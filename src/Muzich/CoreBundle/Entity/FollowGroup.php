<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Cet entité est le lien entre un User et un Group. Pour exprimer que
 * le follower suit un Groupe.
 * 
 * @ORM\Entity
 * @ORM\Table(name="follow_group")
 */
class FollowGroup
{
  
  /**
  * @ORM\Id
  * @ORM\Column(type="integer")
  * @ORM\GeneratedValue(strategy="AUTO")
  */
  protected $id;
  
  /**
   * Ee suiveur
   * 
   * @ORM\ManyToOne(targetEntity="User", inversedBy="followed_groups")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  protected $follower;
  
  /**
   * Groupe suivis
   * 
   * @ORM\ManyToOne(targetEntity="Group", inversedBy="followers")
   * @ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")
   */
  protected $group;
  
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
   * Set group
   *
   * @param Group $group
   */
  public function setGroup(Group $group)
  {
    $this->group = $group;
  }

  /**
   * Get group
   *
   * @return Group 
   */
  public function getGroup()
  {
    return $this->group;
  }
  
  /**
   * Retourne le groupe suivis
   * 
   * @return Group 
   */
  public function getFollowed()
  {
    return $this->group;
  }
}