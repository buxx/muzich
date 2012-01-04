<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Le RegistrationToken sert uniquement a contrôler les inscription durant 
 * toute la phase fermé du site en production.
 * 
 * @ORM\Entity
 * @UniqueEntity(fields="token")
 */
class RegistrationToken
{
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;
  
  /**
   * Le token en question
   * 
   * @ORM\Column(type="string", length=32, unique=true)
   * @Assert\NotBlank()
   * @Assert\MinLength(limit=3)
   * @Assert\MaxLength(32)
   * @var type string
   */
  protected $token;
  
  /**
   * Si ce boolean est a vrai il n'est plus utilisable
   * @ORM\Column(type="boolean")
   * @var type boolean
   */
  protected $used = false;
    
  public function __toString()
  {
    return $this->getToken();
  }
  
  /**
   * Get id
   *
   * @return integer 
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set token
   *
   * @param string $token
   */
  public function setToken($token)
  {
    $this->token = $token;
  }

  /**
   * Get token
   *
   * @return string 
   */
  public function getToken()
  {
    return $this->token;
  }

  /**
   * Set used
   *
   * @param boolean $used
   */
  public function setUsed($used)
  {
    $this->used = $used;
  }

  /**
   * Get used
   *
   * @return boolean 
   */
  public function getUsed()
  {
    return $this->used;
  }

}