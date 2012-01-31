<?php

namespace Muzich\CoreBundle\ElementFactory\Site;

use Muzich\CoreBundle\ElementFactory\Site\base\BaseFactory;

/**
 * 
 *
 * @author bux
 */
class YoutubeFactory extends BaseFactory
{
  public function getEmbedCode()
  {
    $url = $this->getCleanedUrl();
    $embed_url = null;
    
    // http://youtu.be/9hQVA2sloGc
    if (preg_match("#\/([a-zA-Z0-9]+)#", $url, $chaines))
    {
      $embed_url = 'http://www.youtube.com/embed/'.$chaines[1];
    }
        
    if ($embed_url)
    {
      $width = $this->container->getParameter('youtube_player_width');
      $height = $this->container->getParameter('youtube_player_height');
      return '<iframe width="'.$width.'" height="'.$height.'" src="'.$embed_url.'" '
        .'frameborder="0" allowfullscreen></iframe>';
    }
    
    return null;
  }
  
  public function getThumbnailUrl()
  {
    $url_object = $this->getCleanedUrl();
    $url = null;
    
    // http://youtu.be/9hQVA2sloGc
    if (preg_match("#\/([a-zA-Z0-9]+)#", $url_object, $chaines))
    {
      $url = 'http://img.youtube.com/vi/'.$chaines[1].'/default.jpg';
    }
    
    return $url;
  }
}

?>
