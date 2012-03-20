<?php

namespace Muzich\CoreBundle\Actions\User;

use Muzich\CoreBundle\Entity\User;

/**
 * Description of Reputation
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
  
  public function addPoints($points)
  {
    $this->user->setReputation($this->user->getReputation()+$points);
  }
  
  public function removePoints($points)
  {
    $this->user->setReputation($this->user->getReputation()-$points);
  }
}
