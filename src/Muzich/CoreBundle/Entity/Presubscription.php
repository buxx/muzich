<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="presubscription")
 * @UniqueEntity("email")
 */
class Presubscription
{
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;
  
  /**
   * @ORM\Column(type="string", length=255, unique=true, nullable=false)
   * @Assert\NotBlank()
   * @Assert\Email()
   * @var type string
   */
  public $email;
  
  /**
   * @ORM\Column(type="string", length=255, nullable=false)
   * @Assert\NotBlank()
   * @var type string
   */
  protected $token;
  
  /**
   * @ORM\Column(type="boolean", nullable=false)
   * @var type boolean
   */
  protected $confirmed = false;
  
  public function __construct()
  {
    $this->token = $this->rand_sha1(32);
  }
  
  protected function rand_sha1($length)
  {
    $max = ceil($length / 40);
    $random = '';
    for ($i = 0; $i < $max; $i ++) {
      $random .= sha1(microtime(true).mt_rand(10000,90000));
    }
    return substr($random, 0, $length);
  }
  
  public function getId()
  {
    return $this->id;
  }
 
  public function getEmail()
  {
    return $this->email;
  }
  
  public function getToken()
  {
    return $this->token;
  }
  
  public function setConfirmed($confirmed)
  {
    $this->confirmed = $confirmed;
  }
  
  public function getConfirmed()
  {
    return $this->confirmed;
  }
  
}