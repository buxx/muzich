<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

class TagRepository extends EntityRepository
{
  
  /**
   * Retourne tous les tags connus sous forme d'un tableau
   * 
   * @return array id => name
   */
  public function getTagsArray()
  {
    $tag_array = array();
    foreach ($this->getEntityManager()
      ->createQuery('
        SELECT t.id, t.name FROM MuzichCoreBundle:Tag t
        ORDER BY t.count DESC'
      )
      ->getArrayResult() as $tag)
    {
      $tag_array[$tag['id']] = $tag['name'];
    }
    return $tag_array;
  }
  
  /**
   * Retourne une Query selectionnant des tags pour leurs id
   * 
   * @param array $ids
   * @return \Doctrine\ORM\Query 
   */
  public function findByIds($ids)
  {
    return $this->getEntityManager()->createQuery("
        SELECT t FROM MuzichCoreBundle:Tag t
        WHERE t.id IN (:tids)
    ")->setParameter('tids', $ids);
  }
  
  public function getTagsWithIds($ids)
  {
    return $this->findByIds($ids)->getResult();
  }
  
  public function getTagsForElementSearch($ids)
  {
    $tags = array();
    if (count($ids))
      {
      foreach ($this->getEntityManager()
        ->createQuery('
          SELECT t.id, t.name FROM MuzichCoreBundle:Tag t
          WHERE t.id IN (:ids)
          ORDER BY t.name ASC'
        )
        ->setParameter('ids', $ids)
        ->getArrayResult() as $tag)
      {
        $tags[$tag['id']] = $tag['name'];
      }
    }
    
    return $tags;
  }
  
  public function countToModerate()
  {
    return $this->getEntityManager()
      ->createQuery("
        SELECT COUNT(t.id) FROM MuzichCoreBundle:Tag t
        WHERE t.tomoderate  = '1'"
      )->getSingleScalarResult()
    ;
  }
  
  public function getToModerate()
  {
    return $this->getEntityManager()
      ->createQuery("
        SELECT t FROM MuzichCoreBundle:Tag t
        WHERE t.tomoderate  = '1'"
      )->getResult()
    ;
  }
  
}
  