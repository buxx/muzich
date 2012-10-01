<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;

/**
 * 
 *
 * @author bux
 */
class Dailymotioncom extends ElementFactory
{
  
  /**
   * URL_API: http://www.dailymotion.com/doc/api/obj-video.html
   * URL_TYPE: /video/xnqcwx_le-nazisme-dans-le-couple_fun#hp-v-v2 (c'est quoi cette url ^^ ?) 
   */
  public function retrieveDatas()
  {
    $url_clean = $this->getCleanedUrl();
    $ref_id = null;
    
    // Récupération de l'id
    if (preg_match("#(video\/)([a-zA-Z0-9]+)([a-zA-Z0-9_-]*)#", $url_clean, $preg_result))
    {
      $ref_id = $preg_result[2];
      $this->element->setData('ref_id', $ref_id);
    }
    
    // Récupération de données auprés de l'API
    if ($ref_id)
    {
      $api_url = curl_init('https://api.dailymotion.com/video/'.$ref_id
        .'&fields=thumbnail_medium_url');
      
      $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Content-type: application/json')
      );
      
      curl_setopt_array($api_url, $options);
      $api_result = json_decode(curl_exec($api_url));
      
      // On récupère l'url du thumbnail
      if (isset($api_result->thumbnail_medium_url))
      {
        $this->element->setData('thumb_medium_url', $api_result->thumbnail_medium_url);
      }
    }
  }
  
  public function proceedEmbedCode()
  {
    if (($ref_id = $this->element->getData('ref_id')))
    {
      $width = $this->container->getParameter('dailymotion_player_width');
      $height = $this->container->getParameter('dailymotion_player_height');
      $this->element->setEmbed(
        '<iframe frameborder="0" width="'.$width.'" height="'.$height.'" '
        .'src="http://www.dailymotion.com/embed/video/'.$ref_id.'"></iframe>'
      );
    }
  }
  
  public function proceedThumbnailUrl()
  {
    if (($thumb = $this->element->getData('thumb_medium_url')))
    {
      $this->element->setThumbnailUrl($thumb);
    }
  }
  
}