<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
  
  public function getEvents($user_id)
  {
    $events = array();
    $result = $this->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $user_id)
      ->getArrayResult()
    ;
    
    foreach ($result as $event)
    {
      $events[$event['type']] = $event;
    }
    
    return $events;
  }
  
  public function findUserEventWithElementId($user_id, $element_id, $event_type)
  {
    $query =  $this->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND
        e.ids LIKE :eid AND
        e.type = :etype'
      )
      ->setParameters(array(
        'uid' => $user_id,
        'eid' => '%"'.$element_id.'"%',
        'etype' => $event_type
      ))
    ;
    try {
        return $query->getSingleResult();
    } catch (\Doctrine\ORM\NoResultException $e) {
        return null;
    }
  }
  
}