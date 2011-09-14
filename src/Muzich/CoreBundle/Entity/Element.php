<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;


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
   */
  protected $group = null;
  
  /**
   * Cet attribu stocke les enregistrements UsersElementsFavorites liés
   * a ce Tag dans le cadre des Elements favoris.
   * 
   * @ORM\OneToMany(targetEntity="UsersElementsFavorites", mappedBy="tag")
   */
  protected $elements_favorites;
  
  /**
   * L'url est l'url du media. 
   * 
   * @ORM\Column(type="string", length=1024)
   * @var type string
   */
  protected $url;
  
  /**
   * Libellé du media
   * 
   * @ORM\Column(type="string", length=128)
   * @var type string
   */
  protected $name;
  
  /**
   * Date d'ajout dans le réseau
   * 
   * @ORM\Column(type="datetime")
   * @var type string
   */
  protected $date_added;
  

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
   * Set date_added
   *
   * @param datetime $dateAdded
   */
  public function setDateAdded($dateAdded)
  {
    $this->date_added = $dateAdded;
  }

  /**
   * Get date_added
   *
   * @return datetime 
   */
  public function getDateAdded()
  {
    return $this->date_added;
  }
  
  /**
   * Set type
   *
   * @param ElementType $type
   */
  public function setType(ElementType $type)
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
  
  
  public function __construct()
  {
    $this->tags = new ArrayCollection();
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