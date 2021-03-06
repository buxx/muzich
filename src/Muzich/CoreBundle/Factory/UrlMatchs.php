<?php

namespace Muzich\CoreBundle\Factory;

use Muzich\CoreBundle\Entity\Element;

class UrlMatchs
{
  /*
   * Si il y a un Element::TYPE_NOTMATCH le placer en début de tableau !
   */
  
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
    Element::TYPE_NOTMATCH => array(
      "#\/search\?q#" => null
    ),
    Element::TYPE_OTHER => array(
      // http://soundcloud.com/matas/sets/library-project
      "#^\/[a-zA-Z0-9_-]+\/sets\/[a-zA-Z0-9_-]+#" => null,
      // http://soundcloud.com/noisia/black-sun-empire-noisia-feed
      // http://soundcloud.com/user4818423/mechanika-crew-andrew-dj-set
      "#^\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+#" => null,
      // http://snd.sc/11CyOpN
      "#\/[a-zA-Z0-9_-]#" => null
    )
  );
  
  public static $dailymotion = array(
    Element::TYPE_OTHER => array(
      // http://dailymotion.com/video/xnqcwx_le-nazisme-dans-le-couple_fun#hp-v-v2
      "#\/(video\/)([a-zA-Z0-9]+)([a-zA-Z0-9_-]*)#" => 2
    )
  );
  
  public static $deezer = array(
    Element::TYPE_ALBUM => array(
      // http://www.deezer.com/fr/music/pantera/far-beyond-driven-80398
      "#^\/[a-zA-Z_-]+\/music\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+-([0-9]+)#" => 1,
      // http://www.deezer.com/fr/album/379324
      "#^\/[a-zA-Z_-]+\/album\/([0-9]+)#" => 1
     ),
    Element::TYPE_PLAYLIST => array(
      // http://www.deezer.com/fr/music/playlist/18701350
      "#^\/[a-zA-Z_-]+\/music\/playlist\/([0-9]+)#" => 1
    ),
    Element::TYPE_TRACK => array(
      // http://www.deezer.com/track/4067216
      "#^\/track\/([0-9]+)#" => 1,
      // http://www.deezer.com/fr/track/67238730
      "#^\/[a-zA-Z_-]+\/track/([0-9]+)#" => 1
    )
  );
  
  public static $spotify = array(
    Element::TYPE_TRACK => array(
      // http://open.spotify.com/track/7ylMdCOkqumPAwIMb6j2D5
      "#^\/track\/([a-zA-Z0-9]+)#" => 1
    ),
    Element::TYPE_ALBUM => array(
      // http://open.spotify.com/album/1VAB3Xn92dPKPWzocgQqkh
      "#^\/album\/([a-zA-Z0-9]+)#" => 1
    ),
    Element::TYPE_PLAYLIST => array(
      // http://open.spotify.com/user/bux/playlist/2kNeCiQaATbUi3lixhNwco
      "#^\/user\/([a-zA-Z0-9_-]+)\/playlist\/([a-zA-Z0-9]+)#" => 2
    )
  );
  
  public static $vimeo = array(
    Element::TYPE_OTHER => array(
      // http://vimeo.com/43258820
      "#\/([0-9]+)#" => 1
    )
  );
  
  public static $youtu = array(
    Element::TYPE_OTHER => array(
      // http://youtu.be/2-5xt9MrI9w
      "#\/([a-zA-Z0-9_-]+)#" => 1
    )
  );
  
  public static $youtube = array(
    Element::TYPE_OTHER => array(
      // https://www.youtube.com/watch?feature=player_detailpage&v=M9PkADawUKU#t=73s
      "#\/(watch|)(\?|)feature\=player_detailpage\&v=([a-zA-Z0-9_-]+)([.\w\W\d]*)#" => 3,
      // https://www.youtube.com/watch?v=2-5xt9MrI9w
      "#\/(watch|)(\?|)v=([a-zA-Z0-9_-]+)#" => 3,
      // http://m.youtube.com/watch?feature=youtu.be&v=QQ3L3mqP5JY&desktop_uri=%2Fwatch%3Fv%3DQQ3L3mqP5JY%26feature%3Dyoutu.be
      "#\/(watch|)(\?|)feature\=youtu.be\&v=([a-zA-Z0-9_-]+)([.\w\W\d]*)#" => 3,
      // https://www.youtube.com/watch?feature=player_embedded&v=PBoc0kuiEn0
      "#\/(watch|)(\?|)feature\=player_embedded\&v=([a-zA-Z0-9_-]+)([.\w\W\d]*)#" => 3,
    )
  );
  
  public static $mixcloud = array(
    Element::TYPE_TRACK => array(
      // http://www.mixcloud.com/nevrakse_ISM/nevrakse-tranceplantation/
      "#^\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+[\/]*#" => null,
    )
  );
}