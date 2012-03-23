<?php

namespace Muzich\CoreBundle\Actions\User;

use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Entity\Event as EventEntity;

/**
 * Refactorisation d'actions lié aux événement de l'utilisateur
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
  
  /*
   * Mise a jour de l'objet Eevnt
   */
  public function updateEvent($element_id)
  {
    $this->event->addId($element_id);
  }
  
  /**
   * Création d'un objet Event
   * 
   * @param string $type
   * @param int $element_id 
   */
  public function createEvent($type, $element_id)
  {
    $this->event->addId($element_id);
    $this->event->setType($type);
    $this->event->setUser($this->user);
  }
  
}
