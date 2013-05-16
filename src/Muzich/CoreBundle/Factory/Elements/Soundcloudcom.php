<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Util\TagLike;
use Muzich\CoreBundle\lib\Api\Response as ApiResponse;

/**
 * 
 *
 * @author bux
 */
class Soundcloudcom extends ElementFactory
{  
  /**
   * ??SET = http://soundcloud.com/matas/sets/library-project
   * ??    = http://soundcloud.com/matas/anadrakonic-waltz
   */
  public function retrieveDatas()
  {
    $url_clean = $this->getCleanedUrl();
    
    $match = false;
    //
    if (preg_match("#^\/[a-zA-Z0-9_-]+\/sets\/[a-zA-Z0-9_-]+#", $url_clean, $chaines))
    {
      $match = true;
    }
    // /noisia/black-sun-empire-noisia-feed
    // /user4818423/mechanika-crew-andrew-dj-set
    else if (preg_match("#^\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+#", $url_clean, $chaines))
    {
      $match = true;
    }
    
    // On en gère pas encore les recherches
    if (preg_match("#\/search\?q#", $url_clean, $chaines))
    {
      $match = false;
    }
    
    /////////////
    if ($match)
    {
      $this->setElementDatasWithApi();
    }
    
    ////$this->element->setData(Element::DATA_REF_ID, $this->element->getUrl());
    //
    //// récupération de données avec l'API
    //if ($match)
    //{
    //  
    //  
    //  
    //  
    //  
    //  // La première étape consiste a résoudre l'url
    //  $ch = curl_init('http://api.soundcloud.com/resolve.json?url='.$this->element->getUrl().'&client_id=39946ea18e3d78d64c0ac95a025794e1');
    //
    //  $options = array(
    //    CURLOPT_RETURNTRANSFER => true,
    //    CURLOPT_HTTPHEADER => array('Content-type: application/json')
    //  );
    //
    //  curl_setopt_array( $ch, $options );
    //  $result = json_decode(curl_exec($ch));
    //
    //  if (isset($result->errors))
    //  {
    //    if (count($result->errors))
    //    {
    //      return;  
    //    }
    //  }
    //  
    //  if (!isset($result->location))
    //  {
    //    return;
    //  }
    //  
    //  if (!$result->location)
    //  {
    //    return;
    //  }
    //  
    //  $getjsonurl = $result->location;
    //  // On a maintenant la bonne url pour demander les infos
    //  $ch = curl_init($getjsonurl);
    //  curl_setopt_array($ch, $options);
    //  $result = json_decode(curl_exec($ch), true);
    //  
    //  // En premier lieux il nous faut être sur d'avoir le droit d'utiliser le lecteur exportable
    //  $sharing = false;
    //  if (array_key_exists('sharing', $result) && array_key_exists('embeddable_by', $result))
    //  {
    //    if ($result['sharing'] == 'public' && ($result['embeddable_by'] == 'all' || $result['embeddable_by'] == 'me'))
    //    {
    //      $sharing = true;
    //    }
    //  }
    //  
    //  if ($sharing)
    //  {
    //    if (array_key_exists('id', $result) )
    //    {
    //      $this->element->setData(Element::DATA_REF_ID, $result['id']);
    //    }
    //    
    //    if (array_key_exists('uri', $result) )
    //    {
    //      $this->element->setData(Element::DATA_NORMALIZED_URL, $result['uri']);
    //    }
    //
    //    if (array_key_exists('artwork_url', $result) )
    //    {
    //      if ($result['artwork_url'])
    //      {
    //        $this->element->setData(Element::DATA_THUMB_URL, $result['artwork_url']);
    //      }
    //      else
    //      {
    //        if (array_key_exists('user', $result) )
    //        {
    //          if (array_key_exists('avatar_url', $result['user']) )
    //          {
    //            if ($result['user']['avatar_url'])
    //            {
    //              $this->element->setData(Element::DATA_THUMB_URL, $result['user']['avatar_url']);
    //            }
    //          }
    //        }
    //      }
    //    }
    //    
    //    if (array_key_exists('kind', $result) )
    //    {
    //      $this->element->setData(Element::DATA_TYPE, $result['kind']);
    //    }
    //
    //    if (array_key_exists('downloadable', $result) )
    //    {
    //      $this->element->setData(Element::DATA_DOWNLOAD, $result['downloadable']);
    //      // FIXME
    //      $this->element->setData(Element::DATA_DOWNLOAD_URL, $this->element->getUrl().'/download');
    //    }
    //
    //    if (array_key_exists('title', $result) )
    //    {
    //      $this->element->setData(Element::DATA_TITLE, $result['title']);
    //    }
    //
    //    if (array_key_exists('user', $result) )
    //    {
    //      $this->element->setData(Element::DATA_ARTIST, $result['user']['username']);
    //    }
    //    
    //    $genres = '';
    //    if (array_key_exists('genre', $result) )
    //    {
    //      if (strlen($result['genre']))
    //      {
    //        $genres = $result['genre'];
    //      }
    //    }
    //    
    //    $tags_list = '';
    //    if (array_key_exists('tag_list', $result) )
    //    {
    //      if (strlen($result['tag_list']))
    //      {
    //        $tags_list = $result['tag_list'];
    //      }
    //    }
    //    
    //    $tags_string = $genres.' '.$tags_list.' '.str_replace(' ', '-', $genres);
    //    $tags_like = array();
    //    if (strlen($tags_string))
    //    {
    //      $tag_like = new TagLike($this->entity_manager);
    //      foreach (explode(' ', $tags_string) as $word)
    //      {
    //        $similar_tags = $tag_like->getSimilarTags($word, ($this->element->getOwner())?$this->element->getOwner()->getId():null);
    //        if (count($similar_tags))
    //        {
    //          if ($similar_tags['same_found'])
    //          {
    //            $tags_like[] = $similar_tags['tags'][0]['name'];
    //          }
    //        }
    //      }
    //      $tags_like[] = $genres;
    //      if (count($tags_like))
    //      {
    //        $this->element->setData(Element::DATA_TAGS, array_unique($tags_like));
    //      }
    //    }
    //    
    //  }
    //}
  }
  
  protected function setElementDatasWithApi()
  {
    if (($response = $this->getApiDatasResponse()))
    {
      if ($response->get('sharing') == 'public')
      {
        $this->setElementSharingData($response);
        $this->setTagsData($response);
      }
      
      if ($response->get('embeddable_by') == 'all')
      {
        $this->setElementEmbeddableData($response);
      }
    }
  }
  
  protected function getApiDatasResponse()
  {
    if (($response = $this->getApiConnector()->getResponseForUrl('http://api.soundcloud.com/resolve.json?url='.$this->element->getUrl().'&client_id=39946ea18e3d78d64c0ac95a025794e1')))
    {
      if ($response->haveNot('errors') && $response->have('location'))
      {
        return $this->getApiConnector()->getResponseForUrl($response->get('location'));
      }
    }
    
    return null;
  }
  
  protected function setElementSharingData(ApiResponse $response)
  {
    $this->getApiConnector()->setElementDatasWithResponse($response, array(
      Element::DATA_NORMALIZED_URL => 'uri',
      Element::DATA_TYPE           => 'kind',
      Element::DATA_DOWNLOAD       => 'downloadable',
      Element::DATA_DOWNLOAD_URL   => 'download_url',
      Element::DATA_TITLE          => 'title',
      Element::DATA_ARTIST         => array('user' => 'username')
    ));
    
    $this->setThumbnailData($response);
  }
  
  protected function setThumbnailData(ApiResponse $response)
  {
    if ($response->have('artwork_url'))
    {
      $this->element->setData(Element::DATA_THUMB_URL, $response->get('artwork_url'));
    }
    elseif ($response->have(array('user' => 'avatar_url')))
    {
      $this->element->setData(Element::DATA_THUMB_URL, $response->get(array('user' => 'avatar_url')));
    }
  }
  
  protected function setTagsData(ApiResponse $response)
  {
    $tags_string = $response->get('genre').' '.$response->get('tag_list').' '.str_replace(' ', '-', $response->get('genre'));
    $tags_like = array();
    if (strlen($tags_string))
    {
      $tag_like = new TagLike($this->entity_manager);
      foreach (explode(' ', $tags_string) as $word)
      {
        $similar_tags = $tag_like->getSimilarTags($word, ($this->element->getOwner())?$this->element->getOwner()->getId():null);
        if (count($similar_tags))
        {
          if ($similar_tags['same_found'])
          {
            $tags_like[] = $similar_tags['tags'][0]['name'];
          }
        }
      }
      $tags_like[] = $response->get('genre');
      if (count($tags_like))
      {
        $this->element->setData(Element::DATA_TAGS, array_unique($tags_like));
      }
    }
  }
  
  protected function setElementEmbeddableData($response)
  {
    $this->getApiConnector()->setElementDatasWithResponse($response, array(
      Element::DATA_REF_ID => 'id'
    ));
  }
  
  public function proceedEmbedCode()
  {
    if (($ref_id = $this->element->getData(Element::DATA_REF_ID)) 
      && ($this->element->getData(Element::DATA_TYPE) == 'track' || $this->element->getData(Element::DATA_TYPE) == 'playlist' ))
    {
      $ref_id = $this->element->getUrl();
      $embed_id = md5($ref_id);
      $height = $this->container->getParameter('soundcloud_player_height');
      $this->element->setEmbed(
        '<object height="'.$height.'" width="100%" id="embed_'.$embed_id.'" '
          .'classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
          <param name="movie" value="http://player.soundcloud.com/player.swf?url='.$ref_id.'&amp;'
          .'enable_api=true&amp;object_id=embed_'.$embed_id.'"></param>
          <param name="allowscriptaccess" value="always"></param>
          <embed allowscriptaccess="always" height="'.$height.'" '
          .'src="http://player.soundcloud.com/player.swf?url='.$ref_id.'&amp;enable_api=true'
          .'&amp;object_id=embed_'.$embed_id.'" type="application/x-shockwave-flash" '
          .'width="100%" name="embed_'.$embed_id.'"></embed>
        </object>'
      );
//      $this->element->setEmbed(
//        '<iframe id="sc-widget_'.$this->element->getData(Element::DATA_REF_ID).
//          '" src="http://w.soundcloud.com/player/?url='.
//          $ref_id.'" width="100%" '.
//          'height="'.$height.'" scrolling="no" frameborder="no"></iframe>'
//      );
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