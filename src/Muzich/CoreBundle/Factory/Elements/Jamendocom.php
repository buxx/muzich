<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;
use Muzich\CoreBundle\Entity\Element;

/**
 * 
 *
 * @author bux
 */
class Jamendocom extends ElementFactory
{
  
  /**
   *  ALBUM = http://www.jamendo.com/fr/album/30661
   *  TRACK = http://www.jamendo.com/fr/track/207079
   * 
   * API: http://developer.jamendo.com/fr/wiki/Musiclist2ApiFields
   */
  public function retrieveDatas()
  {
    $url_clean = $this->getCleanedUrl();
        
    // album
    // http://www.jamendo.com/fr/album/3409
    $type   = null;
    $ref_id = null;
    if (preg_match("#^\/[a-zA-Z0-9_-]+\/album\/([0-9]+)#", $url_clean, $chaines))
    {
      $type   = 'album';
      $ref_id = $chaines[1];
    }
    // track
    // http://www.jamendo.com/fr/track/894974
    else if (preg_match("#^\/[a-zA-Z0-9_-]+\/track\/([0-9]+)#", $url_clean, $chaines))
    {
      $type = 'track';
      $ref_id = $chaines[1];
    }
    // album new ver
    // http://www.jamendo.com/fr/list/a45666/proceed-positron...
    else if (preg_match("#^\/[a-zA-Z0-9_-]+\/list\/a([0-9]+)\/.#", $url_clean, $chaines))
    {
      $type   = 'album';
      $ref_id = $chaines[1];
    }
    // track new ver
    // http://www.jamendo.com/fr/track/347602/come-come
    else if (preg_match("#^\/[a-zA-Z0-9_-]+\/track\/([0-9]+)\/.#", $url_clean, $chaines))
    {
      $type = 'track';
      $ref_id = $chaines[1];
    }
    
    $this->element->setData(Element::DATA_TYPE  , $type);
    $this->element->setData(Element::DATA_REF_ID, $ref_id);
    
    // Récupération de données avec l'API
    $api_url = null;
    switch ($type)
    {
      case 'album':
        $api_url = "http://api.jamendo.com/get2/"
          ."id+name+url+image+artist_name+artist_url/album/jsonpretty/?album_id=".$ref_id;
        $api_tag_url = "http://api.jamendo.com/get2/name+weight/tag/json/album_tag/?album_id=".$ref_id;
      break;
    
      case 'track':
        $api_url = "http://api.jamendo.com/get2/"
          ."id+name+url+image+artist_name+artist_url+track_name/album/json/?track_id=".$ref_id;
        $api_tag_url = "http://api.jamendo.com/get2/name+weight/tag/json/track_tag/?track_id=".$ref_id;
      break;
    }
    
    if ($api_url)
    {
      $ch = curl_init($api_url);
      $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Content-type: text/plain')
      );
      curl_setopt_array( $ch, $options );
      $result = json_decode(curl_exec($ch), true);
            
      if (count($result))
      {
        // Thumb
        if (array_key_exists('image', $result[0]))
        {
          $this->element->setData(Element::DATA_THUMB_URL, $result[0]['image']);
        }
        
        // Album name
        if (array_key_exists('name', $result[0]) && $type == 'album')
        {
          $this->element->setData(Element::DATA_TITLE, $result[0]['name']);
        }
        
        // Artist name
        if (array_key_exists('artist_name', $result[0]))
        {
          $this->element->setData(Element::DATA_ARTIST, $result[0]['artist_name']);
        }
        
        // track name
        if (array_key_exists('track_name', $result[0])  && $type == 'track')
        {
          $this->element->setData(Element::DATA_TITLE, $result[0]['track_name']);
        }
        
        // Maintenant au tour des tags (deuxième requete a l'api)
        $ch = curl_init($api_tag_url);
        $options = array(
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HTTPHEADER => array('Content-type: text/plain')
        );
        curl_setopt_array( $ch, $options );
        $result = json_decode(curl_exec($ch), true);
      
        if (count($result))
        {
          $tags = array();
          foreach ($result as $tag)
          {
            $tags[] = $tag['name'];
          }
          
          $this->element->setData(Element::DATA_TAGS, $tags);
        }
      }
    }
    
    // Un contenu jamendo est toujours téléchargeable
    $this->element->setData(Element::DATA_DOWNLOAD, true);
  }
  
  public function proceedEmbedCode()
  {
    if (($ref_id = $this->element->getData(Element::DATA_REF_ID)) 
      && ($type = $this->element->getData(Element::DATA_TYPE)))
    {
      $height = $this->container->getParameter('jamendo_player_height');
      $width = $this->container->getParameter('jamendo_player_width');
      $embed_url = "http://widgets.jamendo.com/fr/$type/?".$type."_id=$ref_id&playertype=2008";
      $this->element->setEmbed(
        '<object width="'.$width.'" height="'.$height.'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"'
            .' codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" align="middle">
            <param name="allowScriptAccess" value="always" />
            <param name="wmode" value="transparent" />
            <param name="movie" value="'.$embed_url.'" />
            <param name="quality" value="high" />
            <param name="bgcolor" value="#FFFFFF" />
            <embed src="'.$embed_url.'" quality="high" wmode="transparent" bgcolor="#FFFFFF"'
            .' width="'.$width.'" height="'.$height.'" align="middle" allowScriptAccess="always"'
            .' type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
              &nbsp;
            </embed>
            &nbsp;
          </object>'  
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
