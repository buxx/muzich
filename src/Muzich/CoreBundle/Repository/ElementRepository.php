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
  public function findBySearch(ElementSearcher $searcher, $user_id)
  {    
    // Tableaux des paramétres
    $params_ids = array();
    $params_select = array();
    $params_select['uid'] = $user_id;
    
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
    
    // Si id_limit est précisé c'est que l'on demande "la suite"
    $where_id_limit = '';
    if (($id_limit = $searcher->getIdLimit()))
    {
      $where_id_limit = ($is_where) ? ' AND' : ' WHERE';
      $is_where = true;
      $where_id_limit .= " e_.id < :id_limit";
      $params_ids['id_limit'] = $id_limit;
    }
    
    // Requête qui selectionnera les ids en fonction des critéres
    $r_ids = $this->getEntityManager()
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
        ORDER BY e_.created DESC, e_.id DESC")
     ->setParameters($params_ids)
     ->setMaxResults($searcher->getCount())
     ->getArrayResult()
    ;
    
    $ids = array();
    
    if (count($r_ids))
    {
      foreach ($r_ids as $r_id)
      {
        $ids[] = $r_id['id'];
      }

      // C'est la requête qui récupérera les données element avec ses jointures.
      $query_select = "SELECT e, t, o, g, fav
        FROM MuzichCoreBundle:Element e 
        LEFT JOIN e.group g 
        LEFT JOIN e.tags t 
        LEFT JOIN e.elements_favorites fav WITH fav.user = :uid
        JOIN e.owner o
        WHERE e.id IN (:ids)
        ORDER BY e.created DESC, e.id DESC"
      ;

      $params_select['ids'] = $ids;
      $query = $this->getEntityManager()
        ->createQuery($query_select)
        ->setParameters($params_select)
      ;

      return $query;
    }
    
    // Il faut retourner une Query
    return $query = $this->getEntityManager()
      ->createQuery("SELECT e FROM MuzichCoreBundle:Element e WHERE 1 = 2")
    ;
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
}