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
        SELECT e, t FROM MuzichCoreBundle:Element e 
        JOIN e.type t
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
    $params = array();
    $join_personal = '';
    //$query_with = '';
    $where = '';
    
    // ajout du filtres de trie avec les tags transmis
    foreach ($searcher->getTags() as $tag_id)
    {
      if ($where == '')
      {
        $where .= 'WHERE (t.id = :tid'.$tag_id;
      }
      else
      {
        $where .= ' OR t.id = :tid'.$tag_id;
      }
      $params['tid'.$tag_id] = $tag_id;
    }
    
    if (count($searcher->getTags()))
    {
      // Si on ne met pas les parenthéses, lorsqu'il y a d'autre where (AND, OR)
      // On perd la précision et des résultats se retrouvent dans le tas
      $where .= ')';
    }
    
    // Ajout du filtre limitant au réseau personel si c'est le cas
    $where_network = '';
    if ($searcher->getNetwork() == ElementSearcher::NETWORK_PERSONAL)
    {
      $join_personal = 
       " LEFT JOIN eu.followers_users f"
      ." LEFT JOIN g.followers gf"
      ;
      $where_network = ($where != '') ? ' AND' : ' WHERE';
      $where_network .= ' (f.follower = :userid OR gf.follower = :useridg)';
      $params['userid'] = $user_id;
      $params['useridg'] = $user_id;
    }
    
    // ajout du filtre sur un user si c'est le cas
    $where_user = '';
    //                                                  Si c'est une recherche 
    //                de favoris, on ne filtre pas sur le proprio de l'element
    if (($search_user_id = $searcher->getUserId()) && !$searcher->isFavorite())
    {
      $where_user = ($where != '') ? ' AND' : ' WHERE';
      $where_user .= ' e.owner = :suid';
      $params['suid'] = $search_user_id;
    }
    
    // ajout du filtre sur un user si c'est le cas
    $where_group = '';
    //                                                 Si c'est une recherche 
    //               de favoris, on ne filtre pas sur le proprio de l'element
    if (($search_group_id = $searcher->getGroupId()) && !$searcher->isFavorite())
    {
      $where_group = ($where != '') ? ' AND' : ' WHERE';
      $where_group .= ' e.group = :sgid';
      $params['sgid'] = $search_group_id;
    }
    
    // Filtre pour afficher que les elements mis en favoris si c'est la demande
    $join_favorite = ''; $where_favorite = '';
    if ($searcher->isFavorite())
    {
      $where_favorite = ($where != '') ? ' AND' : ' WHERE';
      if (($favorite_user_id = $searcher->getUserId()) && !$searcher->getGroupId())
      {
        $join_favorite = 'JOIN e.elements_favorites fav2';
        $where_favorite .= ' fav2.user = :fuid';
        $params['fuid'] = $favorite_user_id;
      }
      else if (($favorite_group_id = $searcher->getGroupId()) && !$searcher->getUserId())
      {
        // TODO: Faire en sorte que ça affiche les favrois des gens suivant
        // le groupe
      }
      else
      {
        throw new Exception('For use favorite search element, you must specify an user_id or group_id');
      }
    }
    
    // Construction de la requête finale
    $query_string = "SELECT e, et, t2, eu, g, fav
      FROM MuzichCoreBundle:Element e 
      LEFT JOIN e.group g 
      LEFT JOIN e.type et 
      LEFT JOIN e.tags t 
      LEFT JOIN e.tags t2 
      LEFT JOIN e.elements_favorites fav WITH fav.user = :uid
      $join_favorite
      JOIN e.owner eu $join_personal
      $where
      $where_network
      $where_user
      $where_group
      $where_favorite
      ORDER BY e.created, e.name DESC "
    ;
    $params['uid'] = $user_id;
    
    $query = $this->getEntityManager()
      ->createQuery($query_string)
      ->setParameters($params)
      ->setMaxResults($searcher->getCount())
    ;
    
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
}