<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cette classe représente la relation porteuse entre User et Tag, 
 * en tant que Tags favoris de l'utilisateur.
 * 
 * @ORM\Entity
 * @ORM\Table(name="users_tags_favorites")
 */
class UsersTagsFavorites
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
   * @ORM\ManyToOne(targetEntity="User", inversedBy="tags_favorites")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  protected $user;
  
  /**
   * Cet attribut contient l'objet Tag lié
   * 
   * @ORM\ManyToOne(targetEntity="Tag", inversedBy="users_favorites")
   * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
   */
  protected $tag;
  
  /**
   * L'attribut position permet de connaitre l'ordre de préfèrence de 
   * l'utilisateur.
   * 
   * @ORM\Column(type="integer")
   * @var type int
   */
  protected $position;
    

  /**
   * Set position
   *
   * @param integer $position
   */
  public function setPosition($position)
  {
      $this->position = $position;
  }

  /**
   * Get position
   *
   * @return integer 
   */
  public function getPosition()
  {
      return $this->position;
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
   * Set tag
   *
   * @param Tag $tag
   */
  public function setTag(Tag $tag)
  {
    $this->tag = $tag;
  }

  /**
   * Get tag
   *
   * @return Tag 
   */
  public function getTag()
  {
    return $this->tag;
  }
}