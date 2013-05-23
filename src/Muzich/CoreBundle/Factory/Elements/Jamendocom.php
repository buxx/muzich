<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Factory\UrlMatchs;

class Jamendocom extends ElementFactory
{
  
  public function __construct(Element $element, Container $container, EntityManager $entity_manager)
  {
    parent::__construct($element, $container, $entity_manager);
    $this->url_matchs = UrlMatchs::$jamendo;
  }
  
  public function getStreamData()
  {
    $ref_id = $this->element->getData(Element::DATA_REF_ID);
    $api_url = "http://api.jamendo.com/get2/name+stream/track/json/?". $this->element->getData(Element::DATA_TYPE)."_id=".$ref_id;
    
    if ($ref_id)
    {
      $response = $this->getApiConnector()->getResponseForUrl($api_url);
      if (count($response->getContent()))
      {
        $data_return = array();
        foreach ($result as $song)
        {
          $data_return[] = array(
            'name' => $song['name'],
            'url'  => $song['stream'],
          );
        }
        return $data_return;
      }
    }
  }
  
  public function retrieveDatas()
  {
    $type = $this->element->getData(Element::DATA_TYPE);
    $ref_id = $this->element->getData(Element::DATA_REF_ID);
    
    // Récupération de données avec l'API
    $api_url = null;
    switch ($type)
    {
      case Element::TYPE_ALBUM:
        $api_url = "http://api.jamendo.com/get2/"
          ."id+name+url+image+artist_name+artist_url/album/json/?album_id=".$ref_id;
        $api_tag_url = "http://api.jamendo.com/get2/name+weight/tag/json/album_tag/?album_id=".$ref_id;
      break;
    
      case Element::TYPE_TRACK:
        $api_url = "http://api.jamendo.com/get2/"
          ."id+name+url+image+artist_name+artist_url+track_name/album/json/?track_id=".$ref_id;
        $api_tag_url = "http://api.jamendo.com/get2/name+weight/tag/json/track_tag/?track_id=".$ref_id;
      break;
    }
    
    if ($api_url)
    {
      if (($response = $this->getApiConnector()->getResponseForUrl($api_url)))
      {
        // Check si tout se passe bien si pas de retour de l'api
        $this->getApiConnector()->setElementDatasWithResponse($response, array(
          Element::DATA_THUMB_URL      => array(0 => 'image'),
          Element::DATA_ARTIST         => array(0 => 'artist_name'),
        ));
        
        if ($this->url_analyzer->getType() == Element::TYPE_ALBUM)
        {
          $this->element->setData(Element::DATA_TITLE, $response->get(array(0 => 'name')));
        }
        if ($this->url_analyzer->getType() == Element::TYPE_TRACK)
        {
          $this->element->setData(Element::DATA_TITLE, $response->get(array(0 => 'track_name')));
        }
        
        if (($response = $this->getApiConnector()->getResponseForUrl($api_url)))
        {
          // TODO: Check si tout ce passe bien avec pas de tags en retour de l'api
          $tags = array();
          foreach ($result->getContent() as $tag)
          {
            $tags[] = $tag['name'];
          }
          
          $this->element->setData(Element::DATA_TAGS, $tags);
        }
      }
    }
    
    // Un contenu jamendo est toujours téléchargeable
    $this->element->setData(Element::DATA_DOWNLOAD, true);
  }
  
  public function proceedEmbedCode()
  {
    if (($ref_id = $this->element->getData(Element::DATA_REF_ID)) 
      && ($type = $this->element->getData(Element::DATA_TYPE)))
    {
      $height = $this->container->getParameter('jamendo_player_height');
      $width = $this->container->getParameter('jamendo_player_width');
      $embed_url = "http://widgets.jamendo.com/fr/$type/?".$type."_id=$ref_id&playertype=2008";
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
    if (($thumb = $this->element->getData(Element::DATA_THUMB_URL)))
    {
      $this->element->setThumbnailUrl($thumb);
    }
  }
  
}
