<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\EntityManager;
use Muzich\CoreBundle\Entity\GroupsTagsFavorites;
use Symfony\Component\Validator\Constraints as Assert;
use Muzich\CoreBundle\Validator as MuzichAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Le groupe est une sorte de liste de diffusion, a laquelle les
 * users peuvent s'abonner (follow). Un groupe peut tout aussi bien 
 * être "Les fans de la tek du sud est" qu'un sound système, 
 * un artiste ...
 * 
 * @ORM\Entity
 * @ORM\Table(name="m_group")
 * @ORM\Entity(repositoryClass="Muzich\CoreBundle\Repository\GroupRepository")
 * @UniqueEntity(fields="name", message="error.group.name.unique")
 */
class Group
{
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;
  
  /**
   * Nom du groupe
   * 
   * @ORM\Column(type="string", length=128, unique=true)
   * @Assert\NotBlank()
   * @Assert\MinLength(limit=3)
   * @Assert\MaxLength(64)
   * @var type string
   */
  protected $name;
  
  /**
   * @Gedmo\Slug(fields={"name"})
   * @ORM\Column(length=128, unique=true)
   */
  protected $slug;
  
  /**
   * Description
   * 
   * @ORM\Column(type="text")
   * @Assert\MaxLength(2048)
   * @var type string
   */
  protected $description;
  
  /**
   * Si open est a vrai, cela traduit que les followers peuvent 
   * diffuser leur element en tant qu'élément de ce groupe.
   * 
   * @ORM\Column(type="boolean")
   * @var type string
   */
  protected $open = false;
  
  /**
   * Cet attribut contient les enregistrements FollowGroup lié 
   * a ce Groupe dans le cadre des groupes suivis.
   * 
   * @ORM\OneToMany(targetEntity="FollowGroup", mappedBy="group")
   */
  protected $followers;
  
  /**
   * Propriétaire
   * 
   * @ORM\ManyToOne(targetEntity="User", inversedBy="groups_owned")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  protected $owner;
  
  /**
   * Cet attribut contient les enregistrements GroupsTagsFavorites lié 
   * a ce Groupe dans le cadre des tags de groupe.
   * 
   * @ORM\OneToMany(targetEntity="GroupsTagsFavorites", mappedBy="group")
   */
  protected $tags;
  
  /**
   * Cet attribut contient les enregistrements Element lié 
   * a ce Groupe.
   * 
   * @ORM\OneToMany(targetEntity="Element", mappedBy="group")
   */
  protected $elements;

  /**
   * 
   */
  public function __construct()
  {
    $this->followers = new ArrayCollection();
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
   * Set slug
   *
   * @param string $slug
   */
  public function setSlug($slug)
  {
    $this->slug = $slug;
  }

  /**
   * Get slug
   *
   * @return string 
   */
  public function getSlug()
  {
    return $this->slug;
  }

  /**
   * Set description
   *
   * @param string $description
   */
  public function setDescription($description)
  {
    $this->description = $description;
  }

  /**
   * Get description
   *
   * @return string 
   */
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * Set open
   *
   * @param boolean $open
   */
  public function setOpen($open)
  {
    $this->open = $open;
  }

  /**
   * Get open
   *
   * @return boolean 
   */
  public function getOpen()
  {
    return $this->open;
  }

  /**
   * Add followers
   *
   * @param FollowGroup $followers
   */
  public function addFollowGroup(FollowGroup $followers)
  {
    $this->followers[] = $followers;
  }

  /**
   * Get followers
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getFollowers()
  {
    return $this->followers;
  }

  /**
   * Set owner
   *
   * @param User $owner
   */
  public function setOwner(User $owner = null)
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
   * Add tags
   *
   * @param GroupsTagsFavorites $tags
   */
  public function addGroupsTagsFavorites(GroupsTagsFavorites $tags)
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
   * Add tag
   *
   * @param Tag $tag
   */
  public function addTag(Tag $tag, EntityManager $em)
  {
    $GroupsTagsFavorites = new GroupsTagsFavorites();
    $GroupsTagsFavorites->setTag($tag);
    $GroupsTagsFavorites->setPosition(0);
    $GroupsTagsFavorites->setGroup($this);
    $em->persist($GroupsTagsFavorites);
    $this->tags[] = $GroupsTagsFavorites;
  }
  
  public function setTags($tags)
  {
    $this->tags = $tags;
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
   * Definis les relation vers des tags.
   * 
   * @param array $ids 
   */
  public function setTagsWithIds(EntityManager $em, $ids)
  {
    $tags = $em->getRepository('MuzichCoreBundle:Tag')->findByIds($ids)->execute();

    // Pour les nouveaux ids restants
    foreach ($tags as $tag)
    {
      $this->addTag($tag, $em);
    }
  }
  
  public function setTagsToIds()
  {
    $tags_id = array();
    foreach ($this->getTags() as $tag_r)
    {
      $tags_id[] = $tag_r->getTag()->getId();
    }
    $this->tags = $tags_id;
  }
  
  /**
   * Retourne vrai si l'user_id peut poster dans ce groupe.
   * 
   * @param int $user_id
   * @return boolean 
   */
  public function userCanAddElement($user_id)
  {
    if ($this->open)
    {
      return true;
    }
    
    if ($this->getOwner()->getId() == $user_id)
    {
      return true;
    }
    
    return false;
  }
}