<?php

namespace Muzich\CoreBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * Cet entité est l'utilisateur ayant effectué la requête.
 * 
 * @ORM\Entity
 * @ORM\Table(name="m_user")
 * @ORM\Entity(repositoryClass="Muzich\CoreBundle\Repository\UserRepository")
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
   * @ORM\OneToMany(targetEntity="UsersTagsFavorites", mappedBy="user")
   */
  protected $tags_favorites;
  
  /**
   * Cet attribut contient les enregistrements UsersElementsFavorites lié 
   * a cet utilisateur dans le cadre des éléments Favoris.
   * 
   * @ORM\OneToMany(targetEntity="UsersElementsFavorites", mappedBy="user")
   */
  protected $elements_favorites;
  
  /**
   * Liste des Elements appartenant a cet utilisateur.
   * 
   * @ORM\OneToMany(targetEntity="Element", mappedBy="owner")
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
   * @ORM\OneToMany(targetEntity="FollowGroup", mappedBy="follower")
   */
  protected $followed_groups;
  
  /**
   * Liste des Groupes appartenant a cet utilisateur.
   * 
   * @ORM\OneToMany(targetEntity="Group", mappedBy="owner")
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
   * @param UsersTagsFavorites $tagsFavorites
   */
  public function addUsersTagsFavorites(UsersTagsFavorites $tagsFavorites)
  {
    $this->tags_favorites[] = $tagsFavorites;
  }
  
  /**
   * Add elements_favorites
   *
   * @param UsersElementsFavorites $elementsFavorites
   */
  public function addUsersElementsFavorites(UsersElementsFavorites $elementsFavorites)
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
   * @param Element $elements
   */
  public function addElement(Element $elements)
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
    $users = array();
    foreach ($this->followeds_users as $follow_user)
    {
      $users[] = $follow_user->getFollowed();
    }
    return $users;
  }

  /**
   * Get followers_users
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getFollowersUsers()
  {
    $users = array();
    foreach ($this->followers_users as $follow_user)
    {
      $users[] = $follow_user->getFollower();
    }
    return $users;
  }

  /**
   * Add followed_groups
   *
   * @param FollowGroup $followedGroups
   */
  public function addFollowGroup(FollowGroup $followedGroups)
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
    $groups = array();
    foreach ($this->followed_groups as $follow_group)
    {
      $groups[] = $follow_group->getGroup();
    }
    return $groups;
  }

  /**
   * Add groups
   *
   * @param Group $groups
   */
  public function addGroupOwned(Group $groups)
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
  
  /*
   * 
   * 
   */
  
  
}