<?php

namespace Muzich\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="muzich_user")
 */
class User extends BaseUser
{
  
  /**
   * @ORM\ManyToMany(targetEntity="Muzich\CoreBundle\Entity\Tag", inversedBy="users_favorites")
   * @ORM\JoinTable(name="users_tags_favorites")
   */
  private $tags_favorites;
  
 /**
  * @ORM\Id
  * @ORM\Column(type="integer")
  * @ORM\generatedValue(strategy="AUTO")
  */
  protected $id;

  public function __construct()
  {
    $this->tags_favorites = new \Doctrine\Common\Collections\ArrayCollection();
    parent::__construct();
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
   * Add tags_favorites
   *
   * @param Muzich\UserBundle\Entity\Tag $tagsFavorites
   */
  public function addTag(\Muzich\UserBundle\Entity\Tag $tagsFavorites)
  {
      $this->tags_favorites[] = $tagsFavorites;
  }

  /**
   * Get tags_favorites
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getTagsFavorites()
  {
      return $this->tags_favorites;
  }
  
}