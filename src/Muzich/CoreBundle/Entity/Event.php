<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * L'Element est l'Element central de l'application. C'est cet
 * entité qui stocke le media partagé sur le réseau.
 * 
 * @ORM\Entity
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="Muzich\CoreBundle\Repository\EventRepository")
 * 
 */
class Event
{
  
  const TYPE_COMMENT_ADDED_ELEMENT = "com.adde.ele";
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;
  
  /**
   * array json des id d'éléments/users/...
   * 
   * @ORM\Column(type="text", nullable=true)
   * @var string 
   */
  protected $ids;
  
  /**
   * Compteur d'élément/users concernés
   * 
   * @ORM\Column(type="integer", nullable=true)
   * @var int 
   */
  protected $count;
  
  /**
   * Type (constitué de la valeur d'une des
   * 
   * @ORM\Column(type = "string", length = 12)
   * @var type string
   */
  protected $type;
  
  /**
   * Propriétaire de l'event
   * 
   * @ORM\ManyToOne(targetEntity="User", inversedBy="events")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  protected $user;
  
  /*
   * 
   * getters / setters
   * 
   */
  
  public function getId()
  {
    return $this->id;
  }
  
  public function getIds()
  {
    return json_decode($this->ids, true);
  }
  
  public function setIds($ids)
  {
    $this->ids = json_encode($ids);
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
  
  public function addId($id)
  {
    $ids = $this->getIds();
    if (!count($ids))
    {
      $ids = array();
    }
    
    if (!$this->hasId($id))
    {
      $ids[] = (string)$id;
      $this->setCount(count($ids));
    }
    $this->setIds($ids);
  }
    
  public function hasId($id_check)
  {
    if (count($ids = $this->getIds()))
    {
      foreach ($ids as $id)
      {
        if ($id == $id_check)
        {
          return true;
        }
      }
    }
    return false;
  }
  
}