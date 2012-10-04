<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\Elements\Youtubecom;
use Muzich\CoreBundle\Entity\Element;

/**
 * 
 *
 * @author bux
 */
class Youtube extends Youtubecom
{
  
  public function retrieveDatas()
  {
    $url_clean = $this->getCleanedUrl();
    $ref_id = null;
    
    if (preg_match("#\/([a-zA-Z0-9]+)#", $url_clean, $chaines))
    {
      $ref_id = $chaines[1];
    }
    
    $this->element->setData(Element::DATA_REF_ID, $ref_id);
    
    // Données API
    if ($ref_id)
    {
      
    }
  }
  
}
