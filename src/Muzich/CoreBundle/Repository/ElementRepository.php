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
          JOIN eu.followers_users f WITH f.follower = :userid
        ";
        $params['userid'] = $user_id;
        
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
    
    $query_string = "SELECT e, et, t, eu 
      FROM MuzichCoreBundle:Element e 
      JOIN e.type et JOIN e.tags t $query_with JOIN e.owner eu $join_personal
      ORDER BY e.date_added DESC "
    ;
    //die($query_string);
    $query = $this->getEntityManager()
      ->createQuery($query_string)
      ->setParameters($params)
      ->setMaxResults($searcher->getCount())
    ;
    
    return $query;
  }
  
}