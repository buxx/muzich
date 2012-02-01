<?php

namespace Muzich\CoreBundle\ElementFactory\Site;

use Muzich\CoreBundle\ElementFactory\Site\base\BaseFactory;

/**
 * 
 *
 * @author bux
 */
class DailymotioncomFactory extends BaseFactory
{
  public function getEmbedCode()
  {
    $url = $this->getCleanedUrl();
    $embed_url = null;
    
    // /video/xnqcwx_le-nazisme-dans-le-couple_fun#hp-v-v2
    if (preg_match("#(video\/)([a-zA-Z0-9]+)([a-zA-Z0-9_-]*)#", $url, $chaines))
    {
      $embed_url = 'http://www.dailymotion.com/embed/video/'.$chaines[2];
    }
    
    if ($embed_url)
    {
      $width = $this->container->getParameter('dailymotion_player_width');
      $height = $this->container->getParameter('dailymotion_player_height');
      return '<iframe frameborder="0" width="'.$width.'" height="'.$height.'" '
        .'src="'.$embed_url.'"></iframe>';
    }
    
    return null;
  }
  
  /*
   * http://www.dailymotion.com/doc/api/obj-video.html
   */
  public function getThumbnailUrl()
  {
    // https://api.dailymotion.com/video/xmi3i1&fields=thumbnail_medium_url
    $url_object = $this->getCleanedUrl();
    $url = null;
    
    if (preg_match("#(video\/)([a-zA-Z0-9]+)([a-zA-Z0-9_-]*)#", $url_object, $chaines))
    {
      $ch = curl_init('https://api.dailymotion.com/video/'.$chaines[2].'&fields=thumbnail_medium_url');
      
      $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Content-type: application/json')
      );
      
      curl_setopt_array( $ch, $options );
      $result = json_decode(curl_exec($ch));
      
      $url = $result->thumbnail_medium_url;
      ;
    }
    
    return $url;
  }
  
}

?>
