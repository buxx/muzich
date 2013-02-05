<?php

namespace Muzich\CoreBundle\Searcher;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;

class ElementSearcherQueryBuilder
{
  
  /**
   *
   * @var \Doctrine\ORM\EntityManager 
   */
  protected $em;
  
  protected $user_id;
  
  /**
   *
   * @var array 
   */
  protected $parameters_ids = array();
  
  /**
   *
   * @var array 
   */
  protected $parameters_elements = array();
  
  /**
   *
   * @var ElementSearcher 
   */
  protected $es;
  
  /**
   *
   * @var QueryBuilder
   */
  protected $query_ids;
  
  /**
   *
   * @var QueryBuilder
   */
  protected $query_elements;
  
  /**
   *
   * @var array 
   */
  protected $builder_params = array();
  
  /**
   *
   * @param EntityManager $em
   * @param ElementSearcher $es 
   */
  public function __construct(EntityManager $em, ElementSearcher $es, $user_id, $builder_params = array())
  {
    $this->em = $em;
    $this->es = $es;
    $this->user_id = $user_id;
    $this->builder_params = $builder_params;
  }
  
  private function buildTags()
  {
    if (count($tags = $this->es->getTags()))
    {
      // Un truc pas propre fait que des fois (quand ajax on dirais) 
      // on a du string a la place du tableau
      if (!is_array($tags))
      {
        
        $tags_decoded = json_decode($tags);
        $tags = array();
        foreach ($tags_decoded as $tag_id)
        {
          $tags[$tag_id] = $tag_id;
        }
      }
      
      
      if (count($tags))
      {
        $str_or = $this->query_ids->expr()->orx();
        $this->query_ids->leftJoin('e.tags', 't');
        foreach ($tags as $tag_id => $tag_name)
        {
          $str_or->add($this->query_ids->expr()->eq('t.id', ":itag".$tag_id));
          $this->parameters_ids["itag".$tag_id] = $tag_id;
        }
        
        $this->query_ids->andWhere($str_or);
      }
      
    }
  }
  
  private function buildString()
  {
    if (($string = $this->es->getString()))
    {
      // On prépare notre liste de mots
      $words = array_unique(array_merge(
        explode(' ', $string),
        explode('-', $string),
        explode('- ', $string),
        explode(' -', $string),
        explode(' - ', $string),
        explode(',', $string),
        explode(', ', $string),
        explode(' ,', $string),
        explode(' , ', $string)
      ));

      // On récupère les ids des elements correspondants
      $word_min_length = 0;
      if (isset($this->builder_params['word_min_length']))
      {
        $word_min_length = $this->builder_params['word_min_length'];
      }

      // On prépare un sous-where avec des or
      $str_or = $this->query_ids->expr()->orx();

      foreach ($words as $i => $word)
      {
        if (strlen($word) >= $word_min_length)
        {
          // On ajoute un or pour chaque mots
          $str_or->add($this->query_ids->expr()->like('UPPER(e.name)', ":str".$i));
          $this->parameters_ids['str'.$i] = '%'.strtoupper($word).'%';
        }
      }

      $this->query_ids->andWhere($str_or);
    }
  }
  
  private function buildNetwork()
  {
    if ($this->es->getNetwork() == ElementSearcher::NETWORK_PERSONAL)
    {
      $this->query_ids
        ->join('e.owner', 'o')
        ->leftJoin('e.group', 'g')
        ->leftJoin('o.followers_users', 'f')
        ->leftJoin('g.followers', 'gf')
      ;
      
      $this->query_ids->andWhere('f.follower = :userid OR gf.follower = :useridg');
      $this->parameters_ids['userid'] = $this->user_id;
      $this->parameters_ids['useridg'] = $this->user_id;
    }
  }
  
  private function buildFavorite()
  {
    if ($this->es->isFavorite())
    {
      if (($favorite_user_id = $this->es->getUserId()) && !$this->es->getGroupId())
      {
        $this->query_ids->leftJoin('e.elements_favorites', 'fav2');
        $this->query_ids->andWhere('fav2.user = :fuid');
        $this->parameters_ids['fuid'] = $favorite_user_id;
      }
      else if (($favorite_group_id = $this->es->getGroupId()) && !$this->es->getUserId())
      {
        // TODO: Faire en sorte que ça affiche les favoris des gens suivant
        // le groupe
      }
      else
      {
        throw new Exception('For use favorite search element, you must specify an user_id or group_id');
      }
    }
  }
  
  private function buildUserAndGroup()
  {
    // (flou) Si on recherche les élements d'un user
    if (($search_user_id = $this->es->getUserId()) && !$this->es->isFavorite())
    {
      $this->query_ids->andWhere('e.owner = :suid');
      $this->parameters_ids['suid'] = $search_user_id;
    }
    
    // (flou) Si on recherche les éléments d'un groupe
    if (($search_group_id = $this->es->getGroupId()) && !$this->es->isFavorite())
    {
      $this->query_ids->andWhere('e.group = :sgid');
      $this->parameters_ids['sgid'] = $search_group_id;
    }
  }
  
  private function buildLimits()
  {
    // Si id_limit est précisé c'est que l'on demande "la suite" ou "les nouveaux"
    if (($id_limit = $this->es->getIdLimit()) && !$this->es->isSearchingNew())
    {
      $this->query_ids->andWhere("e.id < :id_limit");
      $this->parameters_ids['id_limit'] = $id_limit;
    }
    elseif ($id_limit && $this->es->isSearchingNew())
    {
      $this->query_ids->andWhere("e.id > :id_limit");
      $this->parameters_ids['id_limit'] = $id_limit;
      $this->query_ids->orderBy("e.created", 'ASC')
        ->addOrderBy("e.id", 'ASC');
    }
  }

  private function buildStrict()
  {
    // Recherche strict ou non ?
    if ($this->es->getTagStrict() && count(($tags = $this->es->getTags())))
    {
      // On a besoin de récupérer la liste des element_id qui ont les tags
      // demandés.
      $tag_ids = '';
      foreach ($tags as $tag_id => $tag_name)
      {
        if ($tag_ids === '')
        {
          $tag_ids .= (int)$tag_id;
        }
        else
        {
          $tag_ids .= ','.(int)$tag_id;
        }
      }
      
      $sql = "SELECT et.element_id FROM elements_tag et "
      ."WHERE et.tag_id IN ($tag_ids) group by et.element_id "
      ."having count(distinct et.tag_id) = ".count($tags);
      $rsm = new \Doctrine\ORM\Query\ResultSetMapping;
      $rsm->addScalarResult('element_id', 'element_id');
   
      $strict_element_ids_result = $this->em
        ->createNativeQuery($sql, $rsm)
        //->setParameter('ids', $tag_ids)
        ->getScalarResult()
      ;
      
      $strict_element_ids = array();
      if (count($strict_element_ids_result))
      {
        foreach ($strict_element_ids_result as $strict_id)
        {
          $strict_element_ids[] = $strict_id['element_id'];
        }
      }
      
      if (count($strict_element_ids))
      {
        $this->query_ids->andWhere('e.id IN (:tag_strict_ids)');
        $this->parameters_ids['tag_strict_ids'] = $strict_element_ids;
      }
      // Ce else palie au bug du au cas ou $strict_element_ids est egal a array();
      else
      {
        return false;
      }
    }
  }
  
  private function buildNeedTags()
  {
    // Si id_limit est précisé c'est que l'on demande "la suite" ou "les nouveaux"
    if ($this->es->isNeedTags())
    {
      $this->query_ids->andWhere("e.need_tags  = '1'");
    }
  }
  
  private function buildIdsLimits()
  {
    if ($this->es->hasIds())
    {
      $this->query_ids->andWhere('e.id IN (:limiteds_ids)');
      $this->parameters_ids['limiteds_ids'] = $this->es->getIds();
    }
  }

  /**
   *
   * @param boolean $disable_limit 
   */
  protected function proceedIdsQuery($disable_limit = false)
  {
    
    $this->query_ids = $this->em->createQueryBuilder()
      ->select('e.id')
      ->from('MuzichCoreBundle:Element', 'e')
      ->groupBy('e.id')
      ->orderBy('e.created', 'DESC')
      ->addOrderBy('e.id', 'DESC')
    ;
    
    if (!$disable_limit)
    {
      $this->query_ids->setMaxResults($this->es->getCount());
    }
    
    // Prise en compte des tags
    $this->buildTags();
    // Si on effectue une recherche avec un string
    $this->buildString();
    // Paramètrage en fonction du réseau
    $this->buildNetwork();
    // Si on recherche des elements d'user ou de groupe
    $this->buildUserAndGroup();
    // Si on recherche des elements mis en favoris
    $this->buildFavorite();
    // Pour les demandes de "more" ou "nouveaux" elements.
    $this->buildLimits();
    // Si on recherche les tags de manière stricte
    if ($this->buildStrict() === false)
    {
      return false;
    }
    // Si on recherche des partages en demande de tags
    $this->buildNeedTags();
    // Si on a fournis des ids dés le départ
    $this->buildIdsLimits();
    
    $this->query_ids->setParameters($this->parameters_ids);
  }
  
  /**
   *
   * @param boolean $disable_limit
   * @return \Doctrine\ORM\Query
   * @throws \Exception 
   */
  public function getIdsQuery($disable_limit = false)
  {
    //// Contrôle de la demande
    //if ($this->es->hasIds())
    //{
    //  throw new \Exception("Vous demandez un Query_ids avec un ElementSearcher "
    //    ."possédant déjà une liste d'ids");
    //}
    
    if ($this->proceedIdsQuery($disable_limit) === false)
    {
      return false;
    }
    
    return $this->query_ids->getQuery();
  }
  
  protected function proceedElementsQuery()
  {
    
    // On récupère les ids d'éléments
    if (($ids_query = $this->getIdsQuery()) === false)
    {
      return false;
    }
    
    // On va récupérer les ids en base en fonction des paramètres
    $q_ids = $ids_query->getArrayResult();
   
    $element_ids = array();
    if (count($q_ids))
    {
      // On prépare les ids pour la requete des éléments
      foreach ($q_ids as $r_id)
      {
        $element_ids[] = $r_id['id'];
      }
    }
    
    if (!count($element_ids))
    {
      // Si on a pas d'ids on retourne une requete qui ne donnera rien
      return false;
    }
    
    // On prépare des paramètres de la requete d'éléments
    $this->parameters_elements['uidt'] = '%"'. $this->user_id.'"%';
    $this->parameters_elements['uid']  =  $this->user_id;
    $this->parameters_elements['ids']  = $element_ids;
    
    // On prépare la requete des elements
    $this->query_elements = $this->em->createQueryBuilder()
      ->select('e', 'p', 'po', 't', 'o', 'g', 'fav')
      ->from('MuzichCoreBundle:Element', 'e')
      ->leftJoin('e.group', 'g')
      ->leftJoin('e.parent', 'p')
      ->leftJoin('p.owner', 'po')
      ->leftJoin('e.tags', 't', Join::WITH, 
        "(t.tomoderate = 'FALSE' OR t.tomoderate IS NULL OR t.privateids LIKE :uidt)")
      ->leftJoin('e.elements_favorites', 'fav', Join::WITH,
        'fav.user = :uid')
      ->join('e.owner', 'o')
      ->where('e.id IN (:ids)')
      ->orderBy("e.created", 'DESC')
      ->addOrderBy("e.id", 'DESC')
    ;
    
    // Ce code est désactivé: Les ids ont déjà été filtré par la id_query.
//    // Ce cas de figure se présente lorsque l'on fait un ajax de "plus d'éléments"
//    if (($id_limit = $this->es->getIdLimit()))
//    {
//      $this->query_elements->andWhere("e.id < :id_limit");
//      $this->query_elements->setMaxResults($this->es->getCount());
//      $this->parameters_elements['id_limit'] = $id_limit;
//    }
    
    // Lorsque l'on impose les ids (typiquement affichage des éléments avec un commentaire etc)
    // On charge les tags proposés dés la requete pour économiser les échanges avec la bdd
    if (($ids_display = $this->es->getIdsDisplay()))
    {
      $this->query_elements
        ->addSelect('tp', 'tpu', 'tpt')
        ->leftJoin('e.tags_propositions', 'tp') 
        ->leftJoin('tp.user', 'tpu') 
        ->leftJoin('tp.tags', 'tpt')      
      ;
    }
    
    $this->query_elements->setParameters($this->parameters_elements);
  }
  
  public function getElementsQuery()
  {
    if ($this->proceedElementsQuery() === false)
    {
      return $this->em
        ->createQuery("SELECT e FROM MuzichCoreBundle:Element e WHERE 1 = 2")
      ;
    }
    
    return $this->query_elements->getQuery();
  }
  
}