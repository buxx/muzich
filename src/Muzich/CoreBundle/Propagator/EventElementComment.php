<?php

namespace Muzich\CoreBundle\Propagator;

use Muzich\CoreBundle\Propagator\EventPropagator;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Actions\User\Event as UserEventAction;
use Muzich\CoreBundle\Entity\Event;

/**
 * Description of EventElementScore
 *
 * @author bux
 */
class EventElementComment extends EventPropagator
{  
  
  public function commentAdded(Element $element)
  {    
    $em = $this->container->get('doctrine')->getEntityManager();
    
    try
    {
      $event = $em->createQuery(
        'SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type AND 
          (e.view = \'FALSE\' OR e.view = \'0\')'
      )->setParameters(array(
        'uid' => $element->getOwner()->getId(),
        'type' => Event::TYPE_COMMENT_ADDED_ELEMENT
      ))->getSingleResult()
      ;
      $new = false;
    } 
    catch (\Doctrine\ORM\NoResultException $e)
    {
      $event = new Event();
      $new = true;
    }
    
    $uea = new UserEventAction($element->getOwner(), $event);
    if ($new)
    {
      $uea->createEvent(
        Event::TYPE_COMMENT_ADDED_ELEMENT,
        $element->getId()
      );
    }
    else
    {
      $uea->updateEvent($element->getId());
    }
    
    $em->persist($event);
  }
  
}