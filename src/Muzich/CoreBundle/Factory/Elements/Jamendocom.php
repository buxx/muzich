<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Factory\UrlMatchs;

/**
 * 
 *
 * @author bux
 */
class Jamendocom extends ElementFactory
{
  
  public function __construct(Element $element, Container $container, EntityManager $entity_manager)
  {
    parent::__construct($element, $container, $entity_manager);
    $this->url_matchs = UrlMatchs::$jamendo;
  }
  
  public function getStreamData()
  {
    // On determine le type et l'url
    $this->proceedTypeAndId();
    
    $type = $this->element->getData(Element::DATA_TYPE);
    $ref_id = $this->element->getData(Element::DATA_REF_ID);
    
    // Récupération de données avec l'API
    $api_url = null;
    switch ($type)
    {
      case 'track':
        $api_url = "http://api.jamendo.com/get2/name+stream/track/json/?track_id=".$ref_id;
      break;
    
      case 'album':
        $api_url = "http://api.jamendo.com/get2/name+stream/track/json/?album_id=".$ref_id;
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
        $data_return = array();
        foreach ($result as $song)
        {
          $data_return[] = array(
            'name' => $song['name'],
            'url'  => $song['stream'],
          );
        }
        return $data_return;
      }
    }
  }
  
  /**
   *  ALBUM = http://www.jamendo.com/fr/album/30661
   *  TRACK = http://www.jamendo.com/fr/track/207079
   * 
   * API: http://developer.jamendo.com/fr/wiki/Musiclist2ApiFields
   */
  public function retrieveDatas()
  {
    $type = $this->element->getData(Element::DATA_TYPE);
    $ref_id = $this->element->getData(Element::DATA_REF_ID);
    
    // Récupération de données avec l'API
    $api_url = null;
    switch ($type)
    {
      case 'album':
        $api_url = "http://api.jamendo.com/get2/"
          ."id+name+url+image+artist_name+artist_url/album/json/?album_id=".$ref_id;
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
