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
   * @MongoDB\Hash
   */
  protected $element_diffusion_tags;

  /**
   * @MongoDB\Hash
   */
  protected $element_favorite_tags;

  /**
   * @MongoDB\Hash
   */
  protected $element_playlist_tags;
  
  public function getElementDiffusionTags()
  {
    return $this->element_diffusion_tags;
  }
  
  public function setElementDiffusionTags($tags)
  {
    $this->element_diffusion_tags = $tags;
  }
  
  public function getElementFavoriteTags()
  {
    return $this->element_favorite_tags;
  }
  
  public function setElementFavoriteTags($tags)
  {
    $this->element_favorite_tags = $tags;
  }
  
  public function getElementPlaylistTags()
  {
    return $this->element_playlist_tags;
  }
  
  public function setElementPlaylistTags($tags)
  {
    $this->element_playlist_tags = $tags;
  }
  
}