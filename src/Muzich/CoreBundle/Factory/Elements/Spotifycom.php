<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\ElementFactory;
use Muzich\CoreBundle\Entity\Element;

/**
 * 
 *
 * @author bux
 */
class Spotifycom extends ElementFactory
{
  
  /**
   * 
    track http url: http://open.spotify.com/track/7ylMdCOkqumPAwIMb6j2D5
    track spotify uri: spotify:track:7ylMdCOkqumPAwIMb6j2D5

    album hhtp url: http://open.spotify.com/album/1VAB3Xn92dPKPWzocgQqkh
    album spotify uri: spotify:album:1VAB3Xn92dPKPWzocgQqkh

    playlist: open.spotify.com/user/bux/playlist/2kNeCiQaATbUi3lixhNwco
   */
  public function retrieveDatas()
  {
    $url_clean = $this->getCleanedUrl(false, 'open.spotify.com');
        
    $type   = null;
    $ref_id = null;
    $get_data_from_api = false;
    if (preg_match("#^\/track\/([a-zA-Z0-9]+)#", $url_clean, $chaines))
    {
      $type   = 'track';
      $ref_id = $chaines[1];
      $get_data_from_api = true;
    }
    if (preg_match("#^\/album\/([a-zA-Z0-9]+)#", $url_clean, $chaines))
    {
      $type   = 'album';
      $ref_id = $chaines[1];
      $get_data_from_api = true;
    }
    if (preg_match("#^\/user\/([a-zA-Z0-9_-]+)\/playlist\/([a-zA-Z0-9]+)#", $url_clean, $chaines))
    {
      $type   = 'playlist';
      $this->element->setData(Element::DATA_PLAYLIST_AUTHOR, $chaines[1]);
      $ref_id = $chaines[2];
      $get_data_from_api = false;
    }
    
    $this->element->setData(Element::DATA_TYPE  , $type);
    $this->element->setData(Element::DATA_REF_ID, $ref_id);
      
    if ($get_data_from_api)
    {
      $this->getDataFromApi($ref_id);
    }
  }
  
  protected function getDataFromApi($ref_id)
  {
    if ($this->element->getData(Element::DATA_TYPE) == 'track')
    {
      $data = $this->getJsonDataFromApiWithUrl('http://ws.spotify.com/lookup/1/.json?uri=spotify:track:'.$ref_id);
      
      if (array_key_exists('track', $data))
      {
        if (array_key_exists('available', $data['track']))
        {
          if ($data['track']['available'])
          {
            if (array_key_exists('artist', $data['track']))
            {
              $this->element->setData(Element::DATA_ARTIST, $data['track']['artist']);
            }
            if (array_key_exists('artists', $data['track']))
            {
              if (count($data['track']['artists']))
              {
                if (array_key_exists('name', $data['track']['artists'][0]))
                {
                  $this->element->setData(Element::DATA_ARTIST, $data['track']['artists'][0]['name']);
                }
              }
            }
            if (array_key_exists('name', $data['track']))
            {
              $this->element->setData(Element::DATA_TITLE, $data['track']['name']);
            }
          }
        }
      }
    }
    if ($this->element->getData(Element::DATA_TYPE) == 'album')
    {
      $data = $this->getJsonDataFromApiWithUrl('http://ws.spotify.com/lookup/1/.json?uri=spotify:album:'.$ref_id);
      
      if (array_key_exists('album', $data))
      {
        if (array_key_exists('artist', $data['album']))
        {
          $this->element->setData(Element::DATA_ARTIST, $data['album']['artist']);
        }
        if (array_key_exists('artists', $data['album']))
        {
          if (count($data['album']['artists']))
          {
            if (array_key_exists('name', $data['album']['artists'][0]))
            {
              $this->element->setData(Element::DATA_ARTIST, $data['album']['artists'][0]['name']);
            }
          }
        }
        if (array_key_exists('name', $data['album']))
        {
          $this->element->setData(Element::DATA_TITLE, $data['album']['name']);
        }
      }
    }
    
  }
  
  public function proceedEmbedCode()
  {
    if (($ref_id = $this->element->getData(Element::DATA_REF_ID)))
    {
      $uri = '';
      $type = $this->element->getData(Element::DATA_TYPE);
      
      if ($type == 'playlist')
      {
        $uri = 
          'spotify:user:'.$this->element->getData(Element::DATA_PLAYLIST_AUTHOR).':'
          .'playlist:'.$ref_id
        ;
      }
      if ($type == 'album')
      {
        $uri = 
          'spotify:album:'.$ref_id
        ;
      }
      if ($type == 'track')
      {
        $uri = 
          'spotify:track:'.$ref_id
        ;
      }
      
      $this->element->setEmbed(
        '<iframe src="https://embed.spotify.com/?uri='.$uri.'&theme=white" frameborder="0" allowtransparency="true"></iframe>');
    }
  }
  
}