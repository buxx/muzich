<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Muzich\CoreBundle\Searcher\ElementSearcher;

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
  {;
    $params = array();
    $join_personal = '';
    
    switch ($searcher->getNetwork())
    {
      case ElementSearcher::NETWORK_PERSONAL:
        
        $join_personal = "
          LEFT JOIN eu.followers_users f WITH f.follower = :userid "
          ."LEFT JOIN g.followers gf WITH gf.follower = :useridg"
          ;
        $params['userid'] = $user_id;
        $params['useridg'] = $user_id;
        
      break;
    }
    
    $query_with = "WITH ";
    foreach ($searcher->getTags() as $tag_id)
    {
      if ($query_with != "WITH ")
      {
        $query_with .= "OR ";
      }
      $query_with .= "t.id = :tagid".$tag_id." ";
      $params['tagid'.$tag_id] = $tag_id;
    }
    
    $query_join2 = ' JOIN e.owner';
    
    $query_string = "SELECT e, et, t, eu, g
      FROM MuzichCoreBundle:Element e 
      LEFT JOIN e.group g JOIN e.type et JOIN e.tags t $query_with 
        JOIN e.owner eu $join_personal
      ORDER BY e.date_added DESC "
    ;
    
//    $query_string = "
//      SELECT e, g
//      FROM MuzichCoreBundle:Element e 
//      LEFT JOIN e.group g
//      ORDER BY e.date_added DESC
//    ";
    
    $query = $this->getEntityManager()
      ->createQuery($query_string)
      ->setParameters($params)
      ->setMaxResults($searcher->getCount())
    ;
    
    return $query;
  }
  
}