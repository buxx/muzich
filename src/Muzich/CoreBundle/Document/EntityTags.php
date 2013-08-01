<?php
namespace Muzich\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\MappedSuperclass
 */
class EntityTags
{
  
  const TYPE_USER = 'User';
  const TYPE_GROUP = 'Group';
  const TYPE_PLAYLIST = 'Playlist';
  
  /**
   * @MongoDB\Id
   */
  protected $id;

  /**
   * @MongoDB\Int
   * @MongoDB\Index(unique=true) 
   */
  protected $ref;

  /**
   * @MongoDB\Collection
   */
  protected $tags;

  public function getId()
  {
    return $this->id;
  }

  public function getRef()
  {
    return $this->ref;
  }

  public function setRef($ref)
  {
    $this->ref = (int)$ref;
  }

  public function getTags()
  {
    return $this->tags;
  }
  
  public function setTags($tags)
  {
    $this->tags = $tags;
  }
  
}