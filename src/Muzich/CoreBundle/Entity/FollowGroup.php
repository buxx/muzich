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
  * @ORM\generatedValue(strategy="AUTO")
  */
  protected $id;
  
  /**
   * Ee suiveur
   * 
   * @ORM\ManyToOne(targetEntity="Muzich\UserBundle\Entity\User", inversedBy="followed_groups")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  protected $follower;
  
  /**
   * Groupe suivis
   * 
   * @ORM\ManyToOne(targetEntity="Group", inversedBy="follower")
   * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
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
   * @param Muzich\UserBundle\Entity\User $follower
   */
  public function setFollower(Muzich\UserBundle\Entity\User $follower)
  {
    $this->follower = $follower;
  }

  /**
   * Get follower
   *
   * @return Muzich\UserBundle\Entity\User 
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
}