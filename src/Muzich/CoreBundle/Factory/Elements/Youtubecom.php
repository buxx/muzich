<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;

/**
 * 
 *
 * @author bux
 */
class Youtubecom extends ElementFactory
{
  
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
    
    $this->element->setData('ref_id', $ref_id);
    
    // Données API TODO: REFACTORISER
    if ($ref_id)
    {
      
    }
  }
  
  public function proceedEmbedCode()
  {
    if (($ref_id = $this->element->getData('ref_id')))
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
    if (($ref_id = $this->element->getData('ref_id')))
    {
      $this->element->setThumbnailUrl(
        'http://img.youtube.com/vi/'.$ref_id.'/default.jpg'        
      );
    }
  }
}
