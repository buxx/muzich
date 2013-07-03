<?php

namespace Muzich\CoreBundle\Tests\Searcher;

use Muzich\CoreBundle\lib\UnitTest;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Managers\ElementManager;
use Doctrine\Common\Collections\ArrayCollection;

class ElementFactoryTest extends UnitTest
{  
  
  /**
   * Test du fonctionnement du l'usine
   * C'est plus un test fusible car il ne test que la méthode proceedFill.
   * 
   */
  public function testEngine()
  {
    $r = $this->getDoctrine();
    
    $bux = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bux')
    ;
    
    $hardtek = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
    
    $youtube_width = '590';
    $youtube_height = '300';
        
    $element = new Element();
    $element->setName('Mon bel element');
    $element->setTags(json_encode(array($hardtek->getId(), $tribe->getId())));
    $element->setUrl('http://www.youtube.com/watch?v=WC8qb_of04E');
    
    $factory = new ElementManager($element, $r->getEntityManager(), $this->_container);
    $factory->proceedFill($bux);
    
    $url = 'http://www.youtube.com/embed/WC8qb_of04E';
    
    $this->assertEquals($element->getName(), 'Mon bel element');
    $this->assertEquals($element->getUrl(), 'http://www.youtube.com/watch?v=WC8qb_of04E');
    $this->assertEquals($element->getTags(), array($hardtek, $tribe));
    $this->assertEquals($element->getEmbed(), 
      '<iframe width="'.$youtube_width.'" height="'.$youtube_height.'" src="'.$url.'" '
        .'frameborder="0" allowfullscreen></iframe>'
    );
    
    
  }
  
  /**
   * Test des création de code embed
   */
  public function testEmbedsEngine()
  {
    $r = $this->getDoctrine();
    $bux = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bux')
    ;
    $hardtek = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
    
    /*
     *   - youtube.com && youtu.be
     */
    $this->proceed_elementAndFill(
      $bux, 
      'dfd4z5d3s45sgf45645', 
      'http://www.youtube.com/watch?v=Itfg7UpkcSs&feature=g-vrec&context=G2e61726RVAAAAAAAAAg', 
      array($hardtek->getId(), $tribe->getId()), 
      '<iframe width="'.$this->getParam('youtube_player_width').'" '
        .'height="'.$this->getParam('youtube_player_height').'" '
        .'src="http://www.youtube.com/embed/Itfg7UpkcSs" '
        .'frameborder="0" allowfullscreen></iframe>'
    );
    $this->proceed_elementAndFill(
      $bux, 
      'dfd4z5d3s45sgf45645', 
      'http://www.youtube.com/watch?feature=player_detailpage&v=Itfg7UpkcSs#t=3s', 
      array($hardtek->getId(), $tribe->getId()), 
      '<iframe width="'.$this->getParam('youtube_player_width').'" '
        .'height="'.$this->getParam('youtube_player_height').'" '
        .'src="http://www.youtube.com/embed/Itfg7UpkcSs" '
        .'frameborder="0" allowfullscreen></iframe>'
    );
    $this->proceed_elementAndFill(
      $bux, 
      'dfd4z5d3s45sgf45645', 
      'http://youtu.be/Itfg7UpkcSs', 
      array($hardtek->getId(), $tribe->getId()), 
      '<iframe width="'.$this->getParam('youtube_player_width').'" '
        .'height="'.$this->getParam('youtube_player_height').'" '
        .'src="http://www.youtube.com/embed/Itfg7UpkcSs" '
        .'frameborder="0" allowfullscreen></iframe>'
    );
    $this->proceed_elementAndFill(
      $bux, 
      'dfd4z5d3s45sgf45645', 
      'http://www.youtube.com/watch?v=Itfg7UpkcSs', 
      array($hardtek->getId(), $tribe->getId()), 
      '<iframe width="'.$this->getParam('youtube_player_width').'" '
        .'height="'.$this->getParam('youtube_player_height').'" '
        .'src="http://www.youtube.com/embed/Itfg7UpkcSs" '
        .'frameborder="0" allowfullscreen></iframe>'
    );
    
    /*
     *   - dailymotion.com
     */
    $this->proceed_elementAndFill(
      $bux, 
      'fzgrj79ukl46ye4rgz6a', 
      'http://www.dailymotion.com/video/xafj1q_black-bomb-a-tales-from-the-old-sch_music', 
      array($hardtek->getId(), $tribe->getId()), 
      '<iframe frameborder="0" width="'.$this->getParam('dailymotion_player_width').'" '
        .'height="'.$this->getParam('dailymotion_player_height').'" '
        .'src="http://www.dailymotion.com/embed/video/xafj1q?autoPlay=1"></iframe>'
    );
    
    /*
     * - soundcloud.com
     */
    $url_id = 'http://soundcloud.com/matas/sets/library-project';
    $embed_id = md5($url_id);
    $this->proceed_elementAndFill(
      $bux, 
      'faez7tf8re9h4gf5j64dssz', 
      'http://soundcloud.com/matas/sets/library-project', 
      array($hardtek->getId(), $tribe->getId()), 
      '<object height="'.$this->getParam('soundcloud_player_height').'" width="100%" id="embed_'.$embed_id.'" '
          .'classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
          <param name="movie" value="http://player.soundcloud.com/player.swf?url='.$url_id.'&amp;'
          .'enable_api=true&amp;object_id=embed_'.$embed_id.'"></param>
          <param name="allowscriptaccess" value="always"></param>
          <embed allowscriptaccess="always" height="'.$this->getParam('soundcloud_player_height').'" '
          .'src="http://player.soundcloud.com/player.swf?url='.$url_id.'&amp;enable_api=true'
          .'&amp;object_id=embed_'.$embed_id.'" type="application/x-shockwave-flash" '
          .'width="100%" name="embed_'.$embed_id.'"></embed>
        </object>'
    );
    
    $url_id = 'http://soundcloud.com/matas/above-hyperion-redux';
    $embed_id = md5($url_id);
    $this->proceed_elementAndFill(
      $bux, 
      'faez7tf8re9h4gf5j64dssz', 
      'http://soundcloud.com/matas/above-hyperion-redux', 
      array($hardtek->getId(), $tribe->getId()), 
      
        '<object height="'.$this->getParam('soundcloud_player_height').'" width="100%" id="embed_'.$embed_id.'" '
          .'classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
          <param name="movie" value="http://player.soundcloud.com/player.swf?url='.$url_id.'&amp;'
          .'enable_api=true&amp;object_id=embed_'.$embed_id.'"></param>
          <param name="allowscriptaccess" value="always"></param>
          <embed allowscriptaccess="always" height="'.$this->getParam('soundcloud_player_height').'" '
          .'src="http://player.soundcloud.com/player.swf?url='.$url_id.'&amp;enable_api=true'
          .'&amp;object_id=embed_'.$embed_id.'" type="application/x-shockwave-flash" '
          .'width="100%" name="embed_'.$embed_id.'"></embed>
        </object>'
    );
        
    $this->proceed_elementAndFill(
      $bux, 
      'faez7tf8re9h4gf5j64dssz', 
      'http://soundcloud.com/tracks/search?q%5Bfulltext%5D=EEK+A+MOUSSE&q%5Btype%5D=&q%5Bduration%5D=', 
      array($hardtek->getId(), $tribe->getId()), 
      null
    );
    
    $this->proceed_elementAndFill(
      $bux, 
      'faez7tf8re9h4gf5j64dssz', 
      'http://soundcloud.com/people/search?q%5Bfulltext%5D=EEK+A+MOUSSE&q%5Btype%5D=&q%5Bduration%5D=', 
      array($hardtek->getId(), $tribe->getId()), 
      null
    );
    
    $this->proceed_elementAndFill(
      $bux, 
      'faez7tf8re9h4gf5j64dssz', 
      'http://soundcloud.com/groups/search?q%5Bfulltext%5D=EEK+A+MOUSSE&q%5Btype%5D=&q%5Bduration%5D=', 
      array($hardtek->getId(), $tribe->getId()), 
      null
    );
     
    /*
     *   - jamendo.com
     */
    
    $this->proceed_elementAndFill(
      $bux, 
      'gthyk456+liszz', 
      'http://www.jamendo.com/fr/album/30661', 
      array($hardtek->getId(), $tribe->getId()), 
      
        '<object width="'.$this->getParam('jamendo_player_width').'" height="'.$this->getParam('jamendo_player_height').'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"'
            .' codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" align="middle">
            <param name="allowScriptAccess" value="always" />
            <param name="wmode" value="transparent" />
            <param name="movie" value="http://widgets.jamendo.com/fr/album/?album_id=30661&playertype=2008" />
            <param name="quality" value="high" />
            <param name="bgcolor" value="#FFFFFF" />
            <embed src="http://widgets.jamendo.com/fr/album/?album_id=30661&playertype=2008" quality="high" wmode="transparent" bgcolor="#FFFFFF"'
            .' width="'.$this->getParam('jamendo_player_width').'" height="'.$this->getParam('jamendo_player_height').'" align="middle" allowScriptAccess="always"'
            .' type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
              &nbsp;
            </embed>
            &nbsp;
          </object>' 
    );
    
    $this->proceed_elementAndFill(
      $bux, 
      'gthyk456+liszz', 
      'http://www.jamendo.com/fr/track/207079', 
      array($hardtek->getId(), $tribe->getId()), 
      
      '<object width="'.$this->getParam('jamendo_player_width').'" height="'.$this->getParam('jamendo_player_height').'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"'
      .' codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" align="middle">
            <param name="allowScriptAccess" value="always" />
            <param name="wmode" value="transparent" />
            <param name="movie" value="http://widgets.jamendo.com/fr/track/?track_id=207079&playertype=2008" />
            <param name="quality" value="high" />
            <param name="bgcolor" value="#FFFFFF" />
            <embed src="http://widgets.jamendo.com/fr/track/?track_id=207079&playertype=2008" quality="high" wmode="transparent" bgcolor="#FFFFFF"'
      .' width="'.$this->getParam('jamendo_player_width').'" height="'.$this->getParam('jamendo_player_height').'" align="middle" allowScriptAccess="always"'
      .' type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
              &nbsp;
            </embed>
            &nbsp;
          </object>' 
    );

     
    $this->proceed_elementAndFill(
      $bux, 
      'gthyk456+liszz', 
      'http://www.jamendo.com/fr/search/all/psytrance', 
      array($hardtek->getId(), $tribe->getId()), 
      null
    );
    
    $this->proceed_elementAndFill(
      $bux, 
      'gthyk456+liszz', 
      'http://www.jamendo.com/fr/artist/DJ_BETO', 
      array($hardtek->getId(), $tribe->getId()), 
      null
    );
    
    /*/*
     *   - deezer.com
     */
    
    $this->proceed_elementAndFill(
      $bux, 
      'a9j4l56dsu8ra5gf647je', 
      'http://www.deezer.com/fr/music/pantera/far-beyond-driven-80398', 
      array($hardtek->getId(), $tribe->getId()), 
      '<iframe scrolling="no" frameborder="0" allowTransparency="true" '
        .'src="http://www.deezer.com/fr/plugins/player?autoplay=true&playlist=true'
        .'&width='.$this->getParam('deezer_player_width').'&height='
        .$this->getParam('deezer_player_height')
        .'&cover=true&type=album&id=80398&title=&app_id=undefined" '
        .'width="'.$this->getParam('deezer_player_width').'" height="'
        .$this->getParam('deezer_player_height')
        .'"></iframe>'
    );
    
    $this->proceed_elementAndFill(
      $bux, 
      'g4th4545ku6kti456e456z', 
      'http://www.deezer.com/fr/music/playlist/18701350', 
      array($hardtek->getId(), $tribe->getId()), 
      '<iframe scrolling="no" frameborder="0" allowTransparency="true" '
        .'src="http://www.deezer.com/fr/plugins/player?autoplay=true&playlist=true'
        .'&width='.$this->getParam('deezer_player_width').'&height='
        .$this->getParam('deezer_player_height')
        .'&cover=true&type=playlist&id=18701350&title=&app_id=undefined" '
        .'width="'.$this->getParam('deezer_player_width').'" height="'
        .$this->getParam('deezer_player_height')
        .'"></iframe>'
    );
    
  }
  
  public function testDataApiengine()
  {
    $r = $this->getDoctrine();
    $bux = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bux')
    ;
    
    /*
     *   - youtube.com && youtu.be
     */
    
    $this->assertEquals(array(
      'data_ref_id' => 'Itfg7UpkcSs',
      'data_title'  => 'DIDIER SUPER SUR FRANCE O : UN PETIT MALENTENDU ?',
      'data_type'   => 'other'
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://www.youtube.com/watch?v=Itfg7UpkcSs&feature=g-vrec&context=G2e61726RVAAAAAAAAAg'
    ));
    
    $this->assertEquals(array(
      'data_ref_id' => 'Itfg7UpkcSs',
      'data_title'  => 'DIDIER SUPER SUR FRANCE O : UN PETIT MALENTENDU ?',
      'data_type'   => 'other'
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://www.youtube.com/watch?feature=player_detailpage&v=Itfg7UpkcSs#t=3s'
    ));
    
    $this->assertEquals(array(
      'data_ref_id' => 'Itfg7UpkcSs',
      'data_title'  => 'DIDIER SUPER SUR FRANCE O : UN PETIT MALENTENDU ?',
      'data_type'   => 'other'
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://youtu.be/Itfg7UpkcSs'
    ));
    
    $this->assertEquals(array(
      'data_ref_id' => 'Itfg7UpkcSs',
      'data_title'  => 'DIDIER SUPER SUR FRANCE O : UN PETIT MALENTENDU ?',
      'data_type'   => 'other'
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://www.youtube.com/watch?v=Itfg7UpkcSs'
    ));
    
    
    /*
     *   - dailymotion.com
     */
    
      // 'http://www.dailymotion.com/video/xafj1q_black-bomb-a-tales-from-the-old-sch_music'
    $this->assertEquals(array(
      'data_ref_id' => 'xafj1q',
      'data_thumb_url'  => 'http://s1.dmcdn.net/kcSK/160x120-si6.jpg',
      'data_type' => 'other',
      'data_title' => 'Black Bomb A - Tales From The Old School',
      'data_tags' => array(
        0 => 'Metal'
      )
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://www.dailymotion.com/video/xafj1q_black-bomb-a-tales-from-the-old-sch_music'
    ));
    
    // http://www.dailymotion.com/video/x4om5b_punish-yourself-gimme-cocaine-live_music?search_algo=2
    $this->assertEquals(array(
      'data_ref_id' => 'x4om5b',
      'data_thumb_url'  => 'http://s1.dmcdn.net/sRiY/160x120-BYy.jpg',
      'data_type' => 'other',
      'data_title' => 'Punish yourself - gimme cocaine (live à nancy, azimut854)',
      'data_tags' => array(
        0 => 'Metal',
        1 => 'Electro'
      )
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://www.dailymotion.com/video/x4om5b_punish-yourself-gimme-cocaine-live_music?search_algo=2'
    ));
        
    /*
     * - soundcloud.com
     */
    
    // 'http://soundcloud.com/matas/sets/library-project'
    
    // On retire le test de "data_thumb_url", chez sound cloud ca arrete pas de 
    // changer en ce moment
    
    $datas = $this->proceed_element_datas_api(
      $bux, 
      'http://soundcloud.com/matas/sets/library-project'
    );
    
    $this->assertTrue(array_key_exists('data_thumb_url', $datas));
    
    if (array_key_exists('data_thumb_url', $datas))
    {
      unset($datas['data_thumb_url']);
    }
    
    $this->assertEquals(array(
      'data_ref_id' => 3770,
      'data_title' => 'Library Project',
      //'data_thumb_url' => 'http://i1.sndcdn.com/artworks-000000514203-fsvbcj-large.jpg?51826bf',
      'data_type' => 'playlist',
      'data_artist' => 'matas',
      'data_normalized_url' => 'http://api.soundcloud.com/playlists/3770',
      'data_tags' => array(0 => '')
    ),$datas);
    
    // Test des tags récupérés
    $datas = $this->proceed_element_datas_api(
      $bux, 
      'https://soundcloud.com/mixessss3/white-stripes-vs-led-zeppelin-icky-kinky-love-rock-mashup-dj-zebra'
    );
    
    $this->assertTrue(array_key_exists('data_thumb_url', $datas));
    if (array_key_exists('data_thumb_url', $datas))
    {
      unset($datas['data_thumb_url']);
    }
    
    $this->assertEquals(array(
      'data_ref_id' => 2215186,
      'data_title' => 'White Stripes Vs Led Zeppelin - Icky Kinky Love (Rock Mashup) DJ Zebra',
      //'data_thumb_url' => 'http://i1.sndcdn.com/artworks-000000514203-fsvbcj-large.jpg?51826bf',
      'data_type' => 'track',
      'data_artist' => 'Mixes and Mashups #3',
      'data_normalized_url' => 'http://api.soundcloud.com/tracks/2215186',
      'data_tags' => array(0 => 'Rock', 1 => 'rock  ')
    ),$datas);
    
    
    // 'http://soundcloud.com/matas/above-hyperion-redux'
    $datas = $this->proceed_element_datas_api(
      $bux, 
      'http://soundcloud.com/matas/above-hyperion-redux'
    );
    
    $this->assertTrue(array_key_exists('data_thumb_url', $datas));
    
    if (array_key_exists('data_thumb_url', $datas))
    {
      unset($datas['data_thumb_url']);
    }
    $this->assertEquals(array(
      'data_ref_id' => 3154252,
      'data_title' => 'Above Hyperion (redux)',
      //'data_thumb_url' => 'http://i1.sndcdn.com/artworks-000001536693-gb1n5v-large.jpg?51826bf',
      'data_type' => 'track',
      'data_artist' => 'matas',
      'data_tags' => array(
        0 => 'Spacestep'
      ),
      'data_normalized_url' => 'http://api.soundcloud.com/tracks/3154252'
    ),$datas);
    
    
    // https://soundcloud.com/sinkane/okay-africa-mixtape-2011#play
    $datas = $this->proceed_element_datas_api(
      $bux, 
      'https://soundcloud.com/sinkane/okay-africa-mixtape-2011#play'
    );
    
    $this->assertTrue(array_key_exists('data_thumb_url', $datas));
    
    if (array_key_exists('data_thumb_url', $datas))
    {
      unset($datas['data_thumb_url']);
    }
    
    $this->assertEquals(array(
      'data_ref_id' => 29186819,
      'data_title' => 'Okay Africa Mixtape 2011',
      //'data_thumb_url' => 'http://i1.sndcdn.com/artworks-000001536693-gb1n5v-large.jpg?51826bf',
      'data_type' => 'track',
      'data_artist' => 'Sinkane',
      'data_tags' => array(
        0 => null
      ),
      'data_normalized_url' => 'http://api.soundcloud.com/tracks/29186819',
    ),$datas);
    
    // 'http://soundcloud.com/tracks/search?q%5Bfulltext%5D=EEK+A+MOUSSE&q%5Btype%5D=&q%5Bduration%5D='
    
    $this->assertEquals(array(
      
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://soundcloud.com/tracks/search?q%5Bfulltext%5D=EEK+A+MOUSSE&q%5Btype%5D=&q%5Bduration%5D='
    ));
    
    //'http://soundcloud.com/people/search?q%5Bfulltext%5D=EEK+A+MOUSSE&q%5Btype%5D=&q%5Bduration%5D='
    
    $this->assertEquals(array(
      
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://soundcloud.com/people/search?q%5Bfulltext%5D=EEK+A+MOUSSE&q%5Btype%5D=&q%5Bduration%5D='
    ));
    
    // 'http://soundcloud.com/groups/search?q%5Bfulltext%5D=EEK+A+MOUSSE&q%5Btype%5D=&q%5Bduration%5D='
    
    $this->assertEquals(array(
      
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://soundcloud.com/groups/search?q%5Bfulltext%5D=EEK+A+MOUSSE&q%5Btype%5D=&q%5Bduration%5D='
    ));
    
    $datas = $this->proceed_element_datas_api(
      $bux, 
      'http://snd.sc/11CyOpN'
    );
    $this->assertTrue(array_key_exists('data_thumb_url', $datas));
    if (array_key_exists('data_thumb_url', $datas))
      unset($datas['data_thumb_url']);
    
    $this->assertEquals(array(
      'data_ref_id' => 90126814,
      'data_title' => 'The Test - WAKANTANKA #01 (Back to the originz)',
      'data_type' => 'track',
      'data_artist' => 'mgl32',
      'data_tags' => array(
        0 => 'Tribe',
        1 => 'Acid Tekno'
      ),
      'data_normalized_url' => 'http://api.soundcloud.com/tracks/90126814',
      'data_download' => true,
      'data_download_url' => 'http://api.soundcloud.com/tracks/90126814/download'
    ),$datas);
     
    /*
     *   - jamendo.com
     */
    
    // 'http://www.jamendo.com/fr/list/a120468/6-00-am'
    
    $this->assertEquals(array(
      'data_ref_id' => '120468',
      'data_title' => '6:00 AM',
      'data_type' => 'album',
      'data_thumb_url' => 'https://imgjam.com/albums/s120/120468/covers/1.100.jpg',
      'data_artist' => 'Azyd Azylum',
      'data_tags' => array(
        0 => 'Metal',
        1 => 'Hardcore',
        2 => 'Metalcore',
        3 => 'Azyd',
        4 => 'Azylum',
      ),
      'data_download' => true,
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://www.jamendo.com/fr/list/a120468/6-00-am'
    ));
    
    // 'http://www.jamendo.com/fr/track/207079'
    
    $this->assertEquals(array(
      'data_ref_id' => '207079',
      'data_title' => 'Insanity',
      'data_type' => 'track',
      'data_thumb_url' => 'https://imgjam.com/albums/s30/30661/covers/1.100.jpg',
      'data_artist' => 'Ptit lutin',
      'data_tags' => array(
        0 => 'Techno',
        1 => 'Hardtek'
      ),
      'data_download' => true,
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://www.jamendo.com/fr/track/207079'
    ));
    
    // 'http://www.jamendo.com/fr/search/all/psytrance'
    
    $this->assertEquals(array(
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://www.jamendo.com/fr/search/all/psytrance'
    ));
    
    // 'http://www.jamendo.com/fr/artist/DJ_BETO'
    
    $this->assertEquals(array(
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://www.jamendo.com/fr/artist/DJ_BETO'
    ));
    
    /*/*
     *   - deezer.com
     */
    
    // 'http://www.deezer.com/fr/music/pantera/far-beyond-driven-80398'
    
    $this->assertEquals(array(
      'data_ref_id' => '80398',
      'data_type' => 'album',
      'data_thumb_url' => 'http://api.deezer.com/2.0/album/80398/image',
      'data_title' => 'Far Beyond Driven',
      'data_artist' => 'Pantera'
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://www.deezer.com/fr/music/pantera/far-beyond-driven-80398'
    ));
    
    // 'http://www.deezer.com/fr/music/playlist/18701350'
    
    $this->assertEquals(array(
      'data_ref_id' => '18701350',
      'data_type'   => 'playlist',
      'data_title'  => 'Trucs Cools'
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://www.deezer.com/fr/music/playlist/18701350'
    ));
    
    /*
     * Vimeo
     *
     */
    
    $this->assertEquals(array(
      'data_ref_id' => '43258820',
      'data_title'  => 'Punish Yourself',
      'data_thumb_url' => 'http://b.vimeocdn.com/ts/301/282/301282081_200.jpg',
      'data_type' => 'other'
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://vimeo.com/43258820'
    ));
    
    /*
     * Spotify
     *
     */
    
    $this->assertEquals(array(
      'data_ref_id' => '1Uz3BDNxgLI0S6ACV7yXlT',
      'data_title'  => 'Narkotek Old School Tracks',
      'data_artist' => 'Guigoo Narkotek',
      'data_type'   => 'album'
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://open.spotify.com/album/1Uz3BDNxgLI0S6ACV7yXlT'
    ));
    
    $this->assertEquals(array(
      'data_ref_id' => '3d5FWJe19DkUJaO2wDEQHY',
      'data_title'  => 'Outta space',
      'data_artist' => 'Guigoo Narkotek',
      'data_type'   => 'track'
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://open.spotify.com/track/3d5FWJe19DkUJaO2wDEQHY'
    ));
    
  }
  
}