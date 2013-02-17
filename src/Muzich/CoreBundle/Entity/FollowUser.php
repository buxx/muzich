<?php

namespace Muzich\CoreBundle\Entity;

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
  * @ORM\GeneratedValue(strategy="AUTO")
  */
  protected $id;
  
  /**
   * User suiveur
   * 
   * @ORM\ManyToOne(targetEntity="User", inversedBy="followeds_users")
   * @ORM\JoinColumn(name="follower_id", referencedColumnName="id")
   */
  protected $follower;
  
  /**
   * User suivis
   * 
   * @ORM\ManyToOne(targetEntity="User", inversedBy="followers_users")
   * @ORM\JoinColumn(name="followed_id", referencedColumnName="id")
   */
  protected $followed;
  
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