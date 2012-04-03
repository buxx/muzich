<?php

namespace Muzich\CoreBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\EntityManager;
use Muzich\CoreBundle\Entity\UsersTagsFavorites;
use Symfony\Component\Validator\Constraints as Assert;
use Muzich\CoreBundle\Validator as MuzichAssert;

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
   * @ORM\Column(type="text", nullable=true)
   * @ORM\Column(length=256)
   */
  protected $email_requested;
  
 /**
  * @ORM\Column(type="integer", nullable=true)
  */
  protected $email_requested_datetime;
  
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
   * Compteur de signalements inutiles
   * 
   * @ORM\Column(type="integer", nullable=true)
   * @var int 
   */
  protected $bad_report_count;

  /**
   * @ORM\Column(type="text", nullable=true)
   * @ORM\Column(length=256)
   */
  protected $town;

  /**
   * @ORM\Column(type="text", nullable=true)
   * @ORM\Column(length=128)
   */
  protected $country;
  
  /**
   * Reputation
   * 
   * @ORM\Column(type="integer", nullable=true)
   * @var int 
   */
  protected $reputation;
  
  /**
   * Liste des Events appartenant a cet utilisateur.
   * 
   * @ORM\OneToMany(targetEntity="Event", mappedBy="user")
   */
  protected $events;
  
  /**
   * Contient des données pratique pour par exemple influencer l'affichange dans twig.
   * 
   * @var array 
   */
  protected $live_datas = array();
  
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
    $this->groups_owned = new ArrayCollection();
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
  public function getGroupsOwned()
  {
    return $this->groups_owned;
  }

  /**
   * Get groups in array (id => name)
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getGroupsOwnedArray()
  {
    $groups = array();
    foreach ($this->groups_owned as $group)
    {
      $groups[$group->getId()] = $group->getName();
    }
    return $groups;
  }
  
  public function getSlug()
  {
    return $this->slug;
  }
  
  public function setSlug($slug)
  {
    $this->slug = $slug;
  }
  
  public function getEmailRequested()
  {
    return $this->email_requested;
  }
  
  public function setEmailRequested($email_requested)
  {
    $this->email_requested = $email_requested;
  }
  
  public function getBadReportCount()
  {
    return $this->bad_report_count;
  }
  
  public function setBadReportCount($count)
  {
    $this->bad_report_count = $count;
  }
  
  public function addBadReport()
  {
    $this->setBadReportCount($this->getBadReportCount()+1);
  }
  
  public function getTown()
  {
    return $this->town;
  }
  
  public function setTown($town)
  {
    $this->town = $town;
  }
  
  public function getCountry()
  {
    return $this->country;
  }
  
  public function setCountry($country)
  {
    $this->country = $country;
  }
  
  public function setReputation($reputation)
  {
    $this->reputation = $reputation;
  }
  
  public function getReputation()
  {
    if ($this->reputation === null)
    {
      return 0;
    }
    return $this->reputation;
  }
  
  public function getEvents()
  {
    return $this->events;
  }
  
  public function setEvents($events)
  {
    $this->events = $events;
  }
  
  /*
   * 
   * 
   */
  
  public function getName()
  {
    return $this->getUsername();
  }
  
//  public function getLocalisationExploded()
//  {
//    $town = null;
//    $country = null;
//    if ($this->localisation)
//    {
//      if (($explode = explode(', ', $this->localisation)))
//      {
//        $town = $explode[0];
//        $country = $explode[1];
//      }
//    }
//    return array(
//      'town'    => $town,
//      'country' => $country
//    );
//  }
//  
//  public function setLocalisationExploded($town, $country)
//  {
//    $town = str_replace(', ', '', $town);
//    $town = str_replace(',', '', $town);
//    $country = str_replace(', ', '', $country);
//    $country = str_replace(',', '', $country);
//    
//    $this->localisation = $town. ', ' .$country;
//  }
  
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
    $ids = json_decode($ids);
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
      $ids_to_add = array_merge($ids_to_add);
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

  /**
   * Set email_requested_datetime
   *
   * @param integer $emailRequestedDatetime
   */
  public function setEmailRequestedDatetime($emailRequestedDatetime)
  {
    $this->email_requested_datetime = $emailRequestedDatetime;
  }

  /**
   * Get email_requested_datetime
   *
   * @return integer 
   */
  public function getEmailRequestedDatetime()
  {
    return $this->email_requested_datetime;
  }
  
  public function addLiveData($id, $data)
  {
    $this->live_datas[$id] = $data;
  }
  
  public function removeLiveData($id)
  {
    if (array_key_exists($id, $this->live_datas))
    {
      unset($this->live_datas[$id]);
    }
  }
  
  public function hasLiveData($id, $data = null)
  {
    if (array_key_exists($id, $this->live_datas))
    {
      if ($this->live_datas[$id] == $data)
      {
        return true;
      }
    }
    return false;
  }
  
}