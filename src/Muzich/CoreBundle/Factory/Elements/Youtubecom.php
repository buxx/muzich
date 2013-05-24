<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Factory\UrlMatchs;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;

class Youtubecom extends ElementFactory
{
  
  public function __construct(Element $element, Container $container, EntityManager $entity_manager)
  {
    $this->url_matchs = UrlMatchs::$youtube;
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
    $response = $this->getApiConnector()->getResponseForUrl('https://www.googleapis.com/youtube/v3/videos?id='
      .$this->url_analyzer->getRefId().'&key=AIzaSyC1DjeoUerFTUDdf2Rw8L8znEWbueGeykg&part=snippet,contentDetails,statistics,status');
    
    $this->getApiConnector()->setElementDatasWithResponse($response, array(
      Element::DATA_THUMB_URL  => array('items' => array(0 => array('thumbnails' => 'medium'))),
      Element::DATA_TITLE      => array('items' => array(0 => array('snippet' => 'title'))),
    ));
  }
  
  public function proceedEmbedCode()
  {
    if (($ref_id = $this->element->getData(Element::DATA_REF_ID)))
    {
      $width = $this->container->getParameter('youtube_player_width');
      $height = $this->container->getParameter('youtube_player_height');
      $this->element->setEmbed(
        '<iframe width="'.$width.'" height="'.$height.'" '
        .'src="http://www.youtube.com/embed/'.$ref_id.'" '
        .'frameborder="0" allowfullscreen></iframe>'
      );
    }
  }
  
  public function proceedThumbnailUrl()
  {
    if (($ref_id = $this->element->getData(Element::DATA_REF_ID)))
    {
      $this->element->setThumbnailUrl(
        'http://img.youtube.com/vi/'.$ref_id.'/default.jpg'
      );
    }
  }
}
