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
  
  public function getId()
  {
    return $this->id;
  }
  
}