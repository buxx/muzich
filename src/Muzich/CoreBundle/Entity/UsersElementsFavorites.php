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
   * @ORM\ManyToOne(targetEntity="User", inversedBy="tags")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  protected $user;
  
  /**
   * Cet attribut contient l'objet Element lié
   * 
   * @ORM\ManyToOne(targetEntity="Element", inversedBy="users")
   * @ORM\JoinColumn(name="element_id", referencedColumnName="id", onDelete="CASCADE")
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
   * @param User $user
   */
  public function setUser(User $user)
  {
    $this->user = $user;
  }

  /**
   * Get user
   *
   * @return User 
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * Set element
   *
   * @param Element $element
   */
  public function setElement(Element $element)
  {
    $this->element = $element;
  }

  /**
   * Get element
   *
   * @return Element 
   */
  public function getElement()
  {
    return $this->element;
  }
  
}