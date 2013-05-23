<?php

namespace Muzich\CoreBundle\Factory;

use Muzich\CoreBundle\Entity\Element;

class UrlMatchs
{
  public static $jamendo = array(
    Element::TYPE_TRACK => array(
      // http://www.jamendo.com/fr/track/894974
      "#^\/[a-zA-Z0-9_-]+\/track\/([0-9]+)#" => 1,
      // http://www.jamendo.com/fr/track/347602/come-come
      "#^\/[a-zA-Z0-9_-]+\/track\/([0-9]+)\/.#" => 1
    ),
    Element::TYPE_ALBUM => array(
      // http://www.jamendo.com/fr/album/3409
      "#^\/[a-zA-Z0-9_-]+\/album\/([0-9]+)#" => 1,
      // http://www.jamendo.com/fr/list/a45666/proceed-positron...
      "#^\/[a-zA-Z0-9_-]+\/list\/a([0-9]+)\/.#" => 1
    )
  );
  
  public static $soundcloud = array(
    Element::TYPE_OTHER => array(
      // http://soundcloud.com/matas/sets/library-project
      "#^\/[a-zA-Z0-9_-]+\/sets\/[a-zA-Z0-9_-]+#" => null,
      // http://soundcloud.com/noisia/black-sun-empire-noisia-feed
      // http://soundcloud.com/user4818423/mechanika-crew-andrew-dj-set
      "#^\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+#" => null
    )
  );
}