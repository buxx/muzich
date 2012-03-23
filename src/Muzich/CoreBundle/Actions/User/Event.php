<?php

namespace Muzich\CoreBundle\Actions\User;

use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Entity\Event as EventEntity;

/**
 * Description of Reputation
 *
 * @author bux
 */
class Event
{
  
  /**
   *
   * @var User 
   */
  protected $user;
  protected $event;
  
  public function __construct(User $user, EventEntity $event)
  {
    $this->user = $user;
    $this->event = $event;
  }
  
  public function updateEvent($element_id)
  {
    $this->event->addId($element_id);
  }
  
  public function createEvent($type, $element_id)
  {
    $this->event->addId($element_id);
    $this->event->setType($type);
    $this->event->setUser($this->user);
  }
}
