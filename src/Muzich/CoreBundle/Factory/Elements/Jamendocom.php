<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;

/**
 * 
 *
 * @author bux
 */
class Jamendocom extends ElementFactory
{
  
  /**
   *  ALBUM = http://www.jamendo.com/fr/album/30661
   *  TRACK = http://www.jamendo.com/fr/track/207079
   */
  public function retrieveDatas()
  {
    $url_clean = $this->getCleanedUrl();
    
    // album
    $type   = null;
    $ref_id = null;
    if (preg_match("#^\/[a-zA-Z0-9_-]+\/album\/([0-9]+)#", $url_clean, $chaines))
    {
      $type   = 'album';
      $ref_id = $chaines[1];
    }
    // track
    else if (preg_match("#^\/[a-zA-Z0-9_-]+\/track\/([0-9]+)#", $url_clean, $chaines))
    {
      $type = 'track';
      $ref_id = $chaines[1];
    }
    
    $this->element->setData('type'  , $type);
    $this->element->setData('ref_id', $ref_id);
    
    // Récupération de données avec l'API
    $api_url = null;
    switch ($type)
    {
      case 'album':
        $api_url = "http://api.jamendo.com/get2/image/album/json/?id=".$ref_id;
      break;
    
      /**
       * Lorsque l'on a une track, il faut récupérer les infos sur l'album dans laquelle
       * est la track
       */
      case 'track':
        $get_album_url = "http://www.jamendo.com/get/album/id/track/page/json/".$ref_id.'/';

        $ch = curl_init($get_album_url);
        curl_setopt_array($ch, array(
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_HTTPHEADER => array('Content-type: application/json')
        ));
        $result = json_decode(curl_exec($ch));
        if (count($result))
        {
          $album_url = str_replace('http://www.jamendo.com', '', $result[0]);

          $expl_alb = null;
          if (preg_match("#^\/album\/([0-9]+)#", $album_url, $expl_alb))
          {
            $id_album = $expl_alb[1];
            $api_url = "http://api.jamendo.com/get2/image/album/json/?id=".$id_album;
          }
        }
      break;
    }
    
    if ($api_url)
    {
      $ch = curl_init($api_url);
      $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Content-type: text/plain')
      );
      curl_setopt_array( $ch, $options );
      $result = json_decode(curl_exec($ch));
      
      if (count($result))
      {
        $this->element->setData('thumb_url', $result[0]);
      }
    }
  }
  
  public function proceedEmbedCode()
  {
    if (($ref_id = $this->element->getData('ref_id')) && ($type = $this->element->getData('type')))
    {
      $height = $this->container->getParameter('jamendo_player_height');
      $width = $this->container->getParameter('jamendo_player_width');
      $embed_url = "http://widgets.jamendo.com/fr/$type/?album_id=$ref_id&playertype=2008";
      $this->element->setEmbed(
        '<object width="'.$width.'" height="'.$height.'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"'
            .' codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" align="middle">
            <param name="allowScriptAccess" value="always" />
            <param name="wmode" value="transparent" />
            <param name="movie" value="'.$embed_url.'" />
            <param name="quality" value="high" />
            <param name="bgcolor" value="#FFFFFF" />
            <embed src="'.$embed_url.'" quality="high" wmode="transparent" bgcolor="#FFFFFF"'
            .' width="'.$width.'" height="'.$height.'" align="middle" allowScriptAccess="always"'
            .' type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
              &nbsp;
            </embed>
            &nbsp;
          </object>'  
      );
    }
  }
  
  public function proceedThumbnailUrl()
  {
    if (($thumb = $this->element->getData('thumb_url')))
    {
      $this->element->setThumbnailUrl($thumb);
    }
  }
  
}
