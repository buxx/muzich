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
class Dailymotioncom extends ElementFactory
{
  
  public function __construct(Element $element, Container $container, EntityManager $entity_manager)
  {
    $this->url_matchs = UrlMatchs::$dailymotion;
    parent::__construct($element, $container, $entity_manager);
  }
  
  public function proceedDatas()
  {
    $this->setElementDatasWithApi();
    $this->proceedEmbedCode();
    $this->proceedThumbnailUrl();
  }
  
  /**
   * URL_API: http://www.dailymotion.com/doc/api/obj-video.html
   * URL_TYPE: /video/xnqcwx_le-nazisme-dans-le-couple_fun#hp-v-v2 
   */
  public function setElementDatasWithApi()
  {
    $response = $this->getApiConnector()->getResponseForUrl('https://api.dailymotion.com/video/'
      .$this->url_analyzer->getRefId().'&fields=thumbnail_medium_url,title,tags');
    
    $this->getApiConnector()->setElementDatasWithResponse($response, array(
      Element::DATA_THUMB_URL      => 'thumbnail_medium_url',
      Element::DATA_TITLE          => 'title',
    ));
    
    if ($response->have('tags'))
    {
      $this->setDataTagsForElement(implode(' ', $response->get('tags')));
    }
    
  }
  
  public function proceedEmbedCode()
  {
    if (($ref_id = $this->element->getData(Element::DATA_REF_ID)))
    {
      $width = $this->container->getParameter('dailymotion_player_width');
      $height = $this->container->getParameter('dailymotion_player_height');
      $this->element->setEmbed(
        '<iframe frameborder="0" width="'.$width.'" height="'.$height.'" '
        .'src="http://www.dailymotion.com/embed/video/'.$ref_id.'?autoPlay=1"></iframe>'
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