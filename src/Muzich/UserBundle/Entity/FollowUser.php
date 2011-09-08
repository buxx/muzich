<?php

namespace Muzich\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Cet entité est le lien entre deux User. Pour exprimer que
 * le follower suit un followed.
 * 
 * @ORM\Entity
 * @ORM\Table(name="follow_user")
 */
class FollowUser
{
  
  /**
  * @ORM\Id
  * @ORM\Column(type="integer")
  * @ORM\generatedValue(strategy="AUTO")
  */
  protected $id;
  
  /**
   * User suiveur
   * 
   * @ORM\ManyToOne(targetEntity="User", inversedBy="followed")
   * @ORM\JoinColumn(name="follower_id", referencedColumnName="id")
   */
  protected $follower;
  
  /**
   * User suivis
   * 
   * @ORM\ManyToOne(targetEntity="User", inversedBy="follower")
   * @ORM\JoinColumn(name="followed_id", referencedColumnName="id")
   */
  protected $followed;
  
  public function __construct()
  {
    $this->follower = new ArrayCollection();
    $this->followed = new ArrayCollection();
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
   * Set follower
   *
   * @param User $follower
   */
  public function setFollower(User $follower)
  {
    $this->follower = $follower;
  }

  /**
   * Get follower
   *
   * @return User 
   */
  public function getFollower()
  {
    return $this->follower;
  }

  /**
   * Set followed
   *
   * @param User $followed
   */
  public function setFollowed(User $followed)
  {
    $this->followed = $followed;
  }

  /**
   * Get followed
   *
   * @return User 
   */
  public function getFollowed()
  {
    return $this->followed;
  }
}