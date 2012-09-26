<?php

namespace Muzich\CoreBundle\ElementFactory\Site;

use Muzich\CoreBundle\ElementFactory\Site\base\BaseFactory;

/**
 * 
 *
 * @author bux
 */
class DeezercomFactory extends BaseFactory
{
  
  protected function getCleanedUrl($decode = false)
  {
    $data = parent::getCleanedUrl(true);
    $data = str_replace(' ', '-', $data);
    $data = strtolower($data);
    
    return $data;
  }
  
  public function getEmbedCode()
  {
    $data = $this->getCleanedUrl(true);
    
    $embed = null;
    $element_id = null;
    $type = null;
    // album
    // http://www.deezer.com/fr/music/pantera/far-beyond-driven-80398
    if (preg_match("#^\/[a-zA-Z_-]+\/music\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+-([0-9]+)#", $data, $chaines))
    {
      $element_id = $chaines[1];
      $type = 'album';
    }
    // playlist
    // http://www.deezer.com/fr/music/playlist/18701350
    else if (preg_match("#^\/[a-zA-Z_-]+\/music\/playlist\/([0-9]+)#", $data, $chaines))
    {
      $element_id = $chaines[1];
      $type = 'playlist';
    }
    
    if ($element_id)
    {
      $width = $this->container->getParameter('deezer_player_width');
      $heigth = $this->container->getParameter('deezer_player_height');
      $embed = '<iframe scrolling="no" frameborder="0" allowTransparency="true" '
        .'src="http://www.deezer.com/fr/plugins/player?autoplay=false&playlist=true'
        .'&width='.$width.'&height='.$heigth.'&cover=true&btn_popup=true&type='.$type.'&id='.$element_id.'&title=" '
        .'width="'.$width.'" height="'.$heigth.'"></iframe>'
      ;
    }
    
    return $embed;
  }
  
  public function getThumbnailUrl()
  {
    $url_object = $this->getCleanedUrl();
    $url = null;
    
    // album
    // http://www.deezer.com/fr/music/pantera/far-beyond-driven-80398
    if (preg_match("#^\/[a-zA-Z_-]+\/music\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+-([0-9]+)#", $url_object, $chaines))
    {
      $id = $chaines[1];
      $ch = curl_init('http://api.deezer.com/2.0/album/'.$id);
      $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Content-type: application/json')
      );
      curl_setopt_array( $ch, $options );
      $result = json_decode(curl_exec($ch));
      
      if (isset($result->cover))
      {
        $url = $result->cover;
      }
    }
    
    return $url;
  }
  
}