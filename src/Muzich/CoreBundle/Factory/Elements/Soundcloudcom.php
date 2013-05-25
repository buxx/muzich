<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\lib\Api\Response as ApiResponse;
use Muzich\CoreBundle\Factory\UrlMatchs;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;

class Soundcloudcom extends ElementFactory
{
  
  public function __construct(Element $element, Container $container, EntityManager $entity_manager)
  {
    $this->url_matchs = UrlMatchs::$soundcloud;
    parent::__construct($element, $container, $entity_manager);
  }
  
  public function proceedDatas()
  {
    $this->cleanUrl();
    $this->setElementDatasWithApi();
    $this->proceedThumbnailUrl();
  }
  
  protected function cleanUrl()
  {
    if (strpos($this->element->getUrl(), '#') !== false)
    {
      $this->element->setUrl(substr($this->element->getUrl(), 0, strpos($this->element->getUrl(), '#')));
    }
  }
  
  protected function setElementDatasWithApi()
  {
    if (($response = $this->getApiDatasResponse()))
    {
      if ($response->get('sharing') == 'public')
      {
        $this->setElementSharingData($response);
        $this->setTagsData($response);
      }
      
      if ($response->get('embeddable_by') == 'all')
      {
        $this->setElementEmbeddableData($response);
      }
    }
  }
  
  protected function getApiDatasResponse()
  {
    if (($response = $this->getApiConnector()->getResponseForUrl('http://api.soundcloud.com/resolve.json?url='.$this->element->getUrl().'&client_id=39946ea18e3d78d64c0ac95a025794e1')))
    {
      if ($response->haveNot('errors') && $response->have('location'))
      {
        return $this->getApiConnector()->getResponseForUrl($response->get('location'));
      }
    }
    
    return null;
  }
  
  protected function setElementSharingData(ApiResponse $response)
  {
    $this->getApiConnector()->setElementDatasWithResponse($response, array(
      Element::DATA_NORMALIZED_URL => 'uri',
      Element::DATA_TYPE           => 'kind',
      Element::DATA_DOWNLOAD       => 'downloadable',
      Element::DATA_DOWNLOAD_URL   => 'download_url',
      Element::DATA_TITLE          => 'title',
      Element::DATA_ARTIST         => array('user' => 'username')
    ));
    
    $this->setThumbnailData($response);
  }
  
  protected function setThumbnailData(ApiResponse $response)
  {
    if ($response->have('artwork_url'))
    {
      $this->element->setData(Element::DATA_THUMB_URL, $response->get('artwork_url'));
    }
    elseif ($response->have(array('user' => 'avatar_url')))
    {
      $this->element->setData(Element::DATA_THUMB_URL, $response->get(array('user' => 'avatar_url')));
    }
  }
  
  protected function setTagsData(ApiResponse $response)
  {
    $tags_string = $response->get('genre').' '.$response->get('tag_list').' '.str_replace(' ', '-', $response->get('genre'));
    $this->setDataTagsForElement($tags_string, array($response->get('genre')));
  }
  
  protected function setElementEmbeddableData($response)
  {
    $this->getApiConnector()->setElementDatasWithResponse($response, array(
      Element::DATA_REF_ID => 'id'
    ));
  }
  
  public function proceedThumbnailUrl()
  {
    if (($thumb = $this->element->getData(Element::DATA_THUMB_URL)))
    {
      $this->element->setThumbnailUrl($thumb);
    }
  }
  
}