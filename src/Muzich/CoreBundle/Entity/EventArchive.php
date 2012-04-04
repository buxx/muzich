<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * EventArchive contient l'archive des événement passés nécéssaire pour des 
 * calculs.
 * 
 * @ORM\Entity
 * @ORM\Table(name="event_archive")
 * @ORM\Entity(repositoryClass="Muzich\CoreBundle\Repository\EventArchiveRepository")
 * 
 */
class EventArchive
{
  
  const PROP_TAGS_ELEMENT_ACCEPTED = "pro.tagel.ac";
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;
  
  /**
   * Type (constitué de la valeur d'une des constantes d'Event)
   * 
   * @ORM\Column(type = "string", length = 12)
   * @var type string
   */
  protected $type;
  
  /**
   * Propriétaire de l'archive d'event
   * 
   * @ORM\ManyToOne(targetEntity="User", inversedBy="events")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  protected $user;
  
  /**
   * Compteur de fois ou l'évnènement c'est produit
   * 
   * @ORM\Column(type="integer", nullable=true)
   * @var int 
   */
  protected $count;
  
  /*
   * 
   * getters / setters
   * 
   */
  
  public function getId()
  {
    return $this->id;
  }
  
  public function getCount()
  {
    return $this->count;
  }
  
  public function setCount($count)
  {
    $this->count = $count;
  }
  
  public function getType()
  {
    return $this->type;
  }
  
  public function setType($type)
  {
    $this->type = $type;
  }
  
  public function getUser()
  {
    return $this->user;
  }
  
  public function setUser($user)
  {
    $this->user = $user;
  }
  
  /*
   * other methods
   * 
   */
  
  
}