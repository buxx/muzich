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
  public function findBySearch(ElementSearcher $searcher)
  {
    $query_select = "e, et, t";
    $query_join = "e.type et JOIN e.tags t";
    $query_where = "";
    $query_with = "WITH ";
    $params = array();
    
    switch ($searcher->getNetwork())
    {
      case ElementSearcher::NETWORK_PUBLIC:
        
      break;
    
      case ElementSearcher::NETWORK_PERSONAL:
        
      break;
    }
    
    foreach ($searcher->getTags() as $tag)
    {
      if ($query_with != "WITH ")
      {
        $query_with .= "OR ";
      }
      $query_with .= "t.id = :tagid".$tag->getId()." ";
      $params['tagid'.$tag->getId()] = $tag->getId();
    }
    
    $query_string = "SELECT $query_select 
      FROM MuzichCoreBundle:Element e 
      JOIN $query_join $query_with
      ORDER BY e.date_added DESC"
    ;
    
    $query = $this->getEntityManager()
      ->createQuery($query_string)
      ->setParameters($params)
    ;
    
    return $query;
  }
  
}