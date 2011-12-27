<?php

namespace Muzich\CoreBundle\ElementFactory\Site;

use Muzich\CoreBundle\ElementFactory\Site\base\BaseFactory;
use Muzich\CoreBundle\Entity\Element;
use Symfony\Component\DependencyInjection\Container;

/**
 * 
 *
 * @author bux
 */
class SoundCloudFactory extends BaseFactory
{
  public function getEmbedCode()
  {
    $url = str_replace('www.', '', $this->element->getUrl());
    $data = str_replace('http://soundcloud.com', '', $url);
    
    // http://soundcloud.com/matas/sets/library-project
    if (preg_match("#^\/[a-zA-Z0-9_-]+\/sets\/[a-zA-Z0-9_-]+#", $data)
    // http://soundcloud.com/matas/anadrakonic-waltz
         || preg_match("#^\/[a-zA-Z0-9_]+\/[a-zA-Z0-9_]+#", $data))
    {
      // l'url est valide pour l'api javascript que l'on utilise
      
      $height = $this->container->getParameter('soundcloud_player_height');
      $embed = 
        '<object height="'.$height.'" width="100%" id="embed_'.$this->element->getId().'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
          <param name="movie" value="http://player.soundcloud.com/player.swf?url='.$url.'&amp;enable_api=true&amp;object_id=embed_'.$this->element->getId().'"></param>
          <param name="allowscriptaccess" value="always"></param>
          <embed allowscriptaccess="always" height="'.$height.'" src="http://player.soundcloud.com/player.swf?url='.$url.'&amp;enable_api=true&amp;object_id=embed_'.$this->element->getId().'" type="application/x-shockwave-flash" width="100%" name="embed_'.$this->element->getId().'"></embed>
        </object>
        ';
      
      return $embed;
    }
    
    return null;
  }
}

?>
