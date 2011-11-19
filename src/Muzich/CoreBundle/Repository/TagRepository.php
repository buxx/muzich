<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

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
  
}
  