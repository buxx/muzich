<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\Elements\Youtubecom;

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
      $ref_id = $chaines[2];
    }
    
    $this->element->setData('ref_id', $ref_id);
    
    // Données API
    if ($ref_id)
    {
      
    }
  }
  
}
