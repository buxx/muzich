<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UsersElementsFavoritesRepository extends EntityRepository
{
  
  /**
   * Retourne tous les tags des favoris de l'utilisateur
   * 
   * @return doctrine_collection
   */
  public function getTags($user_id)
  {
    return $this->getEntityManager()
      ->createQuery('
        SELECT t FROM MuzichCoreBundle:Tag t
        LEFT JOIN t.elements e
        LEFT JOIN e.elements_favorites f
        WHERE f.user = :uid
        ORDER BY t.name ASC'
      )
      ->setParameter('uid', $user_id)
      ->getResult()
    ;
  }
  
}
  