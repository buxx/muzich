<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;
use Muzich\CoreBundle\Entity\Element;

/**
 * 
 *
 * @author bux
 */
class Deezercom extends ElementFactory
{
  
  protected function getCleanedUrl($decode = false)
  {
    $data = parent::getCleanedUrl($decode);
    $data = str_replace(' ', '-', $data);
    $data = strtolower($data);
    
    return $data;
  }
  
  /**
   *  URL_PLAYLIST: http://www.deezer.com/fr/music/playlist/18701350
   *  URL_ALBUM:    http://www.deezer.com/fr/music/pantera/far-beyond-driven-80398
   *
   * http://www.deezer.com/fr/music/the-delta/Thing%20EP-379324
   * http://www.deezer.com/fr/album/379324
   */
  public function retrieveDatas()
  {
    $url_clean = $this->getCleanedUrl(true);
    
    // album
    $type   = null;
    $ref_id = null;
    if (preg_match("#^\/[a-zA-Z_-]+\/music\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+-([0-9]+)#", $url_clean, $chaines))
    {
      $type   = 'album';
      $ref_id = $chaines[1];
    }
    // playlist
    else if (preg_match("#^\/[a-zA-Z_-]+\/music\/playlist\/([0-9]+)#", $url_clean, $chaines))
    {
      $type = 'playlist';
      $ref_id = $chaines[1];
    }
    
    $this->element->setData(Element::DATA_TYPE  , $type);
    $this->element->setData(Element::DATA_REF_ID, $ref_id);
    
    if ($type && $ref_id)
    {
      // Récupération d'infos auprès de l'API
      $ch = curl_init('http://api.deezer.com/2.0/'.$type.'/'.$ref_id);
      $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Content-type: application/json')
      );
      curl_setopt_array( $ch, $options );
      $result = json_decode(curl_exec($ch));
      
      if (isset($result->cover))
      {
        $this->element->setData(Element::DATA_THUMB_URL, $result->cover);
      }
      
      
    }
  }
  
  public function proceedEmbedCode()
  {
    if (($ref_id = $this->element->getData(Element::DATA_REF_ID)) 
      && ($type = $this->element->getData(Element::DATA_TYPE)))
    {
      $width = $this->container->getParameter('deezer_player_width');
      $heigth = $this->container->getParameter('deezer_player_height');
      $this->element->setEmbed(
        '<iframe scrolling="no" frameborder="0" allowTransparency="true" '
        .'src="http://www.deezer.com/fr/plugins/player?autoplay=false&playlist=true'
        .'&width='.$width.'&height='.$heigth.'&cover=true&btn_popup=true&type='.$type.'&id='.$ref_id.'&title=" '
        .'width="'.$width.'" height="'.$heigth.'"></iframe>'
      );
    }
  }
  
  public function proceedThumbnailUrl()
  {
    if (($thumb = $this->element->getData(Element::DATA_THUMB_URL)))
    {
      $this->element->setThumbnailUrl($thumb);
    }
  }
  
}