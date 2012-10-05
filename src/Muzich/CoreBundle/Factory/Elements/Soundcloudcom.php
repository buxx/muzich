<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;
use Muzich\CoreBundle\Entity\Element;

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
    
    
    //$this->element->setData(Element::DATA_REF_ID, $this->element->getUrl());
    
    // récupération de données avec l'API
    if ($match)
    {
      // La première étape consiste a résoudre l'url
      $ch = curl_init('http://api.soundcloud.com/resolve.json?url='.$this->element->getUrl().'&client_id=39946ea18e3d78d64c0ac95a025794e1');

      $options = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Content-type: application/json')
      );

      curl_setopt_array( $ch, $options );
      $result = json_decode(curl_exec($ch));

      if (isset($result->errors))
      {
        if (count($result->errors))
        {
          return null;
        }
      }

      $getjsonurl = $result->location;
      // On a maintenant la bonne url pour demander les infos
      $ch = curl_init($getjsonurl);
      curl_setopt_array($ch, $options);
      $result = json_decode(curl_exec($ch), true);

      /*
       * array
          'kind' => string 'track' (length=5)
          'id' => int 57452080
          'created_at' => string '2012/08/24 20:39:44 +0000' (length=25)
          'user_id' => int 11235441
          'duration' => int 4206558
          'commentable' => boolean true
          'state' => string 'finished' (length=8)
          'original_content_size' => int 168196212
          'sharing' => string 'public' (length=6)
          'tag_list' => string '' (length=0)
          'permalink' => string 'mechanika-crew-andrew-dj-set' (length=28)
          'streamable' => boolean true
          'embeddable_by' => string 'all' (length=3)
          'downloadable' => boolean true
          'purchase_url' => null
          'label_id' => null
          'purchase_title' => null
          'genre' => string '' (length=0)
          'title' => string 'MECHANIKA CREW / ANDREW dj set 24.08.12' (length=39)
          'description' => string '' (length=0)
          'label_name' => string '' (length=0)
          'release' => string '' (length=0)
          'track_type' => string '' (length=0)
          'key_signature' => string '' (length=0)
          'isrc' => string '' (length=0)
          'video_url' => null
          'bpm' => null
          'release_year' => null
          'release_month' => null
          'release_day' => null
          'original_format' => string 'mp3' (length=3)
          'license' => string 'all-rights-reserved' (length=19)
          'uri' => string 'http://api.soundcloud.com/tracks/57452080' (length=41)
          'user' => 
            array
              'id' => int 11235441
              'kind' => string 'user' (length=4)
              'permalink' => string 'user4818423' (length=11)
              'username' => string 'Andrea Andrew mechanika' (length=23)
              'uri' => string 'http://api.soundcloud.com/users/11235441' (length=40)
              'permalink_url' => string 'http://soundcloud.com/user4818423' (length=33)
              'avatar_url' => string 'http://i1.sndcdn.com/avatars-000023343399-cp1lvg-large.jpg?04ad178' (length=66)
          'permalink_url' => string 'http://soundcloud.com/user4818423/mechanika-crew-andrew-dj-set' (length=62)
          'artwork_url' => string 'http://i1.sndcdn.com/artworks-000029057120-6fz4k4-large.jpg?04ad178' (length=67)
          'waveform_url' => string 'http://w1.sndcdn.com/udItSnzA5J22_m.png' (length=39)
          'stream_url' => string 'http://api.soundcloud.com/tracks/57452080/stream' (length=48)
          'download_url' => string 'http://api.soundcloud.com/tracks/57452080/download' (length=50)
          'playback_count' => int 502
          'download_count' => int 85
          'favoritings_count' => int 12
          'comment_count' => int 13
          'attachments_uri' => string 'http://api.soundcloud.com/tracks/57452080/attachments' (length=53)

       */
      
      // En premier lieux il nous faut être sur d'avoir le droit d'utiliser le lecteur exportable
      $sharing = false;
      if (array_key_exists('sharing', $result) && array_key_exists('embeddable_by', $result))
      {
        if ($result['sharing'] == 'public' && ($result['embeddable_by'] == 'all' || $result['embeddable_by'] == 'me'))
        {
          $sharing = true;
        }
      }
      
      if ($sharing)
      {
        if (array_key_exists('id', $result) )
        {
          $this->element->setData(Element::DATA_REF_ID, $result['id']);
        }

        if (array_key_exists('artwork_url', $result) )
        {
          $this->element->setData(Element::DATA_THUMB_URL, $result['artwork_url']);
        }

        if (array_key_exists('kind', $result) )
        {
          $this->element->setData(Element::DATA_TYPE, $result['kind']);
        }

        if (array_key_exists('downloadable', $result) )
        {
          $this->element->setData(Element::DATA_DOWNLOAD, $result['downloadable']);
          // FIXME
          $this->element->setData(Element::DATA_DOWNLOAD_URL, $this->element->getUrl().'/download');
        }

        if (array_key_exists('title', $result) )
        {
          $this->element->setData(Element::DATA_TITLE, $result['title']);
        }

        if (array_key_exists('user', $result) )
        {
          $this->element->setData(Element::DATA_ARTIST, $result['user']['username']);
        }

        if (array_key_exists('genre', $result) )
        {
          if (strlen($result['genre']))
          {
            $this->element->setData(Element::DATA_TAGS, array($result['genre']));
          }
        }
      }
            
    }
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