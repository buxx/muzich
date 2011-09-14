<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
  
  /**
   * Retourne les tag id préférés de l'user.
   * 
   * @param int $user_id
   * @param int $limit
   * @return array 
   */
  public function getTagIdsFavorites($user_id, $limit)
  {
    $tag_ids = array();
    foreach ($this->getEntityManager()
      ->createQuery('
        SELECT t.id FROM MuzichCoreBundle:Tag t 
        JOIN t.users_favorites uf
        JOIN uf.user u
        WHERE u.id = :uid
        ORDER BY uf.position ASC'
      )
      ->setParameter('uid', $user_id)
      ->setMaxResults($limit)
      ->getArrayResult() as $tag)
    {
      $tag_ids[] = $tag['id'];
    }
    return $tag_ids;
  }
  
}
  