<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;
use Muzich\CoreBundle\Entity\Element;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Muzich\CoreBundle\Factory\UrlMatchs;


/**
 * 
 *
 * @author bux
 */
class Deezercom extends ElementFactory
{
  
  public function __construct(Element $element, Container $container, EntityManager $entity_manager)
  {
    $this->url_matchs = UrlMatchs::$deezer;
    parent::__construct($element, $container, $entity_manager);
  }
  
  public function proceedDatas()
  {
    $this->setElementDatasWithApi();
    $this->proceedEmbedCode();
    $this->proceedThumbnailUrl();
  }
  
  protected function setElementDatasWithApi()
  {
    $response = $this->getApiConnector()->getResponseForUrl('http://api.deezer.com/2.0/'.$this->url_analyzer->getType().'/'.$this->url_analyzer->getRefId());
    $this->getApiConnector()->setElementDatasWithResponse($response, array(
      Element::DATA_THUMB_URL      => 'cover',
      Element::DATA_TITLE          => 'title',
      Element::DATA_ARTIST         => array('artist' => 'name')
    ));
    
    if ($this->url_analyzer->getType() == Element::TYPE_TRACK && !$this->element->getData(Element::DATA_THUMB_URL))
    {
      $this->getApiConnector()->setElementDatasWithResponse($response, array(
        Element::DATA_THUMB_URL      => array('album' => 'cover')
      ));
    }
  }
  
  protected function getCleanedUrl($decode = false, $force_base_url = null)
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
  //public function retrieveDatas()
  //{
  //  $url_clean = $this->getCleanedUrl(true);
  //  
  //  // album
  //  $type   = null;
  //  $ref_id = null;
  //  if (preg_match("#^\/[a-zA-Z_-]+\/music\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+-([0-9]+)#", $url_clean, $chaines))
  //  {
  //    $type   = 'album';
  //    $ref_id = $chaines[1];
  //  }
  //  // http://www.deezer.com/fr/album/379324
  //  else if (preg_match("#^\/[a-zA-Z_-]+\/album\/([0-9]+)#", $url_clean, $chaines))
  //  {
  //    $type   = 'album';
  //    $ref_id = $chaines[1];
  //  }
  //  // playlist
  //  else if (preg_match("#^\/[a-zA-Z_-]+\/music\/playlist\/([0-9]+)#", $url_clean, $chaines))
  //  {
  //    $type = 'playlist';
  //    $ref_id = $chaines[1];
  //  }
  //  // http://www.deezer.com/track/4067216
  //  else if (preg_match("#^\/track\/([0-9]+)#", $url_clean, $chaines))
  //  {
  //    $type = 'track';
  //    $ref_id = $chaines[1];
  //  }
  //  
  //  
  //  $this->element->setData(Element::DATA_TYPE  , $type);
  //  $this->element->setData(Element::DATA_REF_ID, $ref_id);
  //  
  //  if ($type && $ref_id)
  //  {
  //    // Récupération d'infos auprès de l'API
  //    $ch = curl_init('http://api.deezer.com/2.0/'.$type.'/'.$ref_id);
  //    $options = array(
  //      CURLOPT_RETURNTRANSFER => true,
  //      CURLOPT_HTTPHEADER => array('Content-type: application/json')
  //    );
  //    curl_setopt_array( $ch, $options );
  //    $result = json_decode(curl_exec($ch));
  //    
  //    if (isset($result->cover))
  //    {
  //      $this->element->setData(Element::DATA_THUMB_URL, $result->cover);
  //    }
  //    else if ($type == 'track')
  //    {
  //      if (isset($result->album))
  //      {
  //        if (isset($result->album->cover))
  //        {
  //          $this->element->setData(Element::DATA_THUMB_URL, $result->album->cover);
  //        }
  //      }
  //    }
  //    
  //    if ($type == 'album' || $type == 'track')
  //    {
  //      if (isset($result->title))
  //      {
  //        $this->element->setData(Element::DATA_TITLE, $result->title);
  //      }
  //      if (isset($result->artist))
  //      {
  //        if (isset($result->artist->name))
  //        {
  //          $this->element->setData(Element::DATA_ARTIST, $result->artist->name);
  //        }
  //      }
  //    }
  //    
  //  }
  //}
  
  public function proceedEmbedCode()
  {
    if (($ref_id = $this->element->getData(Element::DATA_REF_ID)) 
      && ($type = $this->element->getData(Element::DATA_TYPE))
      && ($this->element->getData(Element::DATA_TYPE) == 'album' ||
         $this->element->getData(Element::DATA_TYPE) == 'playlist'||
         $this->element->getData(Element::DATA_TYPE) == 'track')
      )
    {
      if ($type == 'track')
      {
        $type = 'tracks';
      }
      
      $width = $this->container->getParameter('deezer_player_width');
      $heigth = $this->container->getParameter('deezer_player_height');
      $this->element->setEmbed(
        '<iframe scrolling="no" frameborder="0" allowTransparency="true" '
        .'src="http://www.deezer.com/fr/plugins/player?autoplay=true&playlist=true'
        .'&width='.$width.'&height='.$heigth.'&cover=true&type='.$type.'&id='.$ref_id.'&title=&app_id=undefined"'
        .' width="'.$width.'" height="'.$heigth.'"></iframe>'
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