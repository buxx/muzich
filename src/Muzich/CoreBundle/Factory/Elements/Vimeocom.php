<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Factory\UrlMatchs;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;

class Vimeocom extends ElementFactory
{
  
  public function __construct(Element $element, Container $container, EntityManager $entity_manager)
  {
    $this->url_matchs = UrlMatchs::$vimeo;
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
    $response = $this->getApiConnector()->getResponseForUrl('http://vimeo.com/api/v2/video/'.$this->url_analyzer->getRefId().'.json');
    $this->getApiConnector()->setElementDatasWithResponse($response, array(
      Element::DATA_TITLE       => array(0 => 'title'),
      Element::DATA_THUMB_URL   => array(0 => 'thumbnail_medium')
    ));
  }
  
  public function proceedEmbedCode()
  {
    if (($ref_id = $this->element->getData(Element::DATA_REF_ID)))
    {
      $width = $this->container->getParameter('vimeo_player_width');
      $height = $this->container->getParameter('vimeo_player_height');
      $this->element->setEmbed(
        '<iframe src="http://player.vimeo.com/video/'.$ref_id.'?autoplay=1&api=1" '
        .'width="'.$width.'" height="'.$height.'" frameborder="0" '
        .'webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>'
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
