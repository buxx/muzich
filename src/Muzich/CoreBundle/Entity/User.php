<?php

namespace Muzich\CoreBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\EntityManager;
use Muzich\CoreBundle\Entity\UsersTagsFavorites;

/**
 * Cet entité est l'utilisateur ayant effectué la requête.
 * 
 * @ORM\Entity
 * @ORM\Table(name="m_user")
 * @ORM\Entity(repositoryClass="Muzich\CoreBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
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
   * @Gedmo\Slug(fields={"username"})
   * @ORM\Column(length=128, unique=true)
   */
  protected $slug;
  
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
  
  public function __toString()
  {
    return $this->getName();
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
  
  public function getSlug()
  {
    return $this->slug;
  }
  
  public function setSlug($slug)
  {
    $this->slug = $slug;
  }
  
  /*
   * 
   * 
   */
  
  public function getName()
  {
    return $this->getUsername();
  }
  
//  /**
//  * @ORM\prePersist
//  */
//  public function setSlug()
//  {
//    if (!$this->slug)
//    {
//      
//    }
//  }
//  
  /**
   * Retourn si l'user_id transmis fait partis des enregistrements
   * followed de l'objet.
   * 
   * @param int $user_id 
   * @return boolean
   */
  public function isFollowingUser($user_id)
  {
    foreach ($this->followeds_users as $followed_user)
    {
      if ($followed_user->getFollowed()->getId() == $user_id)
      {
        return true;
      }
    }
    return false;
  }
  
  /**
   * Retourn si l'user_id transmis est l'un des User suivis
   * 
   * @param Symfony\Bundle\DoctrineBundle\Registry doctrine
   * @param int $user_id 
   * @return boolean
   */
  public function isFollowingUserByQuery($doctrine, $user_id)
  {
    return $doctrine
      ->getRepository('MuzichCoreBundle:User')
      ->isFollowingUser($this->getId(), $user_id)
    ;
  }
  
  /**
   * Retourn si l'group_id transmis est l'un des groupe suivis
   * 
   * @param Symfony\Bundle\DoctrineBundle\Registry doctrine
   * @param int $user_id 
   * @return boolean
   */
  public function isFollowingGroupByQuery($doctrine, $group_id)
  {
    return $doctrine
      ->getRepository('MuzichCoreBundle:User')
      ->isFollowingGroup($this->getId(), $group_id)
    ;
  }
  
  public function getPersonalHash()
  {
    return hash('sha256', $this->getSalt().$this->getUsername());
  }
  
  /**
   * Ajoute a l'user les tags transmis (id) comme favoris.
   * 
   * @param EntityManager $em 
   * @param array $ids 
   */
  public function updateTagsFavoritesById(EntityManager $em, $ids)
  {
    $ids_to_add = $ids;
    
    // Pour chacun des tags favoris 
    foreach ($this->tags_favorites as $ii => $tag_favorite)
    {
      $trouve = false;
      foreach ($ids as $i => $id)
      {
        if ($id == $tag_favorite->getTag()->getId())
        {
          $trouve = true;
          // Si le tag était favoris déjà avant (et aussi maintenant)
          // il ne sera ni a ajouter, ni a supprimer.
          unset($ids_to_add[$i]);
        }
      }
      
      if (!$trouve)
      {
        // Si cet ancien tag n'est plus dans la liste, il faut le supprimer
        // (rappel: on supprime ici la relation, pas le tag)
        $em->remove($tag_favorite);
      }
    }
    
    if (count($ids_to_add))
    {
      $tag_favorite_position_max = $this->getTagFavoritePositionMax();
      $tags = $em->getRepository('MuzichCoreBundle:Tag')->findByIds($ids_to_add)->execute();

      // Pour les nouveaux ids restants
      foreach ($tags as $tag)
      {      
        $tag_favorite = new UsersTagsFavorites();
        $tag_favorite->setUser($this);
        $tag_favorite->setTag($tag);
        $tag_favorite->setPosition($tag_favorite_position_max);
        $tag_favorite_position_max++;

        $this->addUsersTagsFavorites($tag_favorite);
        $em->persist($tag_favorite);
      }
    }
    
    $em->flush();
  }
  
  /**
   * Retourne un tableau contenant les ids des tags préférés de l'user
   * 
   * @return type array
   */
  public function getTagFavoriteIds()
  {
    $ids = array();
    foreach ($this->tags_favorites as $tag_favorite)
    {
      $ids[$tag_favorite->getTag()->getId()] = $tag_favorite->getTag()->getId();
    }
    return $ids;
  }
  
  /**
   * Retourne la position max des tag favoris.
   * 
   * @return int 
   */
  public function getTagFavoritePositionMax()
  {
    $max = 0;
    foreach ($this->tags_favorites as $tag_favorite)
    {
      if ($tag_favorite->getPosition() > $max)
      {
        $max = $tag_favorite->getPosition();
      }
    }
    return $max;
  }
  
}