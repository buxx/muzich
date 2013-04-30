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
use Muzich\CoreBundle\Entity\ElementTagsProposition;
use Muzich\CoreBundle\Entity\Tag;

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
   * Data ordre des tags de sa page favoris
   * @var string 
   */
  const DATA_TAGS_ORDER_PAGE_FAV = "data_tags_order_page_fav";
  
  /**
   * Data ordre des tags de ses diffusions
   * @var string 
   */
  const DATA_TAGS_ORDER_DIFF     = "data_tags_order_diff";
  
  /**
   * Data, les favoris ont ils été modifiés
   * @var string 
   */
  const DATA_FAV_UPDATED         = "data_fav_updated";
  
  /**
   * Data, les favoris ont ils été modifiés
   * @var string 
   */
  const DATA_DIFF_UPDATED        = "data_diff_updated";
  
  const HELP_TOUR_HOME = "home";
  
 /**
  * @ORM\Id
  * @ORM\Column(type="integer")
  * @ORM\GeneratedValue(strategy="AUTO")
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
   * Liste des propositions de tags effectués par cet utilisateur
   * 
   * @ORM\OneToMany(targetEntity="ElementTagsProposition", mappedBy="user")
   */
  protected $element_tags_propositions;
  
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
   * @ORM\Column(type="integer", nullable=true)
   * @var int 
   */
  protected $bad_count;
  
  /**
   * Compteur de signalements inutiles
   * 
   * @ORM\Column(type="integer", nullable=true)
   * @var int 
   */
  protected $bad_report_count;
  
  /**
   * Compteur de contenus refusés par la modération
   * 
   * @ORM\Column(type="integer", nullable=true)
   * @var int 
   */
  protected $moderated_element_count;
  
  /**
   * Compteur de contenus refusés par la modération
   * 
   * @ORM\Column(type="integer", nullable=true)
   * @var int 
   */
  protected $moderated_tag_count;
  
  /**
   * Compteur de contenus refusés par la modération
   * 
   * @ORM\Column(type="integer", nullable=true)
   * @var int 
   */
  protected $moderated_comment_count;

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
   * Contient des données pratique, comme l'ordre des tags de sa page favoris etc.
   * 
   * @ORM\Column(type="text", nullable=true)
   * @var type string
   */
  protected $datas = null;
  
  /**
   * Tableau contenant les id => name des tags favoris
   * de l'user. Ces donnée sont faites pour optimiser les calculs.
   * Ce chamsp est mis ajour a chaque fois qu'un UsersTagsFavorite est manipulé.
   * 
   * @ORM\Column(type="text", unique=false, nullable=true)
   * @var array 
   */
  private $tags_favorites_quick;
  
  /**
   * @Assert\Image(maxSize="6000000")
   */
  public $avatar;
    
  /**
   * @ORM\Column(type="text", length=255, nullable=true)
   */
  public $avatar_path;
  
  /**
   * @ORM\Column(type="text", unique=false, nullable=true)
   * @var array 
   */
  private $help_tour;
  
  /**
   * @ORM\Column(type="boolean")
   * @Assert\NotBlank()
   * @var type boolean
   */
  public $cgu_accepted = false;
  
  /**
   * @ORM\Column(type="boolean")
   * @var type boolean
   */
  public $mail_newsletter = true;
  
  /**
   * @ORM\Column(type="boolean")
   * @var type boolean
   */
  public $mail_partner = true;
  
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
    $this->help_tour = json_encode(array(
      self::HELP_TOUR_HOME => true
    ));
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
   * Add elements
   *
   * @param Element $elements
   */
  public function addElementTagsProposition(ElementTagsProposition $proposition)
  {
    $this->element_tags_propositions[] = $proposition;
  }

  /**
   * Get elements
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getElementTagsPropositions()
  {
    return $this->element_tags_propositions;
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
    $this->updateBadCount();
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
  
  public function getModeratedElementCount()
  {
    if ($this->moderated_element_count === null)
    {
      return 0;
    }
    return $this->moderated_element_count;
  }
  
  public function setModeratedElementCount($count)
  {
    $this->moderated_element_count = $count;
    $this->updateBadCount();
  }
  
  public function addModeratedElementCount()
  {
    $this->setModeratedElementCount($this->getModeratedElementCount()+1);
  }
  
  public function getModeratedTagCount()
  {
    if ($this->moderated_tag_count === null)
    {
      return 0;
    }
    return $this->moderated_tag_count;
  }
  
  public function setModeratedTagCount($count)
  {
    $this->moderated_tag_count = $count;
    $this->updateBadCount();
  }
  
  public function addModeratedTagCount()
  {
    $this->setModeratedTagCount($this->getModeratedTagCount()+1);
  }
  
  public function getModeratedCommentCount()
  {
    if ($this->moderated_comment_count === null)
    {
      return 0;
    }
    return $this->moderated_comment_count;
  }
  
  public function setModeratedCommentCount($count)
  {
    $this->moderated_comment_count = $count;
    $this->updateBadCount();
  }
  
  public function addModeratedCommentCount()
  {
    $this->setModeratedCommentCount($this->getModeratedCommentCount()+1);
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
//  * @ORM\PrePersist
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
   * @param Doctrine\Bundle\DoctrineBundle\Registry doctrine
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
   * @param Doctrine\Bundle\DoctrineBundle\Registry doctrine
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
  
  public function getPersonalHash($salt_context = null)
  {
    return hash('sha256', $this->getSalt().$this->getUsername().$salt_context);
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
  
  public function getTagsFavoritesQuick()
  {
    if ($this->tags_favorites_quick == null)
    {
      return array();
    }
    
    return json_decode($this->tags_favorites_quick, true);
  }
  
  /**
   * 
   * @param array $tags_favorites_quick (id => name)
   */
  public function setTagsFavoritesQuick($tags_favorites_quick)
  {
    $this->tags_favorites_quick = json_encode($tags_favorites_quick);
  }
  
  /**
   * 
   * @param \Muzich\CoreBundle\Entity\Tag $tag
   */
  public function addTagFavoriteQuick(Tag $tag)
  {
    $tags_favorites_quick = $this->getTagsFavoritesQuick();
    if (!array_key_exists($tag->getId(), $tags_favorites_quick))
    {
      $tags_favorites_quick[$tag->getId()] = $tag->getName();
    }
    $this->setTagsFavoritesQuick($tags_favorites_quick);
  }
  
  /**
   * 
   * @param \Muzich\CoreBundle\Entity\Tag $tag
   */
  public function removeTagFavoriteQuick(Tag $tag)
  {
    $tags_favorites_quick = $this->getTagsFavoritesQuick();
    if (array_key_exists($tag->getId(), $tags_favorites_quick))
    {
      unset($tags_favorites_quick[$tag->getId()]);
    }
    $this->setTagsFavoritesQuick($tags_favorites_quick);
  }
  
  /**
   * Retourne vrai si le tag_id transmis fait partis des tags favoris de 
   * l'utilisateur
   * 
   * @param int $tag_id
   * @return boolean
   */
  public function haveTagsFavorite($tag_id)
  {
    $tags_favorites_quick = $this->getTagsFavoritesQuick();
    if (array_key_exists($tag_id, $tags_favorites_quick))
    {
      return true;
    }
    
    return false;
  }
  
  /**
   *
   * @return type array
   */
  public function getDatas()
  {
    if ($this->datas === null)
    {
      return array();
    }
    return json_decode($this->datas, true);
  }
  
  /**
   *
   * @param string $data_id
   * @param ~ $default
   * @return all 
   */
  public function getData($data_id, $default)
  {
    $datas = $this->getDatas();
    if (array_key_exists($data_id, $datas))
    {
      return $datas[$data_id];
    }
    
    return $default;
  }
  
  
  /**
   *
   * @param array $datas 
   */
  public function setDatas($datas)
  {
    $this->datas = json_encode($datas);
  }
  
  /**
   *
   * @param string $data_id
   * @param all $data_value 
   */
  public function setData($data_id, $data_value)
  {
    $datas = $this->getDatas();
    $datas[$data_id] = $data_value;
    $this->setDatas($datas);
  }
  
  public function getAvatarAbsolutePath()
  {
    return null === $this->avatar_path
      ? null
      : $this->getAvatarUploadRootDir().'/'.$this->avatar_path;
  }

  public function getAvatarWebPath()
  {
    return null === $this->avatar_path
      ? null
      : $this->getAvatarUploadDir().'/'.$this->avatar_path;
  }

  protected function getAvatarUploadRootDir()
  {
    return __DIR__.'/../../../../web/'.$this->getAvatarUploadDir();
  }

  protected function getAvatarUploadDir()
  {
    return 'files/avatars';
  }
    
   /**
    * @ORM\PrePersist()
    * @ORM\PreUpdate()
    */
   public function preUploadAvatar()
   {
      if (null !== $this->avatar) {
         $this->avatar_path = $this->getPersonalHash($this->avatar->getClientOriginalName()).'.'.$this->avatar->guessExtension();
      }
   }
   
  /**
   * @ORM\PostPersist()
   * @ORM\PostUpdate()
   */
  public function uploadAvatar()
  {
    if (null === $this->avatar) {
      return;
    }
    
    $this->avatar->move($this->getAvatarUploadRootDir(), $this->avatar_path);
    $this->avatar = null;
  }
  
  /**
   * @ORM\PostRemove()
   */
  public function removeUpload()
  {
    if ($file = $this->getAvatarAbsolutePath()) {
      unlink($file);
    }
  }
  
  public function getCguAccepted()
  {
    return $this->cgu_accepted;
  }
  
  public function setCguAccepted($accepted)
  {
    if ($accepted)
      $this->cgu_accepted = true;
    else
      $this->cgu_accepted = false;
  }
  
  public function getMailNewsletter()
  {
    return $this->mail_newsletter;
  }
  
  public function getMailPartner()
  {
    return $this->mail_partner;
  }
  
  public function getHelpTour()
  {
    return json_decode($this->help_tour, true);
}
  
  public function setHelpTour($help_tour)
  {
    $this->help_tour = json_encode($help_tour);
  }
  
  public function wantSeeHelp($help_id)
  {
    $help_tour_status = $this->getHelpTour();
    if (array_key_exists($help_id, $help_tour_status))
    {
      return $help_tour_status[$help_id];
    }
    return false;
  }
  
  public function setSeeHelp($help_id, $boolean)
  {
    $help_tour_status = $this->getHelpTour();
    if (array_key_exists($help_id, $help_tour_status))
    {
      $help_tour_status[$help_id] = ($boolean)?true:false;
    }
    $this->setHelpTour($help_tour_status);
  }
  
  public function getBadCount()
  {
    if (is_null($this->bad_count))
    {
      return 0;
    }
    
    return $this->bad_count;
  }
  
  public function updateBadCount()
  {
    $this->bad_count = $this->getBadReportCount()
      + $this->getModeratedCommentCount()
      + $this->getModeratedElementCount()
      + $this->getModeratedTagCount()
    ;
  }
  
}