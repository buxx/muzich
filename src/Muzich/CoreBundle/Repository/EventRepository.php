<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
  
  public function getNotViewEvents($user_id)
  {
    $events = array();
    $result = $this->getEntityManager()
      ->createQuery('
        SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND 
        (e.view = \'FALSE\' OR e.view = \'0\')'
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
  
}