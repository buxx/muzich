<?php

namespace Muzich\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * Cet entité est l'utilisateur ayant effectué la requête.
 * 
 * @ORM\Entity
 * @ORM\Table(name="muzich_user")
 */
class User extends BaseUser
{
  
 /**
  * @ORM\Id
  * @ORM\Column(type="integer")
  * @ORM\generatedValue(strategy="AUTO")
  */
  protected $id;
  
  /**
   * Cet attribut contient les enregistrements UsersTagsFavorites lié 
   * a cet utilisateur dans le cadre des Tags Favoris.
   * 
   * @ORM\OneToMany(targetEntity="Muzich\CoreBundle\Entity\UsersTagsFavorites", mappedBy="user")
   */
  protected $tags_favorites;
  
  /**
   * Cet attribut contient les enregistrements UsersElementsFavorites lié 
   * a cet utilisateur dans le cadre des éléments Favoris.
   * 
   * @ORM\OneToMany(targetEntity="Muzich\CoreBundle\Entity\UsersElementsFavorites", mappedBy="user")
   */
  protected $elements_favorites;
  
  /**
   * Liste des Elements appartenant a cet utilisateur.
   * 
   * @ORM\OneToMany(targetEntity="Muzich\CoreBundle\Entity\Element", mappedBy="owner")
   */
  protected $elements;
  
  /**
   * Users que cet utilisateur suit.
   * 
   * @ORM\OneToMany(targetEntity="FollowUser", mappedBy="follower")
   */
  protected $followeds_users;
  
  /**
   * Users qui suivent cet utilisateur.
   * 
   * @ORM\OneToMany(targetEntity="FollowUser", mappedBy="followed")
   */
  protected $followers_users;
  
  /**
   * Cet attribut contient les enregistrements FollowGroup lié 
   * a cet utilisateur dans le cadre des groupes suivis.
   * 
   * @ORM\OneToMany(targetEntity="Muzich\CoreBundle\Entity\FollowGroup", mappedBy="user")
   */
  protected $followed_groups;
  
  /**
   * Liste des Groupes appartenant a cet utilisateur.
   * 
   * @ORM\OneToMany(targetEntity="Muzich\CoreBundle\Entity\Group", mappedBy="owner")
   */
  protected $groups_owned;

  /**
   * 
   */
  public function __construct()
  {
    $this->tags_favorites = new ArrayCollection();
    $this->elements = new ArrayCollection();
    $this->elements_favorites = new ArrayCollection();
    $this->followeds_users = new ArrayCollection();
    $this->followers_users = new ArrayCollection();
    $this->followed_groups = new ArrayCollection();
    $this->groups = new ArrayCollection();
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
   * Get tags_favorites
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getTagsFavorites()
  {
    return $this->tags_favorites;
  }
  
  /**
   * Add tags_favorites
   *
   * @param Muzich\CoreBundle\Entity\UsersTagsFavorites $tagsFavorites
   */
  public function addUsersTagsFavorites(Muzich\CoreBundle\Entity\UsersTagsFavorites $tagsFavorites)
  {
    $this->tags_favorites[] = $tagsFavorites;
  }
  
  /**
   * Add elements_favorites
   *
   * @param Muzich\CoreBundle\Entity\UsersElementsFavorites $elementsFavorites
   */
  public function addUsersElementsFavorites(Muzich\CoreBundle\Entity\UsersElementsFavorites $elementsFavorites)
  {
    $this->elements_favorites[] = $elementsFavorites;
  }

  /**
   * Get elements_favorites
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getElementsFavorites()
  {
    return $this->elements_favorites;
  }

  /**
   * Add elements
   *
   * @param Muzich\CoreBundle\Entity\Element $elements
   */
  public function addElement(Muzich\CoreBundle\Entity\Element $elements)
  {
    $this->elements[] = $elements;
  }

  /**
   * Get elements
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getElements()
  {
    return $this->elements;
  }

  /**
   * Add followeds_users
   *
   * @param FollowUser $followedsUsers
   */
  public function addFollowUser(FollowUser $followedsUsers)
  {
    $this->followeds_users[] = $followedsUsers;
  }

  /**
   * Get followeds_users
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getFollowedsUsers()
  {
    return $this->followeds_users;
  }

  /**
   * Get followers_users
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getFollowersUsers()
  {
    return $this->followers_users;
  }

  /**
   * Add followed_groups
   *
   * @param Muzich\CoreBundle\Entity\FollowGroup $followedGroups
   */
  public function addFollowGroup(Muzich\CoreBundle\Entity\FollowGroup $followedGroups)
  {
    $this->followed_groups[] = $followedGroups;
  }

  /**
   * Get followed_groups
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getFollowedGroups()
  {
    return $this->followed_groups;
  }

  /**
   * Add groups
   *
   * @param Muzich\CoreBundle\Entity\Group $groups
   */
  public function addGroupOwned(Muzich\CoreBundle\Entity\Group $groups)
  {
    $this->groups[] = $groups;
  }

  /**
   * Get groups
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getGroupsOnwed()
  {
    return $this->groups;
  }
}