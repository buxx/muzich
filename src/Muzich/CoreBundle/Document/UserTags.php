<?php
namespace Muzich\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Muzich\CoreBundle\Document\EntityTags;

/**
 * @MongoDB\Document
 */
class UserTags extends EntityTags
{

  /**
   * @MongoDB\Collection
   */
  protected $element_diffusion_tags;

  /**
   * @MongoDB\Collection
   */
  protected $element_favorite_tags;

  /**
   * @MongoDB\Collection
   */
  protected $element_playlist_tags;
  
  public function getElementDiffusionTags()
  {
    if (!$this->element_diffusion_tags)
      return array();
      
    return $this->element_diffusion_tags;
  }
  
  public function setElementDiffusionTags($tags)
  {
    $this->element_diffusion_tags = $tags;
  }
  
  public function getElementFavoriteTags()
  {
    if (!$this->element_favorite_tags)
      return array();
      
    return $this->element_favorite_tags;
  }
  
  public function setElementFavoriteTags($tags)
  {
    $this->element_favorite_tags = $tags;
  }
  
  public function getElementPlaylistTags()
  {
    if (!$this->element_playlist_tags)
      return array();
      
    return $this->element_playlist_tags;
  }
  
  public function setElementPlaylistTags($tags)
  {
    $this->element_playlist_tags = $tags;
  }
  
}