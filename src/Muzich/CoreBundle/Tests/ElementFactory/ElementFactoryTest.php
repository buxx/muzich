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
        .'&cover=true&btn_popup=true&type=album&id=80398&title=" '
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
        .'&cover=true&btn_popup=true&type=playlist&id=18701350&title=" '
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
      'data_title'  => 'DIDIER SUPER SUR FRANCE O : UN PETIT MALENTENDU ?'
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://www.youtube.com/watch?v=Itfg7UpkcSs&feature=g-vrec&context=G2e61726RVAAAAAAAAAg'
    ));
    
    $this->assertEquals(array(
      'data_ref_id' => 'Itfg7UpkcSs',
      'data_title'  => 'DIDIER SUPER SUR FRANCE O : UN PETIT MALENTENDU ?'
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://www.youtube.com/watch?feature=player_detailpage&v=Itfg7UpkcSs#t=3s'
    ));
    
    $this->assertEquals(array(
      'data_ref_id' => 'Itfg7UpkcSs',
      'data_title'  => 'DIDIER SUPER SUR FRANCE O : UN PETIT MALENTENDU ?'
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://youtu.be/Itfg7UpkcSs'
    ));
    
    $this->assertEquals(array(
      'data_ref_id' => 'Itfg7UpkcSs',
      'data_title'  => 'DIDIER SUPER SUR FRANCE O : UN PETIT MALENTENDU ?'
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://www.youtube.com/watch?v=Itfg7UpkcSs'
    ));
    
    
    /*
     *   - dailymotion.com
     */
    
      // 'http://www.dailymotion.com/video/xafj1q_black-bomb-a-tales-from-the-old-sch_music', 
    
      // TODO: l'url est pas toujours la même pour le thumb :/
//    $this->assertEquals(array(
//      'data_ref_id' => 'xafj1q',
//      'data_thumb_url'  => 'http://static2.dmcdn.net/static/video/686/025/17520686:jpeg_preview_medium.jpg?20110820212502'
//    ),$this->proceed_element_datas_api(
//      $bux, 
//      'http://www.dailymotion.com/video/xafj1q_black-bomb-a-tales-from-the-old-sch_music'
//    ));
    
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
      'data_download' => null,
      'data_download_url' => 'http://soundcloud.com/matas/sets/library-project/download',
      'data_artist' => 'matas',
      'data_normalized_url' => 'http://api.soundcloud.com/playlists/3770'
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
      'data_download' => false,
      'data_download_url' => 'http://soundcloud.com/matas/above-hyperion-redux/download',
      'data_artist' => 'matas',
      'data_tags' => array(
        0 => 'Spacestep'
      ),
      'data_normalized_url' => 'http://api.soundcloud.com/tracks/3154252'
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
     
    /*
     *   - jamendo.com
     */
    
    // 'http://www.jamendo.com/fr/album/30661'
    
    $this->assertEquals(array(
      'data_ref_id' => '30661',
      'data_title' => 'ZwaNe 01',
      'data_type' => 'album',
      'data_thumb_url' => 'http://imgjam.com/albums/s30/30661/covers/1.100.jpg',
      'data_artist' => 'Ptit lutin',
      'data_tags' => array(
        0 => 'Basse',
        1 => 'Batterie',
        2 => 'Hardtek',
        3 => 'Tek',
        4 => 'Hardtechno',
      ),
      'data_download' => true,
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://www.jamendo.com/fr/album/30661'
    ));
    
    // 'http://www.jamendo.com/fr/track/207079'
    
    $this->assertEquals(array(
      'data_ref_id' => '207079',
      'data_title' => 'Insanity',
      'data_type' => 'track',
      'data_thumb_url' => 'http://imgjam.com/albums/s30/30661/covers/1.100.jpg',
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
      'data_ref_id' => null,
      'data_type' => null,
      'data_download' => true,
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://www.jamendo.com/fr/search/all/psytrance'
    ));
    
    // 'http://www.jamendo.com/fr/artist/DJ_BETO'
    
    $this->assertEquals(array(
      'data_ref_id' => null,
      'data_type' => null,
      'data_download' => true,
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
      'data_type' => 'playlist',
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
      'data_thumb_url' => 'http://b.vimeocdn.com/ts/301/282/301282081_200.jpg'
    ),$this->proceed_element_datas_api(
      $bux, 
      'http://vimeo.com/43258820'
    ));
    
  }
  
}