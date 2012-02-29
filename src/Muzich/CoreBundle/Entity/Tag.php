<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Cet entité représente le Tag.
 * 
 * @ORM\Entity
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="Muzich\CoreBundle\Repository\TagRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Tag
{
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;
  
  /**
   * Cet attribu stocke la liste des élèments liés a ce tag.
   * 
   * @ORM\ManyToMany(targetEntity="Element", mappedBy="tags")
   */
  protected $elements;
  
  /**
   * Cet attribu stocke les enregistrements UsersTagsFavorites liés
   * a ce Tag dans le cadre des Tags favoris de l'user.
   * 
   * @ORM\OneToMany(targetEntity="UsersTagsFavorites", mappedBy="tag")
   */
  protected $users_favorites;
  
  /**
   * Cet attribu stocke les enregistrements GroupsTagsFavorites liés
   * a ce Tag dans le cadre des Tags favoris du groupe.
   * 
   * @ORM\OneToMany(targetEntity="GroupsTagsFavorites", mappedBy="tag")
   */
  protected $groups_favorites;
  
  /**
   * Nom du tag
   * 
   * @ORM\Column(type="string", length=64, unique=true)
   * @var type string
   */
  protected $name;
  
  /**
   * @Gedmo\Slug(fields={"name"})
   * @ORM\Column(length=64, nullable=true)
   */
  protected $slug;
  
  /**
   * Compteur total d'utilisation. Utilisé pour faire ressortir les 
   * tags les plus utilisés.
   * 
   * @ORM\Column(type="integer")
   * @var int
   */
  protected $count = 0;
  
  /**
   * Booléen permettant de savoir si le tag est à modérer
   * 
   * @ORM\Column(type="boolean", nullable=true)
   * @var type string
   */
  protected $tomoderate = false;
  
  /**
   * Lorsque le tag est a modérer on stocke ici les ids d'utilisateurs (json)
   * qui ont voulu l'utiliser. Afin qu'il n'y est que eux a le voir.
   * 
   * @ORM\Column(type="text", nullable=true)
   * @var type string
   */
  protected $privateids;
  
  /**
   * Lorsque le tag est a modérer on stocke ici les argumentations pour 
   * l'ajout du tag.
   * 
   * @ORM\Column(type="text", nullable=true)
   * @var type string
   */
  protected $arguments;
  
  /**
   * 
   */
  public function __construct()
  {
    $this->users_favorites = new ArrayCollection();
    $this->elements = new ArrayCollection();
  }
    
  public function __toString()
  {
      return $this->name;
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
  
  public function getSlug()
  {
    return $this->slug;
  }
  
  public function setSlug($slug)
  {
    $this->slug = $slug;
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
   * Add users_favorites
   *
   * @param UsersTagsFavorites $usersFavorites
   */
  public function addUsersTagsFavorites(UsersTagsFavorites $usersFavorites)
  {
    $this->users_favorites[] = $usersFavorites;
  }

  /**
   * Get users_favorites
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getUsersFavorites()
  {
    return $this->users_favorites;
  }

  /**
   * Add groups_favorites
   *
   * @param GroupsTagsFavorites $groupsFavorites
   */
  public function addGroupsTagsFavorites(GroupsTagsFavorites $groupsFavorites)
  {
    $this->groups_favorites[] = $groupsFavorites;
  }

  /**
   * Get groups_favorites
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getGroupsFavorites()
  {
    return $this->groups_favorites;
  }
  
  public function setTomoderate($tomoderate)
  {
    $this->tomoderate = $tomoderate;
  }
  
  public function getTomoderate()
  {
    return $this->tomoderate;
  }
  
  public function setPrivateids($privateids)
  {
    $this->privateids = $privateids;
  }
  
  public function getPrivateids()
  {
    return $this->privateids;
  }
  
  public function getArguments()
  {
    return $this->arguments;
  }
  
  public function setArguments($arguments)
  {
    $this->arguments = $arguments;
  }
  
}