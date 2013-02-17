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
   * @Assert\Length(min = 3, max = 32)
   * @var type string
   */
  protected $token;
  
  /**
   * Si ce boolean est a vrai il n'est plus utilisable
   * @ORM\Column(type="boolean", nullable=true)
   * @var boolean
   */
  protected $used = false;
  
  /**
   * @ORM\Column(type="integer", nullable=true)
   * @var int
   */
  protected $count = 0;
  
  /**
   * @ORM\Column(type="integer", nullable=true)
   * @var int
   */
  protected $count_max = 1;
    
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
  
  public function getCount()
  {
    if (!$this->count)
    {
      return 0;
    }
    return $this->count;
  }
  
  public function setCount($count)
  {
    $this->count = $count;
  }
  
  public function addUseCount()
  {
    $this->setCount($this->getCount()+1);
    if ($this->getCount() >= $this->count_max)
    {
      $this->setUsed(true);
    }
  }
  
  public function getCountMax()
  {
    return $this->count_max;
  }
  
  public function setCountMax($count_max)
  {
    $this->count_max = $count_max;
  }

}