<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;

/**
 * 
 *
 * @author bux
 */
class SoundcloudcomFactory extends ElementFactory
{
  
  /**
   * ??SET = http://soundcloud.com/matas/sets/library-project
   * ??    = http://soundcloud.com/matas/anadrakonic-waltz
   */
  public function retrieveDatas()
  {
    $url_clean = $this->getCleanedUrl();
    
    $ref_id = null;
    // ??SET
    if (preg_match("#^\/[a-zA-Z0-9_-]+\/sets\/[a-zA-Z0-9_-]+#", $url_clean, $chaines))
    {
      $ref_id = $url;
    }
    // ???
    else if (preg_match("#^\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+#", $url_clean, $chaines))
    {
      $ref_id = $url;
    }
    
    // On en gère pas encore les recherches
    if (preg_match("#\/search\?q#", $url_clean, $chaines))
    {
      $ref_id = null;
    }
    
    $this->element->setData('ref_id', $ref_id);
    
    // récupération de données avec l'API
    if ($ref_id)
    {
      $ch = curl_init('http://api.soundcloud.com/resolve.json?url='.$url_object.'&client_id=39946ea18e3d78d64c0ac95a025794e1');

      $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Content-type: application/json')
      );

      curl_setopt_array( $ch, $options );
      $result = json_decode(curl_exec($ch));

      if (isset($result->errors))
      {
        if (count($result->errors))
        {
          return null;
        }
      }

      $getjsonurl = $result->location;
      $ch = curl_init($getjsonurl);
      curl_setopt_array($ch, $options);
      $result = json_decode(curl_exec($ch));

      if (isset($result->artwork_url))
      {
        $this->element->setData('artwork_url', $result->artwork_url);
      }
    }
  }
  
  public function proceedEmbedCode()
  {
    if (($ref_id = $this->element->getData('ref_id')))
    {
      $embed_id = md5($ref_id);
      $height = $this->container->getParameter('soundcloud_player_height');
      $this->element->setEmbed(
        '<object height="'.$height.'" width="100%" id="embed_'.$embed_id.'" '
          .'classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
          <param name="movie" value="http://player.soundcloud.com/player.swf?url='.$ref_id.'&amp;'
          .'enable_api=true&amp;object_id=embed_'.$embed_id.'"></param>
          <param name="allowscriptaccess" value="always"></param>
          <embed allowscriptaccess="always" height="'.$height.'" '
          .'src="http://player.soundcloud.com/player.swf?url='.$ref_id.'&amp;enable_api=true'
          .'&amp;object_id=embed_'.$embed_id.'" type="application/x-shockwave-flash" '
          .'width="100%" name="embed_'.$embed_id.'"></embed>
        </object>'
      );
    }
  }
  
  public function proceedThumbnailUrl()
  {
    if (($thumb = $this->element->getData('artwork_url')))
    {
      $this->element->setThumbnailUrl($thumb);
    }
  }
  
}