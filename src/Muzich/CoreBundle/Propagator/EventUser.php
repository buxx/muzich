<?php

namespace Muzich\CoreBundle\Propagator;

use Muzich\CoreBundle\Propagator\EventPropagator;
use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Actions\User\Event as UserEventAction;
use Muzich\CoreBundle\Actions\User\Reputation as UserReputation;
use Muzich\CoreBundle\Entity\Event;
use Muzich\CoreBundle\Security\Context as SecurityContext;

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
  public function addToFollow(User $user, User $follower)
  {
    // Points de réputation
    $ur = new UserReputation($user);
    $security_context = new SecurityContext($follower);
    if (!$security_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_USER_FOLLOW))
    {
      $ur->addPoints(
        $this->container->getParameter('reputation_element_follow_value')
      );
    }
    
    // Event de suivis
    $uea = new UserEventAction($user, $this->container);
    $event = $uea->proceed(Event::TYPE_USER_FOLLOW, $follower->getId());
    $this->container->get('doctrine')->getEntityManager()->persist($event);
  }
  
  /**
   * L'utilisateur n'est plus suivit par un autre utilisateur
   * 
   * @param User $user Utilisateur plus suivis
   */
  public function removeFromFollow(User $user, User $old_follower)
  {
    $ur = new UserReputation($user);
    $security_context = new SecurityContext($old_follower);
    if (!$security_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_USER_FOLLOW))
    {
      $ur->removePoints(
        $this->container->getParameter('reputation_element_follow_value')
      );
    }
  }
  
}