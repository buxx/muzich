<?php

namespace Muzich\CoreBundle\ElementFactory\Site;

use Muzich\CoreBundle\ElementFactory\Site\base\BaseFactory;

/**
 * 
 *
 * @author bux
 */
class YoutubecomFactory extends BaseFactory
{
  public function getEmbedCode()
  {
    $url = $this->getCleanedUrl();
    $embed_url = null;
    
    // '/watch?v=kOLQIV22JAs&feature=feedrec_grec_index'
    if (preg_match("#(v\/|watch\?v=)([\w\-]+)#", $url, $chaines))
    {
      $embed_url = 'http://www.youtube.com/embed/'.$chaines[2];
    }
    else if (preg_match("#(v=|watch\?v=)([\w\-]+)#", $url, $chaines))
    {
      $embed_url = 'http://www.youtube.com/embed/'.$chaines[2];
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
    
    // '/watch?v=kOLQIV22JAs&feature=feedrec_grec_index'
    if (preg_match("#(v\/|watch\?v=)([\w\-]+)#", $url_object, $chaines))
    {
      $url = 'http://img.youtube.com/vi/'.$chaines[2].'/default.jpg';
    }
    else if (preg_match("#(v=|watch\?v=)([\w\-]+)#", $url_object, $chaines))
    {
      $url = 'http://img.youtube.com/vi/'.$chaines[2].'/default.jpg';
    }
    
    return $url;
  }
}

?>
