<?php

namespace Muzich\CoreBundle\ElementFactory\Site;

use Muzich\CoreBundle\ElementFactory\Site\base\BaseFactory;

/**
 * 
 *
 * @author bux
 */
class JamendocomFactory extends BaseFactory
{
  public function getEmbedCode()
  {
    $url = str_replace('www.', '', $this->element->getUrl());
    $data = str_replace('http://jamendo.com', '', $url);
    
    $embed_url = null;
    // http://www.jamendo.com/fr/album/30661
    if (preg_match("#^\/[a-zA-Z0-9_-]+\/album\/([0-9]+)#", $data, $chaines))
    {
      $id_album = $chaines[1];
      $embed_url = "http://widgets.jamendo.com/fr/album/?album_id=$id_album&playertype=2008";
    }
    // http://www.jamendo.com/fr/track/207079
    else if (preg_match("#^\/[a-zA-Z0-9_-]+\/track\/([0-9]+)#", $data, $chaines))
    {
      $id_track = $chaines[1];
      $embed_url = "http://widgets.jamendo.com/fr/track/?playertype=2008&track_id=$id_track";
    }
    
    if ($embed_url)
    {
      $height = $this->container->getParameter('jamendo_player_height');
      $width = $this->container->getParameter('jamendo_player_width');
      return  '
          <object width="'.$width.'" height="'.$height.'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" align="middle">
            <param name="allowScriptAccess" value="always" />
            <param name="wmode" value="transparent" />
            <param name="movie" value="'.$embed_url.'" />
            <param name="quality" value="high" />
            <param name="bgcolor" value="#FFFFFF" />
            <embed src="'.$embed_url.'" quality="high" wmode="transparent" bgcolor="#FFFFFF" width="'.$width.'" height="'.$height.'" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
              &nbsp;
            </embed>
            &nbsp;
          </object>
          <a href="http://pro.jamendo.com/" style="display:block;font-size:8px !important;">Catalogue professionnel de musique libre</a>
        ';
    }
    
    return null;
  }
  
  public function getThumbnailUrl()
  {
    $url_object = $this->getCleanedUrl();
    $get_url = null;
    $url = null;
    
    // http://www.jamendo.com/fr/album/30661
    if (preg_match("#^\/[a-zA-Z0-9_-]+\/album\/([0-9]+)#", $url_object, $chaines))
    {
      $id_album = $chaines[1];
      $get_url = "http://api.jamendo.com/get2/image/album/json/?id=".$id_album;
    }
    // http://www.jamendo.com/fr/track/207079
    else if (preg_match("#^\/[a-zA-Z0-9_-]+\/track\/([0-9]+)#", $url_object, $chaines))
    {
      $id_track = $chaines[1];
      $get_url = "http://api.jamendo.com/get2/image/track/json/?id=".$id_track;
    }
    
    if ($get_url)
    {
      $ch = curl_init($get_url);
      $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Content-type: application/json')
      );
      curl_setopt_array( $ch, $options );
      $result = json_decode(curl_exec($ch));
      
      if (count($result))
      {
        $url = $result[0];
      }
    }
    
      
    

    
    
//    if (isset($result->errors))
//    {
//      if (count($result->errors))
//      {
//        return null;
//      }
//    }
//    
//    $getjsonurl = $result->location;
//    $ch = curl_init($getjsonurl);
//    curl_setopt_array($ch, $options);
//    $result = json_decode(curl_exec($ch));
//    
//    $url = $result->artwork_url;
    
    
    return $url;
  }
  
  //http://api.jamendo.com/get2/name+url/album/json/?id=116
}

?>
