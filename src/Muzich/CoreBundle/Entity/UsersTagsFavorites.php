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
   * @ORM\ManyToOne(targetEntity="Muzich\UserBundle\Entity\User", inversedBy="tags")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  protected $user;
  
  /**
   * @ORM\ManyToOne(targetEntity="Tag", inversedBy="users")
   * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
   */
  protected $tag;
  
  
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
    public function setUser(Muzich\UserBundle\Entity\User $user)
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
     * Set tag
     *
     * @param Muzich\CoreBundle\Entity\Tag $tag
     */
    public function setTag(Muzich\CoreBundle\Entity\Tag $tag)
    {
        $this->tag = $tag;
    }

    /**
     * Get tag
     *
     * @return Muzich\CoreBundle\Entity\Tag 
     */
    public function getTag()
    {
        return $this->tag;
    }
}