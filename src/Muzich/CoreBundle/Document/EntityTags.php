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
  protected $tags_top_1;

  /**
   * @MongoDB\Collection
   */
  protected $tags_top_2;

  /**
   * @MongoDB\Collection
   */
  protected $tags_top_3;

  /**
   * @MongoDB\Collection
   */
  protected $tags_top_5;

  /**
   * @MongoDB\Collection
   */
  protected $tags_top_10;

  /**
   * @MongoDB\Collection
   */
  protected $tags_top_25;

  /**
   * @MongoDB\Collection
   */
  protected $tags_all;

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

  public function getTagsAll()
  {
    return $this->tags_all;
  }
  
  public function setTagsAll($tags)
  {
    $this->tags_all = $tags;
  }
  
  public function setTagsTop1($tags)
  {
    $this->tags_top_1 = $tags;
  }
  
  public function getTagsTop1()
  {
    return $this->tags_top_1;
  }
  
  public function setTagsTop2($tags)
  {
    $this->tags_top_2 = $tags;
  }
  
  public function getTagsTop2()
  {
    return $this->tags_top_2;
  }
  
  public function setTagsTop3($tags)
  {
    $this->tags_top_3 = $tags;
  }
  
  public function getTagsTop3()
  {
    return $this->tags_top_3;
  }
  
  public function setTagsTop5($tags)
  {
    $this->tags_top_5 = $tags;
  }
  
  public function getTagsTop5()
  {
    return $this->tags_top_5;
  }
  
  public function setTagsTop10($tags)
  {
    $this->tags_top_10 = $tags;
  }
  
  public function getTagsTop10()
  {
    return $this->tags_top_10;
  }
  
  public function setTagsTop25($tags)
  {
    $this->tags_top_25 = $tags;
  }
  
  public function getTagsTop25()
  {
    return $this->tags_top_25;
  }
  
}