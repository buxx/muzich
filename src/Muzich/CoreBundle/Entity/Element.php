<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints as Assert;
use Muzich\CoreBundle\Validator as MuzichAssert;
use Muzich\CoreBundle\Entity\Tag;
use Muzich\CoreBundle\Managers\CommentsManager;

/**
 * L'Element est l'Element central de l'application. C'est cet
 * entité qui stocke le media partagé sur le réseau.
 * 
 * @ORM\Entity
 * @ORM\Table(name="element")
 * @ORM\Entity(repositoryClass="Muzich\CoreBundle\Repository\ElementRepository")
 * 
 */
class Element
{
  
  /**
   * Identifiant de l'objet externe
   * @var string 
   */
  const DATA_REF_ID         = "data_ref_id";
  
  /**
   * Identifiant de l'objet externe
   * @var string 
   */
  const DATA_NORMALIZED_URL = "data_normalized_url";
  /**
   * Adresse HTTP(S) de la jaquette
   * @var string 
   */
  const DATA_THUMB_URL      = "data_thumb_url";
  /**
   * Titre de l'objet externe
   * @var string 
   */
  const DATA_TITLE          = "data_title";
  /**
   * Nom de l'artiste
   * @var string 
   */
  const DATA_ARTIST         = "data_artist";
  /**
   * Tags de l'objets externe array("tag1", "tag2", [...])
   * @var string 
   */
  const DATA_TAGS           = "data_tags";
  /**
   * Type de l'objet track|album|
   * @var string 
   */
  const DATA_TYPE           = "data_type";
  /**
   * Contenu téléchargeable ?
   * @var string 
   */
  const DATA_DOWNLOAD       = "data_download";
  /**
   * Adresse du contenu téléchargeable
   * @var string 
   */
  const DATA_DOWNLOAD_URL   = "data_download_url";
  /**
   * Don possible ?
   * @var string 
   */
  const DATA_GIFT           = "data_gift";
  /**
   * Adresse pour effectuer un don
   * @var string 
   */
  const DATA_GIFT_URL       = "data_gift_url";
  
  const DATA_PLAYLIST_AUTHOR = "data_playlist_author";
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;

  /**
   * Cet attribut stocke le type d'élément.
   * 
   * @ORM\Column(type="string", length=64)
   * @Assert\NotBlank()
   * @Assert\Length(max = 255)
   */
  protected $type;
  
  /**
   * Cet attribut stocke la liste des tags liés a cet élément.
   * 
   * @ORM\ManyToMany(targetEntity="Tag", inversedBy="elements")
   * @ORM\JoinTable(name="elements_tag")
   */
  private $tags;

  /**
   * Propriétaire de l'élément
   * 
   * @ORM\ManyToOne(targetEntity="User", inversedBy="elements")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  protected $owner;

  /**
   * Objet parent (si a été repartagé)
   * 
   * @ORM\ManyToOne(targetEntity="Element", inversedBy="childs")
   * @ORM\JoinColumn(name="element_parent_id", referencedColumnName="id")
   */
  protected $parent;
  
  
  /**
   * Liste des Elements qui on reshare cet element
   * 
   * @ORM\OneToMany(targetEntity="Element", mappedBy="parent")
   */
  protected $childs;

  /**
   * Groupe de l'élément
   * 
   * @ORM\ManyToOne(targetEntity="Group", inversedBy="elements")
   * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
   * @MuzichAssert\GroupOwnedOrPublic()
   */
  protected $group = null;
  
  /**
   * Cet attribu stocke les enregistrements UsersElementsFavorites liés
   * a ce Tag dans le cadre des Elements favoris.
   * 
   * @ORM\OneToMany(targetEntity="UsersElementsFavorites", mappedBy="element")
   */
  protected $elements_favorites;
  
  /**
   * Propositions de tags
   * 
   * @ORM\OneToMany(targetEntity="ElementTagsProposition", mappedBy="element")
   */
  protected $tags_propositions;
  
  /**
   * Permet de savoir sans faire de gros calculs en base si il y a des 
   * propositions de tags en cours sur cet élément.
   * 
   * @ORM\Column(type="boolean", nullable = true)
   * @var type string
   */
  protected $has_tags_proposition = false;
  
  /**
   * L'url est l'url du media. 
   * 
   * @ORM\Column(type="string", length=1024)
   * @Assert\NotBlank(message = "error.element.url.notblank")
   * @Assert\Length(max = 1024, maxMessage="error.element.url.tolong|error.element.url.tolong")
   * @Assert\Url(message = "error.element.url.invalid")
   * @var type string
   */
  protected $url;
  
  /**
   * Libellé du media
   * 
   * @ORM\Column(type = "string", length = 128)
   * @Assert\NotBlank(message = "error.element.name.notblank")
   * @Assert\Length(min = 3, max = 84, minMessage = "error.element.name.toshort", maxMessage = "error.element.name.tolong")
   * @var type string
   */
  protected $name;
  
  /**
   * Code d'embed
   * 
   * @ORM\Column(type="text", nullable=true)
   * @var type string
   */
  protected $embed;
  
  /**
   * @var datetime $created
   *
   * @Gedmo\Timestampable(on="create")
   * @ORM\Column(type="datetime")
   */
  private $created;

  /**
   * @var datetime $updated
   *
   * @ORM\Column(type="datetime")
   * @Gedmo\Timestampable(on="update")
   */
  private $updated;
  
  /**
   * @var string $thumbnail_url
   *
   * @ORM\Column(type="string", length=512, nullable=true)
   */
  protected $thumbnail_url;
  
  /**
   * Commentaires stocké au format json
   * 
   * array(
   *   array(
   *     "u" => array(              // Des infos sur l'utilisateur auteur du commentaire
   *       "i" => "IdDuUser",       // l'id
   *       "s" => "LeSlugDuUser",   // le slug
   *       "n" => "NameDuUser"      // le name
   *     ),
   *     "d" => "LaDate",        // Date au format Y-m-d H:i:s
   *     "c" => "Comment"           // Le commentaire
   *   ),
   *   [...]
   * );
   * 
   * @ORM\Column(type="text", nullable=true)
   * @var type string
   */
  protected $comments;
  
  /**
   * Compteur de signalements
   * 
   * @ORM\Column(type="integer", nullable=true)
   * @var int 
   */
  protected $count_report;
  
  /**
   * @ORM\Column(type="boolean", nullable=false)
   * @var int 
   */
  protected $reported = false;
  
  /**
   * array json des id users ayant reporté l'élément
   * 
   * @ORM\Column(type="text", nullable=true)
   * @var string 
   */
  protected $report_ids;
  
  /**
   * array json des id users ayant voté +1
   * 
   * @ORM\Column(type="text", nullable=true)
   * @var string 
   */
  protected $vote_good_ids;
  
  /**
   * Compteur de points
   * 
   * @ORM\Column(type="integer", nullable=true)
   * @var int 
   */
  protected $points;
  
  /**
   * Booléen permettant de savoir si un des commentaires de cet élément
   * a été signalé a la modération.
   * 
   * @ORM\Column(type="integer", nullable=true)
   * @var int
   */
  protected $count_comment_report = false;

  /**
   * Données de l'objet chez le service externe
   * 
   * @ORM\Column(type="text", nullable=true)
   * @var type string
   */
  protected $datas;
  
  
  /**
   *
   * @ORM\Column(type="boolean", nullable=true)
   * @var boolean 
   */
  protected $need_tags = false;
  
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
   * Set url
   *
   * @param string $url
   */
  public function setUrl($url)
  {
    $this->url = $url;
  }

  /**
   * Get url
   *
   * @return string 
   */
  public function getUrl()
  {
    return $this->url;
  }

  /**
   * Set name
   *
   * @param string $name
   */
  public function setName($name)
  {
    $this->name = $name;
  }

  /**
   * Get name
   *
   * @return string 
   */
  public function getName()
  {
    return $this->name;
  }
  
  /**
   * Set type
   *
   * @param string $type
   */
  public function setType($type)
  {
    $this->type = $type;
  }

  /**
   * Get type
   *
   * @return string 
   */
  public function getType()
  {
    return $this->type;
  }
  
  
  public function __construct($url = null)
  {
    //$this->tags = new ArrayCollection();
    $this->url = $url;
  }
  
  public function __toString()
  {
      return $this->name;
  }
  
  /**
   * Add tags
   *
   * @param Tag $tags
   */
  public function addTag(Tag $tags)
  {
    $this->tags[] = $tags;
  }

  /**
   * Get tags
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getTags()
  {
    return $this->tags;
  }
  
  public function getTagsIdsJson()
  {
    $ids = array();
    if (count($this->getTags()))
    {
      foreach ($this->getTags() as $tag)
      {
        $ids[] = $tag->getId();
      }
    }
    return json_encode($ids);
  }
  
  public function setTags($tags)
  {
    $this->tags = $tags;
  }
  
  /**
   *
   * @param Collection|Array $tags 
   */
  public function addTags($tags)
  {
    foreach ($tags as $tag)
    {
      $this->addTag($tag);
    }
  }

  /**
   * Set owner
   *
   * @param User $owner
   */
  public function setOwner(User $owner)
  {
      $this->owner = $owner;
  }

  /**
   * Get owner
   *
   * @return User 
   */
  public function getOwner()
  {
      return $this->owner;
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
   * Set group
   *
   * @param Group $group
   */
  public function setGroup($group)
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
   * Set embed
   *
   * @param string $code
   */
  public function setEmbed($code)
  {
      $this->embed = $code;
  }

  /**
   * Get embed
   *
   * @return string 
   */
  public function getEmbed()
  {
      return $this->embed;
  }

  /**
   * Set created
   *
   * @param date $created
   */
  public function setCreated($created)
  {
      $this->created = $created;
  }

  /**
   * Get created
   *
   * @return date 
   */
  public function getCreated()
  {
      return $this->created;
  }

  /**
   * Set updated
   *
   * @param datetime $updated
   */
  public function setUpdated($updated)
  {
      $this->updated = $updated;
  }

  /**
   * Get updated
   *
   * @return datetime 
   */
  public function getUpdated()
  {
      return $this->updated;
  }

  /**
   * Set thumbnail url
   *
   * @param string $thumbnail_url
   */
  public function setThumbnailUrl($thumbnail_url)
  {
      $this->thumbnail_url = $thumbnail_url;
  }

  /**
   * Get thumbnail url
   *
   * @return datetime 
   */
  public function getThumbnailUrl()
  {
      return $this->thumbnail_url;
  }
  
  /**
   *
   * @return type array
   */
  public function getComments()
  {
    return json_decode($this->comments, true);
  }
  
  public function getCountReport()
  {
    return $this->count_report;
  }
  
  public function setCountReport($count)
  {
    $this->count_report = $count;
    if ($count)
    {
      $this->setReported(true);
    }
    else
    {
      $this->setReported(false);
    }
  }
  
  public function setReported($reported)
  {
    $this->reported = $reported;
  }
  
  public function getReported()
  {
    return $this->reported;
  }
  
  public function getReportIds()
  {
    return json_decode($this->report_ids, true);
  }
  
  public function setReportIds($report_ids)
  {
    $this->report_ids = json_encode($report_ids);
  }
  
  /**
   *
   * @param array $comments 
   */
  public function setComments($comments)
  {
    $this->comments = json_encode($comments);
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
   * @return all 
   */
  public function getData($data_id)
  {
    $datas = $this->getDatas();
    if (array_key_exists($data_id, $datas))
    {
      return $datas[$data_id];
    }
    
    return null;
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
  
  public function setHasTagProposition($has_prop)
  {
    $this->has_tags_proposition = $has_prop;
  }
  
  public function hasTagProposition()
  {
    if ($this->has_tags_proposition === null)
    {
      return false;
    }
    
    return $this->has_tags_proposition;
  }
  
  public function getTagsProposition()
  {
    return $this->tags_propositions;
  }
  
  public function setTagsPRopositions($propositions)
  {
    $this->tags_propositions = $propositions;
  }
  
  public function getCountCommentReport()
  {
    return $this->count_comment_report;
  }
  
  public function setCountCommentReport($count)
  {
    $this->count_comment_report = $count;
  }
  
  /**
   * Etablie des relation vers des tags.
   * (Supprime les anciens tags, au niveau de l'objet seulement)
   * 
   * @param array $ids 
   */
  public function setTagsWithIds(EntityManager $em, $ids)
  {
    $this->tags = null;
    if (count($ids))
    {
      $tags = $em->getRepository('MuzichCoreBundle:Tag')->findByIds($ids)->execute();
      // Pour les nouveaux ids restants
      foreach ($tags as $tag)
      {      
        $this->addTag($tag);
      }
    }
  }
  
//  /**
//   * Retourne le nombre de fois que cet élément a été msi en favoris 
//   * 
//   * @return int
//   */
//  public function getCountFavorite()
//  {
//    return count($this->elements_favorites);
//  }
  
  public function hasFavoriteUser($user_id)
  {
    if (count($this->elements_favorites))
    {
      foreach ($this->elements_favorites as $favorite)
      {
        if ($favorite->getUser()->getId() == $user_id)
        {
          return true;
        }
      }
    }
    
    return false;
  }
  
  public function setGroupToId()
  {
    $this->group = $this->group->getId();
  }
  
//  public function deleteTag(Tag $tag)
//  {
//    $this->tags->removeElement($tag);
//  }
  
  /**
   * Répond vrai si le tag transmis fait partis des tags de l'élément
   * 
   * @param Tag $tag_t
   * @return boolean 
   */
  public function hasTag(Tag $tag_t)
  {
    foreach ($this->getTags() as $tag)
    {
      if ($tag_t->getId() == $tag->getId())
      {
        return true;
      }
    }
    return false;
  }
  
  public function getPoints()
  {
    if ($this->points === null)
    {
      return '0';
    }
    
    return $this->points;
  }
  
  public function setPoints($points)
  {
    $this->points = $points;
  }
  
  public function getVoteGoodIds()
  {
    return json_decode($this->vote_good_ids, true);
  }
  
  public function setVoteGoodIds($votes_ids)
  {
    $this->vote_good_ids = json_encode($votes_ids);
  }
  
  /**
   * ajoute le vote de l'user_id aux votes good
   * 
   * @param int $user_id 
   */
  public function addVoteGood($user_id)
  {
    $votes = $this->getVoteGoodIds();
    if (!count($votes))
    {
      $votes = array();
    }
    
    if (!$this->hasVoteGood($user_id))
    {
      $votes[] = (string)$user_id;
      $this->setPoints(count($votes));
    }
    $this->setVoteGoodIds($votes);
  }
  
  /**
   * Retire le vote_good de l'user_id
   * 
   * @param int $user_id 
   */
  public function removeVoteGood($user_id)
  {
    if (count($votes = $this->getVoteGoodIds()))
    {
      $votes_n = array();
      foreach ($votes as $id)
      {
        if ($id != $user_id)
        {
          $votes_n[] = (string)$id;
        }
      }
      
      $this->setPoints(count($votes_n));
      $this->setVoteGoodIds($votes_n);
    }
  }
  
  /**
   * Répond vrai si l'user_id a déjà voté good.
   * 
   * @param int $user_id
   * @return boolean 
   */
  public function hasVoteGood($user_id)
  {
    if (count($votes = $this->getVoteGoodIds()))
    {
      foreach ($votes as $id)
      {
        if ($id == $user_id)
        {
          return true;
        }
      }
    }
    return false;
  }
  
  /**
   * Retourne vrai si l'utilisateur a demandé qa suivre les commentaires
   *  
   * @param int $user_id identifiant de l'utilisateur
   * @return boolean 
   */
  public function userFollowComments($user_id)
  {
    $cm = new CommentsManager($this->getComments());
    return $cm->userFollow($user_id);
  }
  
  /**
   * 
   * @param Element $element 
   */
  public function setParent(Element $element = null)
  {
    $this->parent = $element;
  }
  
  /**
   *
   * @return Element 
   */
  public function getParent()
  {
    return $this->parent;
  }
  
  public function setChilds($elements)
  {
    $this->childs = $elements;
  }
  
  public function getChilds()
  {
    return $this->childs;
  }
  
  /**
   *
   * @param boolean $need 
   */
  public function setNeedTags($need)
  {
    if ($need)
    {
      $this->need_tags = true;
    }
    else
    {
      $this->need_tags = false;
    }
  }
  
  /**
   *
   * @return boolean 
   */
  public function getNeedTags()
  {
    if ($this->need_tags)
    {
      return true;
    }
    return false;
  }
  
  public function getProposedName()
  {
    if (($title = $this->getData(self::DATA_TITLE)))
    {
      if (($artist = $this->getData(self::DATA_ARTIST)))
      {
        $artist = ' - '.$artist;
      }
      return $title.$artist;
    }
    return null;
  }
  
  /**
   *
   * @return type 
   */
  public function getProposedTags()
  {
    if (count($tags = $this->getData(self::DATA_TAGS)))
    {
      return $tags;
    }
    return array();
  }
  
  public function getRefId($api_context = false)
  {
    if ($api_context && $this->getType() == 'soundcloud.com')
    {
      return $this->getData(self::DATA_NORMALIZED_URL);
    }
    
    return $this->getData(self::DATA_REF_ID);
  }
  
  public function hasTagsProposition()
  {
    if (count($this->tags_propositions))
    {
      return true;
    }
    return false;
  }
  
  public function getHasTagsProposition()
  {
    return $this->hasTagsProposition();
  }
  
}