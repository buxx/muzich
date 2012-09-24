<?php

namespace Muzich\CoreBundle\Actions\User;

use Muzich\CoreBundle\Entity\User;

/**
 * Refactorisation d'actions lié a la réputation de l'utilisateur
 *
 * @author bux
 */
class Reputation
{
  
  /**
   *
   * @var User 
   */
  protected $user;
  
  public function __construct(User $user)
  {
    $this->user = $user;
  }
  
  /**
   * Ajoute des points a l'objet User
   * 
   * @param int $points 
   */
  public function addPoints($points)
  {
    $this->user->setReputation($this->user->getReputation()+$points);
  }
  
  /**
   * Retirer des points a l'objet User
   * 
   * @param int $points 
   */
  public function removePoints($points)
  {
    $this->user->setReputation($this->user->getReputation()-$points);
  }
}
