<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;
use Muzich\CoreBundle\Entity\Element;

/**
 * 
 *
 * @author bux
 */
class Youtubecom extends ElementFactory
{
  
  protected function proceedAPIDatas($ref_id)
  {
    $video_data_dom = new \DOMDocument;
    $video_data_dom->load("http://gdata.youtube.com/feeds/api/videos/". $ref_id);
    
    if (($title = $video_data_dom->getElementsByTagName("title")->item(0)->nodeValue))
    {
      $this->element->setData(Element::DATA_TITLE, $title);
    }
  }
  
  public function retrieveDatas()
  {
    $url_clean = $this->getCleanedUrl();
    $ref_id = null;
    
    if (preg_match("#(v\/|watch\?v=)([\w\-]+)#", $url_clean, $chaines))
    {
      $ref_id = $chaines[2];
    }
    else if (preg_match("#(v=|watch\?v=)([\w\-]+)#", $url_clean, $chaines))
    {
      $ref_id = $chaines[2];
    }
    
    $this->element->setData(Element::DATA_REF_ID, $ref_id);
    
    // Données API TODO: REFACTORISER
    if ($ref_id)
    {
      $this->proceedAPIDatas($ref_id);
    }
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
