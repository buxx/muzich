<?php

namespace Muzich\CoreBundle\ElementFactory\Site;

use Muzich\CoreBundle\ElementFactory\Site\base\BaseFactory;

/**
 * 
 *
 * @author bux
 */
class JamendoFactory extends BaseFactory
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
}

?>
