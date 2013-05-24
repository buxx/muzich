<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Factory\UrlMatchs;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;

class Jamendocom extends ElementFactory
{
  
  public function __construct(Element $element, Container $container, EntityManager $entity_manager)
  {
    $this->url_matchs = UrlMatchs::$jamendo;
    parent::__construct($element, $container, $entity_manager);
  }
  
  public function getStreamData()
  {
    $ref_id = $this->element->getData(Element::DATA_REF_ID);
    $api_url = "http://api.jamendo.com/get2/name+stream/track/json/?". $this->element->getData(Element::DATA_TYPE)."_id=".$ref_id;
    
    if ($ref_id)
    {
      $response = $this->getApiConnector()->getResponseForUrl($api_url);
      if (count($response->getContent()))
      {
        $data_return = array();
        foreach ($response->getContent() as $song)
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
  
  
  public function proceedDatas()
  {
    $this->retrieveDatas();
    $this->proceedThumbnailUrl();
  }
  
  protected function getApiUrl()
  {
    switch ($this->url_analyzer->getType())
    {
      case Element::TYPE_ALBUM:
        return "http://api.jamendo.com/get2/id+name+url+image+artist_name+artist_url/album/json/?album_id="
          .$this->url_analyzer->getRefId();
      break;
      case Element::TYPE_TRACK:
        return "http://api.jamendo.com/get2/id+name+url+image+artist_name+artist_url+track_name/album/json/?track_id="
          .$this->url_analyzer->getRefId();
      break;
    }
  }
  
  protected function getApiTagUrl()
  {
    return "http://api.jamendo.com/get2/name+weight/tag/json/".$this->url_analyzer->getType()."_tag/?".$this->url_analyzer->getType()."_id="
      .$this->url_analyzer->getRefId();
  }
  
  public function retrieveDatas()
  {
    if (($response = $this->getApiConnector()->getResponseForUrl($this->getApiUrl())))
    {
      // Check si tout se passe bien si pas de retour de l'api
      $this->getApiConnector()->setElementDatasWithResponse($response, array(
        Element::DATA_THUMB_URL      => array(0 => 'image'),
        Element::DATA_ARTIST         => array(0 => 'artist_name'),
      ));
      
      if ($this->url_analyzer->getType() == Element::TYPE_ALBUM)
      {
        $this->element->setData(Element::DATA_TITLE, $response->get(array(0 => 'name')));
      }
      if ($this->url_analyzer->getType() == Element::TYPE_TRACK)
      {
        $this->element->setData(Element::DATA_TITLE, $response->get(array(0 => 'track_name')));
      }
      
      $tags = array();
      $response = $this->getApiConnector()->getResponseForUrl($this->getApiTagUrl());
      if (count($response->getContent()))
      {
        foreach ($response->getContent() as $tag)
        {
          $tags[] = $tag['name'];
        }
      }
      $this->element->setData(Element::DATA_TAGS, $tags);
    }
    
    // Un contenu jamendo est toujours téléchargeable
    $this->element->setData(Element::DATA_DOWNLOAD, true);
  }
  
  public function proceedThumbnailUrl()
  {
    if (($thumb = $this->element->getData(Element::DATA_THUMB_URL)))
    {
      $this->element->setThumbnailUrl($thumb);
    }
  }
  
}
