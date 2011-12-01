<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints as Assert;
use Muzich\CoreBundle\Validator as MuzichAssert;

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
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;

  /**
   * Cet attribut stocke le type d'élément.
   * 
   * @ORM\ManyToOne(targetEntity="ElementType", inversedBy="elements")
   * @ORM\JoinColumn(name="element_type_id", referencedColumnName="id")
   */
  protected $type;
  
  /**
   * Cet attribut stocke la liste des tags liés a cet élément.
   * 
   * @ORM\ManyToMany(targetEntity="Tag", inversedBy="elements")
   * @ORM\JoinTable(name="elements_tag")
   * @MuzichAssert\Tags()
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
   * L'url est l'url du media. 
   * 
   * @ORM\Column(type="string", length=1024)
   * @Assert\NotBlank()
   * @Assert\MaxLength(1024)
   * @var type string
   */
  protected $url;
  
  /**
   * Libellé du media
   * 
   * @ORM\Column(type="string", length=128)
   * @Assert\NotBlank()
   * @Assert\MinLength(limit=3)
   * @Assert\MaxLength(64)
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
   * @param ElementType $type
   */
  public function setType(ElementType $type = null)
  {
    $this->type = $type;
  }

  /**
   * Get type
   *
   * @return ElementType 
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
  
  public function setTags($tags)
  {
    $this->tags = $tags;
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
   * Etablie des relation vers des tags.
   * (Supprime les anciens tags, au niveau de l'objet seulement)
   * 
   * @param array $ids 
   */
  public function setTagsWithIds(EntityManager $em, $ids)
  {
    $tags = $em->getRepository('MuzichCoreBundle:Tag')->findByIds($ids)->execute();
    $this->tags = null;
    // Pour les nouveaux ids restants
    foreach ($tags as $tag)
    {      
      $this->addTag($tag);
    }
  }
  
  public function getCountFavorite()
  {
    return count($this->elements_favorites);
  }
  
  public function setGroupToId()
  {
    $this->group = $this->group->getId();
  }
  
}