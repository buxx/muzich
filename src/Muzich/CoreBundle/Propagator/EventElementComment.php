<?php

namespace Muzich\CoreBundle\Propagator;

use Muzich\CoreBundle\Propagator\EventPropagator;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Actions\User\Event as UserEventAction;
use Muzich\CoreBundle\Entity\Event;

/**
 * Propagateur d'événement concernant les Commentaires d'éléments
 *
 * @author bux
 */
class EventElementComment extends EventPropagator
{  
  
  /**
   * Cette procédure doit être appelé après l'ajout d'un commentaire sur un 
   * événement. Actuellement il:
   * * Met a jour ou créer un objet événement (nouveau commentaire) pour le
   *   propriétaire de l'élément.
   * 
   * @param Element $element 
   */
  public function commentAdded(Element $element)
  {    
    $em = $this->container->get('doctrine')->getEntityManager();
    
    try
    {
      $event = $em->createQuery(
        'SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type'
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