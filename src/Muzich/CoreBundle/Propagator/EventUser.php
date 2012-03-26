<?php

namespace Muzich\CoreBundle\Propagator;

use Muzich\CoreBundle\Propagator\EventPropagator;
use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Actions\User\Event as UserEventAction;
use Muzich\CoreBundle\Actions\User\Reputation as UserReputation;
use Muzich\CoreBundle\Entity\Event;

/**
 * Propagateur d'événement concernant les users
 *
 * @author bux
 */
class EventUser extends EventPropagator
{  
  
  /**
   * L'utilisateur est suivis par un autre utilisateur
   * 
   * @param User $user Utilisateur suivis
   */
  public function addToFollow(User $user)
  {
    $ur = new UserReputation($user);
    $ur->addPoints(
      $this->container->getParameter('reputation_element_follow_value')
    );
  }
  
  /**
   * L'utilisateur n'est plus suivit par un autre utilisateur
   * 
   * @param User $user Utilisateur plus suivis
   */
  public function removeFromFollow(User $user)
  {
    $ur = new UserReputation($user);
    $ur->removePoints(
      $this->container->getParameter('reputation_element_follow_value')
    );
  }
  
}