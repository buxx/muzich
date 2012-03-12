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
  public function getTags($user_id, $current_user_id)
  {
    return $this->getEntityManager()
      ->createQuery('
        SELECT t FROM MuzichCoreBundle:Tag t
        LEFT JOIN t.elements e
        LEFT JOIN e.elements_favorites f
        WHERE f.user = :uid
        AND (t.tomoderate = \'FALSE\' OR t.tomoderate IS NULL
          OR t.privateids LIKE :uidt)
        ORDER BY t.name ASC'
      )
      ->setParameters(array('uid' => $user_id, 'uidt' => '%"'.$current_user_id.'"%'))
      ->getResult()
    ;
  }
  
}
  