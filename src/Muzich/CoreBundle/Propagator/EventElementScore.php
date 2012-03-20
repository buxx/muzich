<?php

namespace Muzich\CoreBundle\Propagator;

use Muzich\CoreBundle\Propagator\EventPropagator;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Actions\User\Reputation as UserReputation;

/**
 * Description of EventElementScore
 *
 * @author bux
 */
class EventElementScore extends EventPropagator
{  
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
  
}