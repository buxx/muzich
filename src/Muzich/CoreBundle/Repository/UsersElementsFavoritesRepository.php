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
  
  public function countFavoritedForUserElements($user_id, $ids)
  {
    if (count($ids))
    {
      if ($user_id)
      {
        return count($this->getEntityManager()
          ->createQuery('SELECT COUNT(f.id) FROM MuzichCoreBundle:UsersElementsFavorites f
            WHERE f.user != :uid AND f.element IN (:eids)
            GROUP BY f.element')
          ->setParameters(array('uid'=> $user_id, 'eids' => $ids))
          ->getScalarResult())
        ;
      }
      else
      {
        return count($this->getEntityManager()
          ->createQuery('SELECT COUNT(f.id) FROM MuzichCoreBundle:UsersElementsFavorites f
            WHERE f.element IN (:eids)
            GROUP BY f.element')
          ->setParameter('eids', $ids)
          ->getScalarResult())
        ;
      }
    }
    return array();
  }
  
  public function countFavoritedUsersForUserElements($user_id, $ids)
  {
    if (count($ids))
    {
      if ($user_id)
      {
        return count($this->getEntityManager()
          ->createQuery('SELECT COUNT(f.id) FROM MuzichCoreBundle:UsersElementsFavorites f
            WHERE f.user != :uid AND f.element IN (:eids)
            GROUP BY f.user')
          ->setParameters(array('uid'=> $user_id, 'eids' => $ids))
          ->getScalarResult())
        ;
      }
      else
      {
        return count($this->getEntityManager()
          ->createQuery('SELECT COUNT(f.id) FROM MuzichCoreBundle:UsersElementsFavorites f
            WHERE f.element IN (:eids)
            GROUP BY f.user')
          ->setParameter('eids', $ids)
          ->getScalarResult())
        ;
      }
    }
    return null;
  }
  
}
  