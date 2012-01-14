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
  public function getEmbedCode()
  {
    $url = str_replace('www.', '', $this->element->getUrl());
    $data = str_replace('http://deezer.com', '', $url);
        
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
        .'&width='.$width.'&height='.$heigth.'&cover=true&btn_popup=true&type='.$type.'&id='.$chaines[1].'&title=" '
        .'width="'.$width.'" height="'.$heigth.'"></iframe>'
      ;
    }
    
    return $embed;
  }
}

?>
