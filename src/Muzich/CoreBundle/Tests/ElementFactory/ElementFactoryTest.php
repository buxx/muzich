<?php

namespace Muzich\CoreBundle\Tests\Searcher;

use Muzich\CoreBundle\lib\UnitTest;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\ElementFactory\ElementManager;
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
        .'src="http://www.dailymotion.com/embed/video/xafj1q"></iframe>'
    );
    
    /*
     * - soundcloud.com
     */
    $id = md5('http://soundcloud.com/matas/sets/library-project');
    $this->proceed_elementAndFill(
      $bux, 
      'faez7tf8re9h4gf5j64dssz', 
      'http://soundcloud.com/matas/sets/library-project', 
      array($hardtek->getId(), $tribe->getId()), 
      '<object height="'.$this->getParam('soundcloud_player_height').'" width="100%" id="embed_'.$id.'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
          <param name="movie" value="http://player.soundcloud.com/player.swf?url=http://soundcloud.com/matas/sets/library-project&amp;enable_api=true&amp;object_id=embed_'.$id.'"></param>
          <param name="allowscriptaccess" value="always"></param>
          <embed allowscriptaccess="always" height="'.$this->getParam('soundcloud_player_height').'" src="http://player.soundcloud.com/player.swf?url=http://soundcloud.com/matas/sets/library-project&amp;enable_api=true&amp;object_id=embed_'.$id.'" type="application/x-shockwave-flash" width="100%" name="embed_'.$id.'"></embed>
        </object>
        '
    );
    
    $id = md5('http://soundcloud.com/matas/above-hyperion-redux');
    $this->proceed_elementAndFill(
      $bux, 
      'faez7tf8re9h4gf5j64dssz', 
      'http://soundcloud.com/matas/above-hyperion-redux', 
      array($hardtek->getId(), $tribe->getId()), 
      '<object height="'.$this->getParam('soundcloud_player_height').'" width="100%" id="embed_'.$id.'" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
          <param name="movie" value="http://player.soundcloud.com/player.swf?url=http://soundcloud.com/matas/above-hyperion-redux&amp;enable_api=true&amp;object_id=embed_'.$id.'"></param>
          <param name="allowscriptaccess" value="always"></param>
          <embed allowscriptaccess="always" height="'.$this->getParam('soundcloud_player_height').'" src="http://player.soundcloud.com/player.swf?url=http://soundcloud.com/matas/above-hyperion-redux&amp;enable_api=true&amp;object_id=embed_'.$id.'" type="application/x-shockwave-flash" width="100%" name="embed_'.$id.'"></embed>
        </object>
        '
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
      '
          <object width="'.$this->getParam('jamendo_player_width').'" height="'.$this->getParam('jamendo_player_height').'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" align="middle">
            <param name="allowScriptAccess" value="always" />
            <param name="wmode" value="transparent" />
            <param name="movie" value="http://widgets.jamendo.com/fr/album/?album_id=30661&playertype=2008" />
            <param name="quality" value="high" />
            <param name="bgcolor" value="#FFFFFF" />
            <embed src="http://widgets.jamendo.com/fr/album/?album_id=30661&playertype=2008" quality="high" wmode="transparent" bgcolor="#FFFFFF" width="'.$this->getParam('jamendo_player_width').'" height="'.$this->getParam('jamendo_player_height').'" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
              &nbsp;
            </embed>
            &nbsp;
          </object>
          <a href="http://pro.jamendo.com/" style="display:block;font-size:8px !important;">Catalogue professionnel de musique libre</a>
        '
    );
    
    $this->proceed_elementAndFill(
      $bux, 
      'gthyk456+liszz', 
      'http://www.jamendo.com/fr/track/207079', 
      array($hardtek->getId(), $tribe->getId()), 
      '
          <object width="'.$this->getParam('jamendo_player_width').'" height="'.$this->getParam('jamendo_player_height').'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" align="middle">
            <param name="allowScriptAccess" value="always" />
            <param name="wmode" value="transparent" />
            <param name="movie" value="http://widgets.jamendo.com/fr/track/?playertype=2008&track_id=207079" />
            <param name="quality" value="high" />
            <param name="bgcolor" value="#FFFFFF" />
            <embed src="http://widgets.jamendo.com/fr/track/?playertype=2008&track_id=207079" quality="high" wmode="transparent" bgcolor="#FFFFFF" width="'.$this->getParam('jamendo_player_width').'" height="'.$this->getParam('jamendo_player_height').'" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
              &nbsp;
            </embed>
            &nbsp;
          </object>
          <a href="http://pro.jamendo.com/" style="display:block;font-size:8px !important;">Catalogue professionnel de musique libre</a>
        '
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
        .'src="http://www.deezer.com/fr/plugins/player?autoplay=false&playlist=true'
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
        .'src="http://www.deezer.com/fr/plugins/player?autoplay=false&playlist=true'
        .'&width='.$this->getParam('deezer_player_width').'&height='
        .$this->getParam('deezer_player_height')
        .'&cover=true&btn_popup=true&type=playlist&id=18701350&title=" '
        .'width="'.$this->getParam('deezer_player_width').'" height="'
        .$this->getParam('deezer_player_height')
        .'"></iframe>'
    );
    
  }
  
}