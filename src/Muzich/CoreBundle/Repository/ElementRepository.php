<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Muzich\CoreBundle\Searcher\ElementSearcher;
use Symfony\Component\Config\Definition\Exception\Exception;

class ElementRepository extends EntityRepository
{
  
  /**
   * Méthode "exemple" pour la suite.
   * 
   * @return array 
   */
  public function findAllOrderedByName()
  {
    return $this->getEntityManager()
      ->createQuery('
        SELECT e FROM MuzichCoreBundle:Element e 
        ORDER BY e.name ASC'
      )
      ->getResult()
    ;
  }
  
  /**
   *
   * @param ElementSearcher $searcher
   * @return Doctrine\ORM\Query
   */
  public function findBySearch(ElementSearcher $searcher, $user_id, $exec_type = 'execute')
  {
    // Tableaux des paramétres
    $params_ids = array();
    $params_select = array();
    $params_select['uid'] = $user_id;
    $order_by = "ORDER BY e_.created DESC, e_.id DESC";
    
    // Première chose, si on impose les element_ids on a pas besoin de faire 
    // le filtrage
    if ($searcher->hasIds())
    {
      // Dans ce cas ou les ids sont déjà donné, on ne peut pas avoir de nouveaux
      // éléments
      if ($searcher->isSearchingNew())
      {
        return $query = $this->getEntityManager()
          ->createQuery("SELECT e FROM MuzichCoreBundle:Element e WHERE 1 = 2")
        ;
      }
      
      if (($id_limit = $searcher->getIdLimit()))
      {
        return $this->getSelectElementForSearchQuery($params_select, $user_id, $searcher->getIds(), $id_limit, $searcher->getCount(), $searcher->getIdsDisplay());
      }
      return $this->getSelectElementForSearchQuery($params_select, $user_id, $searcher->getIds(), null, null, $searcher->getIdsDisplay());
    }
    
    
    // Booléen nous permettant de savoir si un where a déjà été écrit
    $is_where = false;
    
    // Construction des conditions pour la selection d'ids
    $where_tags = '';
    $join_tags  = '';
    if (count(($tags = $searcher->getTags())))
    {
      foreach ($tags as $tag_id => $tag_name)
      {
        // LEFT JOIN car un element n'est pas obligatoirement lié a un/des tags
        $join_tags = " LEFT JOIN e_.tags t_";
        
        // Construction du chere pour les tags
        if ($where_tags == '')
        {
          $where_tags .= ' WHERE (t_.id = :tid'.$tag_id;
        }
        else
        {
          $where_tags .= ' OR t_.id = :tid'.$tag_id;
        }
        $params_ids['tid'.$tag_id] = $tag_id;
      }
      // Fermeture de la parenthése qui isole la condition des tags
      $where_tags .= ')';
      $is_where = true;
    }
    
    // Construction de la condition network
    $join_network  = '';
    $where_network = '';
    if ($searcher->getNetwork() == ElementSearcher::NETWORK_PERSONAL)
    {
      $join_network = 
        " JOIN e_.owner o_"
      // LEFT JOIN car l'element n'est pas obligatoirement lié a un groupe
      . " LEFT JOIN e_.group g_"
      // LEFT JOIN car owner n'est pas obligatoirement lié a des followers
      . " LEFT JOIN o_.followers_users f_"
      // LEFT JOIN car le groupe n'est pas obligatoirement lié a des followers
      . " LEFT JOIN g_.followers gf_"
      ;
      $where_network = ($is_where) ? ' AND' : ' WHERE';
      $is_where = true;
      // Le filtre applique: Soit le proprio fait partis des followeds de l'utilisateur
      // soit l'element est ajouté dans un groupe que l'utilisateur follow.
      $where_network .= ' (f_.follower = :userid OR gf_.follower = :useridg)';
      $params_ids['userid'] = $user_id;
      $params_ids['useridg'] = $user_id;
    }
    
    // ajout du filtre sur un user si c'est le cas
    $where_user = '';
    //                                                  Si c'est une recherche 
    //                de favoris, on ne filtre pas sur le proprio de l'element
    if (($search_user_id = $searcher->getUserId()) && !$searcher->isFavorite())
    {
      $where_user = ($is_where) ? ' AND' : ' WHERE';
      $is_where = true;
      $where_user .= ' e_.owner = :suid';
      $params_ids['suid'] = $search_user_id;
    }
    
    // ajout du filtre sur un user si c'est le cas
    $where_group = '';
    //                                                 Si c'est une recherche 
    //               de favoris, on ne filtre pas sur le proprio de l'element
    if (($search_group_id = $searcher->getGroupId()) && !$searcher->isFavorite())
    {
      $where_group = ($is_where) ? ' AND' : ' WHERE';
      $is_where = true;
      $where_group .= ' e_.group = :sgid';
      $params_ids['sgid'] = $search_group_id;
    }
    
    // Filtre pour afficher uniquement les elements mis en favoris
    $join_favorite = ''; 
    $where_favorite = '';
    if ($searcher->isFavorite())
    {
      $where_favorite = ($is_where) ? ' AND' : ' WHERE';
      $is_where = true;
      if (($favorite_user_id = $searcher->getUserId()) && !$searcher->getGroupId())
      {
        // Pas de LEFT JOIN car on ne veut que les elements mis en favoris
        $join_favorite = 'JOIN e_.elements_favorites fav2_';
        $where_favorite .= ' fav2_.user = :fuid';
        $params_ids['fuid'] = $favorite_user_id;
      }
      else if (($favorite_group_id = $searcher->getGroupId()) && !$searcher->getUserId())
      {
        // TODO: Faire en sorte que ça affiche les favoris des gens suivant
        // le groupe
      }
      else
      {
        throw new Exception('For use favorite search element, you must specify an user_id or group_id');
      }
    }
    
    // Si id_limit est précisé c'est que l'on demande "la suite" ou "les nouveaux"
    $where_id_limit = '';
    if (($id_limit = $searcher->getIdLimit()) && !$searcher->isSearchingNew())
    {
      $where_id_limit = ($is_where) ? ' AND' : ' WHERE';
      $is_where = true;
      $where_id_limit .= " e_.id < :id_limit";
      $params_ids['id_limit'] = $id_limit;
    }
    elseif ($id_limit && $searcher->isSearchingNew())
    {
      $where_id_limit = ($is_where) ? ' AND' : ' WHERE';
      $is_where = true;
      $where_id_limit .= " e_.id > :id_limit";
      $params_ids['id_limit'] = $id_limit;
      // Pour pouvoir charger les x nouveaux on doit organiser la liste 
      // de manière croissante
      $order_by = "ORDER BY e_.created ASC, e_.id ASC";
    }
    
    // Requête qui selectionnera les ids en fonction des critéres
    $id_query = $this->getEntityManager()
      ->createQuery(
        "SELECT e_.id
        FROM MuzichCoreBundle:Element e_
        $join_tags
        $join_network
        $join_favorite
        $where_tags
        $where_network
        $where_user
        $where_group
        $where_favorite
        $where_id_limit
        GROUP BY e_.id
        $order_by")
     ->setParameters($params_ids)
    ;
    
    // Si on a précisé que l'on voulait un count, pas de limite
    if ($exec_type != 'count')
    {
      $id_query->setMaxResults($searcher->getCount());
    }
    
    // si l'on a demandé un count
    if ($exec_type == 'count')
    {
      // On retourne cette query
      return $id_query;
    }
    
    $r_ids = $id_query->getArrayResult();
    
    $ids = array();
    
    if (count($r_ids))
    {
      foreach ($r_ids as $r_id)
      {
        $ids[] = $r_id['id'];
      }

      return $this->getSelectElementForSearchQuery($params_select, $user_id, $ids);
    }
    
    // Il faut retourner une Query
    return $query = $this->getEntityManager()
      ->createQuery("SELECT e FROM MuzichCoreBundle:Element e WHERE 1 = 2")
    ;
  }
  
  protected function getSelectElementForSearchQuery($params_select, $user_id, $ids, $id_limit = null, $count_limit = null, $ids_display = null)
  {    
    $where = "";
    if ($id_limit)
    {
      $where = "AND e.id < :id_limit";
      $params_select['id_limit'] = $id_limit;
    }
    
    $select = '';
    $left_join = '';
    if ($ids_display)
    {
      $select = ', tp, tpu, tpt';
      $left_join = 'LEFT JOIN e.tags_propositions tp LEFT JOIN tp.user tpu LEFT JOIN tp.tags tpt';
    }
      
    // C'est la requête qui récupérera les données element avec ses jointures.
    $query_select = "SELECT e, t, o, g, fav $select
      FROM MuzichCoreBundle:Element e 
      LEFT JOIN e.group g 
      LEFT JOIN e.tags t WITH (t.tomoderate = 'FALSE' OR t.tomoderate IS NULL
        OR t.privateids LIKE :uidt)
      LEFT JOIN e.elements_favorites fav WITH fav.user = :uid
      $left_join
      JOIN e.owner o
      WHERE e.id IN (:ids) $where
      ORDER BY e.created DESC, e.id DESC"
    ;

    $params_select['ids'] = $ids;
    $params_select['uidt'] = '%"'.$user_id.'"%';
    $query = $this->getEntityManager()
      ->createQuery($query_select)
      ->setParameters($params_select)
    ;
    
    if ($count_limit)
    {
      $query->setMaxResults($count_limit);
    }

    return $query;
  }
  
  /**
   * Retourne une requete selectionnant les Elements en fonction du
   * propriétaire.
   * 
   * @param int $user_id
   * @param int $limit
   * @return type Doctrine\ORM\Query
   */
  public function findByUser($user_id, $limit)
  {
    return $this->getEntityManager()
      ->createQuery('
        SELECT e, u, g, t FROM MuzichCoreBundle:Element e
        JOIN e.owner u
        JOIN e.group g
        JOIN e.tags t
        WHERE u.id = :uid
        ORDER BY e.created DESC'
      )
      ->setParameter('uid', $user_id)
      ->setMaxResults($limit)
    ;
  }
  
  /**
   * Retourne une requete selectionnant les Elements en fonction du
   * groupe.
   * 
   * @param int $user_id
   * @param int $limit
   * @return type Doctrine\ORM\Query
   */
  public function findByGroup($group_id, $limit)
  {
    return $this->getEntityManager()
      ->createQuery('
        SELECT e, u, g, t FROM MuzichCoreBundle:Element e
        JOIN e.owner u
        JOIN e.group g
        JOIN e.tags t
        WHERE g.id = :gid
        ORDER BY e.created DESC'
      )
      ->setParameter('gid', $group_id)
      ->setMaxResults($limit)
    ;
  }  
  
  public function countToModerate()
  {
    return $this->getEntityManager()
      ->createQuery("SELECT COUNT(e.id) FROM MuzichCoreBundle:Element e
        WHERE e.count_report IS NOT NULL"
      )->getSingleScalarResult()
    ;
  }
  
  public function getToModerate()
  {
    return $this->getEntityManager()
      ->createQuery("SELECT e FROM MuzichCoreBundle:Element e
        WHERE e.count_report  IS NOT NULL"
      )->getResult()
    ;
  }  
  
  public function countForCommentToModerate()
  {
    return $this->getEntityManager()
      ->createQuery("SELECT COUNT(e.id) FROM MuzichCoreBundle:Element e
        WHERE e.count_comment_report IS NOT NULL AND e.count_comment_report != 0"
      )->getSingleScalarResult()
    ;
  }
  
  public function getForCommentToModerate()
  {
    return $this->getEntityManager()
      ->createQuery("SELECT e FROM MuzichCoreBundle:Element e
        WHERE e.count_comment_report IS NOT NULL AND e.count_comment_report != 0"
      )->getResult()
    ;
  }
  
}