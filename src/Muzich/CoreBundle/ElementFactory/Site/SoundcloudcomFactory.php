<?php

namespace Muzich\CoreBundle\ElementFactory\Site;

use Muzich\CoreBundle\ElementFactory\Site\base\BaseFactory;
use Muzich\CoreBundle\Entity\Element;
use Symfony\Component\DependencyInjection\Container;

/**
 * 
 *
 * @author bux
 */
class SoundcloudcomFactory extends BaseFactory
{
  public function getEmbedCode()
  {
    $url = str_replace('www.', '', $this->element->getUrl());
    $data = str_replace('http://soundcloud.com', '', $url);
    $embed_url = null;
    
    // http://soundcloud.com/matas/sets/library-project
    if (preg_match("#^\/[a-zA-Z0-9_-]+\/sets\/[a-zA-Z0-9_-]+#", $data, $chaines))
    {
      $embed_url = $url;
    }
    // http://soundcloud.com/matas/anadrakonic-waltz
    else if (preg_match("#^\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+#", $data, $chaines))
    {
      $embed_url = $url;
    }
        
    // Si c'est une recherche, on gère pas !
    // /search?q[fulltext]=tatou
    // /tracks/search?q%5Bfulltext%5D=EEK+A+MOUSSE&q%5Btype%5D=&q%5Bduration%5D=
    if (preg_match("#\/search\?q#", $data, $chaines))
    {
      $embed_url = null;
    }
    
    if ($embed_url)
    {
      // l'url est valide pour l'api javascript que l'on utilise
      
      $id = md5($url);
      $height = $this->container->getParameter('soundcloud_player_height');
      $embed = 
        '<object height="'.$height.'" width="100%" id="embed_'.$id.'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
          <param name="movie" value="http://player.soundcloud.com/player.swf?url='.$embed_url.'&amp;enable_api=true&amp;object_id=embed_'.$id.'"></param>
          <param name="allowscriptaccess" value="always"></param>
          <embed allowscriptaccess="always" height="'.$height.'" src="http://player.soundcloud.com/player.swf?url='.$embed_url.'&amp;enable_api=true&amp;object_id=embed_'.$id.'" type="application/x-shockwave-flash" width="100%" name="embed_'.$id.'"></embed>
        </object>
        ';
      
      return $embed;
    }
    
    return null;
  }
  
  public function getThumbnailUrl()
  {
    
    
    $url_object = $this->element->getUrl();
    $url = null;
    
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
    
    $url = $result->artwork_url;
    return $url;
  }
}

?>
