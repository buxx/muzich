<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;
use Muzich\CoreBundle\Entity\Element;

/**
 * 
 *
 * @author bux
 */
class Vimeocom extends ElementFactory
{
  
  public function retrieveDatas()
  {
    $url_clean = $this->getCleanedUrl();
    $ref_id = null;
    
    // http://vimeo.com/43258820
    if (preg_match("#\/([0-9]+)#", $url_clean, $chaines))
    {
      $ref_id = $chaines[1];
    }
    
    if ($ref_id)
    {
      $this->element->setData(Element::DATA_REF_ID, $ref_id);
      $this->getDataFromApi($ref_id);
    }
  }
  
  protected function getDataFromApi($ref_id)
  {
    $data = $this->getJsonDataFromApiWithUrl('http://vimeo.com/api/v2/video/'.$ref_id.'.json');
    
    if (count($data))
    {
      if (array_key_exists('title', $data[0]))
      {
        $this->element->setData(Element::DATA_TITLE, $data[0]['title']);
      }
      if (array_key_exists('thumbnail_medium', $data[0]))
      {
        $this->element->setData(Element::DATA_THUMB_URL, $data[0]['thumbnail_medium']);
      }
    }
  }
  
  public function proceedEmbedCode()
  {
    if (($ref_id = $this->element->getData(Element::DATA_REF_ID)))
    {
      $width = $this->container->getParameter('vimeo_player_width');
      $height = $this->container->getParameter('vimeo_player_height');
      $this->element->setEmbed(
        '<iframe src="http://player.vimeo.com/video/'.$ref_id.'&autoplay=1&api=1" '
        .'width="'.$width.'" height="'.$height.'" frameborder="0" '
        .'webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>'
      );
    }
  }
}
