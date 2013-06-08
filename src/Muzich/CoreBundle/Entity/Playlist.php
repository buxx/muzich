<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use \Doctrine\Common\Collections\ArrayCollection;
use \Doctrine\Common\Collections\Collection;

use Muzich\CoreBundle\lib\Collection\ElementCollectionManager;
use Muzich\CoreBundle\lib\Collection\TagCollectionManager;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Muzich\CoreBundle\Repository\PlaylistRepository")
 */
class Playlist
{
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;
  
  /**
   * @ORM\Column(type="string", length=128, unique=false)
   * @Assert\NotBlank()
   * @Assert\Length(min = 3, max = 64)
   * @var type string
   */
  protected $name;
  
  /**
   * @ORM\ManyToOne(targetEntity="User", inversedBy="playlists_owneds")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  protected $owner;
  
  /**
   * @ORM\ManyToOne(targetEntity="Playlist", inversedBy="copys")
   * @ORM\JoinColumn(name="copied_id", referencedColumnName="id", onDelete="SET NULL")
   */
  protected $copied;
  
  /**
   * @ORM\OneToMany(targetEntity="Playlist", mappedBy="copied")
   */
  protected $copys;
  
  /**
   * @ORM\Column(type="boolean")
   */
  protected $public = false;
  
  /**
   * @ORM\OneToMany(targetEntity="UserPlaylistPicked", mappedBy="playlist", cascade={"remove"})
   */
  protected $user_playlists_pickeds;
  
  /**
   * @ORM\Column(type="text", unique=false, nullable=true)
   */
  private $tags;
  
  /**
   * @ORM\Column(type="text", unique=false, nullable=true)
   */
  private $elements;
  
  public function __construct()
  {
    $this->user_playlists_pickeds = new ArrayCollection();
    $this->copys = new ArrayCollection();
    $this->tags = json_encode(array());
    $this->elements = json_encode(array());
  }
  
  public function getId()
  {
    return $this->id;
  }
  
  public function getName()
  {
    return $this->name;
  }
  
  public function setName($name)
  {
    $this->name = $name;
  }
  
  /** @return User */
  public function getOwner()
  {
    return $this->owner;
  }
  
  public function setOwner(User $owner = null)
  {
    $this->owner = $owner;
  }
  
  public function isPublic()
  {
    return ($this->public)?true:false;
  }
  
  public function setPublic($public)
  {
    ($public) ? $this->public = true : $this->public = false;
  }
  
  /** @return Collection */
  public function getUserPlaylistsPickeds()
  {
    return $this->user_playlists_pickeds;
  }
  
  public function getPickedsUsers()
  {
    $users = new ArrayCollection();
    foreach ($this->getUserPlaylistsPickeds() as $user_playlist_picked)
    {
      $users->add($user_playlist_picked->getUser());
    }
    return $users;
  }
  
  public function havePickerUser(User $user)
  {
    foreach ($this->getPickedsUsers() as $user_picker)
    {
      if ($user_picker->getId() == $user->getId())
      {
        return true;
      }
    }
    
    return false;
  }
  
  public function setUserPlaylistsPickeds(Collection $user_playlists_pickeds = null)
  {
    $this->user_playlists_pickeds = $user_playlists_pickeds;
  }
  
  public function addPickedUser(User $user)
  {
    $this->addUserPlaylistPicked($user);
  }
  
  public function addUserPlaylistPicked(User $user)
  {
    // TODO
    $user_playlist_picked = new UserPlaylistPicked();
    $user_playlist_picked->setUser($user);
    $user_playlist_picked->setPlaylist($this);
    
    $this->getUserPlaylistsPickeds()->add($user_playlist_picked);
  }
  
  public function removePickedUser(User $user)
  {
    // TODO: à coder
  }
  
  /** @return array */
  public function getTags()
  {
    return json_decode($this->tags, true);
  }
  
  /** @param tags array */
  public function setTags($tags)
  {
    $this->tags = json_encode($tags);
  }
  
  public function addTag(Tag $tag)
  {
    $tags_manager = new TagCollectionManager(json_decode($this->tags, true));
    $tags_manager->add($tag);
    $this->setTags($tags_manager->getContent());
  }
  
  public function removeTag(Tag $tag)
  {
    $tags_manager = new TagCollectionManager(json_decode($this->tags, true));
    $tags_manager->remove($tag);
    $this->setTags($tags_manager->getContent());
  }
  
  public function cleanTags()
  {
    $this->setTags(array());
  }
  
  public function getTagsIds()
  {
    $tags_manager = new TagCollectionManager(json_decode($this->tags, true));
    return $tags_manager->getAttributes(TagCollectionManager::ATTRIBUTE_ID);
  }
  
  /** @return array */
  public function getElements()
  {
    return json_decode($this->elements, true);
  }
  
  /** @param tags array */
  public function setElements($elements)
  {
    $this->elements = json_encode($elements);
  }
  
  public function addElement(Element $element)
  {
    $elements_manager = new ElementCollectionManager(json_decode($this->elements, true));
    $elements_manager->add($element);
    $this->setElements($elements_manager->getContent());
  }
  
  public function removeElement(Element $element)
  {
    $elements_manager = new ElementCollectionManager(json_decode($this->elements, true));
    $elements_manager->remove($element);
    $this->setElements($elements_manager->getContent());
  }
  
  public function removeElementWithId($element_id)
  {
    $elements_manager = new ElementCollectionManager(json_decode($this->elements, true));
    $elements_manager->removeWithReference($element_id);
    $this->setElements($elements_manager->getContent());
  }
  
  public function getElementIndex(Element $element)
  {
    $elements_manager = new ElementCollectionManager(json_decode($this->elements, true));
    return $elements_manager->index($element);
  }
  
  public function removeElementWithIndex($index)
  {
    $elements_manager = new ElementCollectionManager(json_decode($this->elements, true));
    $elements_manager->removeWithIndex($index);
    $this->setElements($elements_manager->getContent());
  }
  
  public function getElementsIds()
  {
    $elements_manager = new ElementCollectionManager(json_decode($this->elements, true));
    return $elements_manager->getAttributes(ElementCollectionManager::ATTRIBUTE_ID);
  }
  
  public function haveElement(Element $element)
  {
    $elements_manager = new ElementCollectionManager(json_decode($this->elements, true));
    return $elements_manager->have($element);
  }
  
  public function setCopied(Playlist $copied)
  {
    $this->copied = $copied;
  }
  
  public function getCopied()
  {
    return $this->copied;
  }
  
  public function addCopy(Playlist $playlist)
  {
    $this->copys->add($playlist);
  }
  
  public function getCopys()
  {
    return $this->copys;
  }
  
  public function setCopys($copys)
  {
    $this->copys = $copys;
  }
  
  public function isOwned(User $user)
  {
    if ($this->getOwner()->getId() == $user->getId())
    {
      return true;
    }
    
    return false;
  }
  
}