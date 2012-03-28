<?php

namespace Muzich\CoreBundle\Propagator;

use Muzich\CoreBundle\Propagator\EventPropagator;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Actions\User\Event as UserEventAction;
use Muzich\CoreBundle\Actions\User\Reputation as UserReputation;
use Muzich\CoreBundle\Entity\Event;

/**
 * Propagateur d'événement concernant les éléments
 *
 * @author bux
 */
class EventElement extends EventPropagator
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
    $uea = new UserEventAction($element->getOwner(), $this->container);
    $event = $uea->proceed(Event::TYPE_COMMENT_ADDED_ELEMENT, $element->getId());
    $this->container->get('doctrine')->getEntityManager()->persist($event);
  }
  
  /**
   * Un point a été ajouté par quelqu'un a cet élément
   * Conséquences:
   *  * L'auteur du partage gagne x point de reputation
   *
   * @param Element $element 
   */
  public function onePointAdded(Element $element)
  {
    $ur = new UserReputation($element->getOwner());
    $ur->addPoints(
      $this->container->getParameter('reputation_element_point_value')
    );
  }
  
  /**
   * Un point a été retiré par quelqu'un a cet élément
   * Conséquences:
   *  * L'auteur du partage perd x point de reputation
   *
   * @param Element $element 
   */
  public function onePointRemoved(Element $element)
  {
    $ur = new UserReputation($element->getOwner());
    $ur->removePoints(
      $this->container->getParameter('reputation_element_point_value')
    );
  }
  
  /**
   * L'élément a été ajouté aux favoris d'un utilisateur
   * 
   * @param Element $element 
   */
  public function addedToFavorites(Element $element)
  {
    $ur = new UserReputation($element->getOwner());
    $ur->addPoints(
      $this->container->getParameter('reputation_element_favorite_value')
    );
    
    $uea = new UserEventAction($element->getOwner(), $this->container);
    $event = $uea->proceed(Event::TYPE_FAV_ADDED_ELEMENT, $element->getId());
    $this->container->get('doctrine')->getEntityManager()->persist($event);
  }
  
  /**
   * L'élément a été retiré des favoris d'un utilisateur
   * 
   * @param Element $element 
   */
  public function removedFromFavorites(Element $element)
  {
    $ur = new UserReputation($element->getOwner());
    $ur->removePoints(
      $this->container->getParameter('reputation_element_favorite_value')
    );
  }
  
}