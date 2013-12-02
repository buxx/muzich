<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\lib\Api\Response as ApiResponse;
use Muzich\CoreBundle\Factory\UrlMatchs;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;

class Mixcloudcom extends ElementFactory
{
  
  public function __construct(Element $element, Container $container, EntityManager $entity_manager)
  {
    $this->url_matchs = UrlMatchs::$mixcloud;
    parent::__construct($element, $container, $entity_manager);
  }
  
  public function proceedDatas()
  {
    $this->setElementDatasWithApi();
    $this->proceedThumbnailUrl();
    $this->proceedEmbedCode();
  }
  
  protected function setElementDatasWithApi()
  {
    if (($response = $this->getApiDatasResponse()))
    {
      $this->setElementSharingData($response);
      $this->setTagsData($response);
    }
  }
  
  protected function getApiDatasResponse()
  {
    if (($response = $this->getApiConnector()->getResponseForUrl('http://api.mixcloud.com'.$this->getCleanedUrl())))
      if ($response->haveNot('error'))
        return $response;
    
    return null;
  }
  
  protected function setElementSharingData(ApiResponse $response)
  {
    $this->getApiConnector()->setElementDatasWithResponse($response, array(
      Element::DATA_TITLE          => 'name',
      Element::DATA_NORMALIZED_URL => 'url',
      Element::DATA_REF_ID         => 'key',
      Element::DATA_TYPE           => Element::TYPE_TRACK,
      Element::DATA_ARTIST         => array('user' => 'name'),
      Element::DATA_THUMB_URL      => array('pictures' => 'medium')
    ));
  }
  
  protected function setTagsData(ApiResponse $response)
  {
    $tags = $response->get('tags');
    $tags_array = array();
    if (count($tags))
    {
      foreach ($tags as $tag_data)
      {
        $tags_array[] = $tag_data['name'];
      }
    }
    $this->setDataTagsForElement(implode(' ', $tags_array));
  }
  
  public function proceedEmbedCode()
  {
    if (($response = $this->getApiConnector()->getResponseForUrl(
          'http://api.mixcloud.com'.$this->element->getData(Element::DATA_REF_ID).'embed-json/?width=100%'
       )))
    {
      if ($response->get('html'))
        $this->element->setEmbed($response->get('html'));
    }
  }
  
}