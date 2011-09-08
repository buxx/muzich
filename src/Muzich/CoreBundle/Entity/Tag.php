<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tag")
 */
class Tag
{
  
  /**
   * @ORM\ManyToMany(targetEntity="Tag", mappedBy="tags")
   */
  protected $elements;
  
  /**
   * @ORM\ManyToMany(targetEntity="Muzich\UserBundle\Entity\User", mappedBy="tags_favorites")
   */
  protected $users_favorites;
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;
  
  /**
   * @ORM\Column(type="string", length=32)
   * @var type string
   */
  protected $name;
  
  public function __construct()
  {
    $this->users_favorites = new \Doctrine\Common\Collections\ArrayCollection();
    $this->elements = new \Doctrine\Common\Collections\ArrayCollection();
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
   * Set name
   *
   * @param string $name
   */
  public function setName($name)
  {
      $this->name = $name;
  }

  /**
   * Get name
   *
   * @return string 
   */
  public function getName()
  {
      return $this->name;
  }

  /**
   * Add elements
   *
   * @param Muzich\CoreBundle\Entity\Tag $elements
   */
  public function addTag(Muzich\CoreBundle\Entity\Tag $elements)
  {
      $this->elements[] = $elements;
  }

  /**
   * Get elements
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getElements()
  {
      return $this->elements;
  }

  /**
   * Add users_favorites
   *
   * @param Muzich\UserBundle\Entity\User $usersFavorites
   */
  public function addUser(Muzich\UserBundle\Entity\User $usersFavorites)
  {
      $this->users_favorites[] = $usersFavorites;
  }

  /**
   * Get users_favorites
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getUsersFavorites()
  {
      return $this->users_favorites;
  }
    
}