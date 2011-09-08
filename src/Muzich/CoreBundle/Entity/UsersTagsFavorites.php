<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users_tags_favorites")
 */
class UsersTagsFavorites
{
  
  /**
   * @ORM\ManyToOne(targetEntity="Muzich\UserBundle\Entity\User", inversedBy="tags_favorites")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  protected $users_favorites;
  
  /**
   * @ORM\ManyToOne(targetEntity="Muzich\CoreBundle\Entity\Tag", inversedBy="users_favorites")
   * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
   */
  protected $tags_favorites;
  
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;
  
  /**
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
   * Set users_favorites
   *
   * @param Muzich\UserBundle\Entity\User $usersFavorites
   */
  public function setUsersFavorites(Muzich\UserBundle\Entity\User $usersFavorites)
  {
      $this->users_favorites = $usersFavorites;
  }

  /**
   * Get users_favorites
   *
   * @return \Muzich\UserBundle\Entity\User 
   */
  public function getUsersFavorites()
  {
      return $this->users_favorites;
  }

  /**
   * Set tags_favorites
   *
   * @param Muzich\CoreBundle\Entity\Tag $tagsFavorites
   */
  public function setTagsFavorites(Muzich\CoreBundle\Entity\Tag $tagsFavorites)
  {
      $this->tags_favorites = $tagsFavorites;
  }

  /**
   * Get tags_favorites
   *
   * @return Muzich\CoreBundle\Entity\Tag 
   */
  public function getTagsFavorites()
  {
      return $this->tags_favorites;
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
}