<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cette classe représente la relation porteuse entre User et Element, 
 * en tant qu'éléments favoris de l'utilisateur.
 * 
 * @ORM\Entity
 * @ORM\Table(name="users_elements_favorites")
 */
class UsersElementsFavorites
{
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;
  
  /**
   * Cet attribut contient l'objet User lié
   * 
   * @ORM\ManyToOne(targetEntity="Muzich\UserBundle\Entity\User", inversedBy="tags")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  protected $user;
  
  /**
   * Cet attribut contient l'objet Element lié
   * 
   * @ORM\ManyToOne(targetEntity="Tag", inversedBy="users")
   * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
   */
  protected $element;
  

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
   * Set user
   *
   * @param Muzich\UserBundle\Entity\User $user
   */
  public function setUser(\Muzich\UserBundle\Entity\User $user)
  {
    $this->user = $user;
  }

  /**
   * Get user
   *
   * @return Muzich\UserBundle\Entity\User 
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * Set element
   *
   * @param Muzich\CoreBundle\Entity\Tag $element
   */
  public function setElement(\Muzich\CoreBundle\Entity\Tag $element)
  {
    $this->element = $element;
  }

  /**
   * Get element
   *
   * @return Muzich\CoreBundle\Entity\Tag 
   */
  public function getElement()
  {
    return $this->element;
  }
  
}