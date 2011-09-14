<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
  
  /**
   * Retourne les tags
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
  
}
  