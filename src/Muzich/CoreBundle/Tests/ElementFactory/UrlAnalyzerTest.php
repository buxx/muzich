<?php

namespace Muzich\CoreBundle\Tests\Api;

use Muzich\CoreBundle\lib\Element\UrlAnalyzer;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Factory\Elements\Jamendocom;
use Muzich\CoreBundle\Factory\UrlMatchs;

class UrlAnalyzerTest extends \PHPUnit_Framework_TestCase
{
  
  protected function getNewElement($type, $url)
  {
    $element = new Element();
    $element->setUrl($url);
    $element->setType($type);
    return $element;
  }
  
  public function testJamendo()
  {
    $url_analyzer = new UrlAnalyzer($this->getNewElement('jamendo.com', 'http://www.jamendo.com/fr/track/894974'), UrlMatchs::$jamendo);
    $this->assertTrue($url_analyzer->haveMatch());
    $this->assertEquals(Element::TYPE_TRACK, $url_analyzer->getType());
    $this->assertEquals('894974', $url_analyzer->getRefId());
    
    $url_analyzer = new UrlAnalyzer($this->getNewElement('jamendo.com', 'http://www.jamendo.com/fr/track/347602/come-come'), UrlMatchs::$jamendo);
    $this->assertTrue($url_analyzer->haveMatch());
    $this->assertEquals(Element::TYPE_TRACK, $url_analyzer->getType());
    $this->assertEquals('347602', $url_analyzer->getRefId());
    
    $url_analyzer = new UrlAnalyzer($this->getNewElement('jamendo.com', 'http://www.jamendo.com/fr/album/3409'), UrlMatchs::$jamendo);
    $this->assertTrue($url_analyzer->haveMatch());
    $this->assertEquals(Element::TYPE_ALBUM, $url_analyzer->getType());
    $this->assertEquals('3409', $url_analyzer->getRefId());
    
    $url_analyzer = new UrlAnalyzer($this->getNewElement('jamendo.com', 'http://www.jamendo.com/fr/list/a45666/proceed-positron...'), UrlMatchs::$jamendo);
    $this->assertTrue($url_analyzer->haveMatch());
    $this->assertEquals(Element::TYPE_ALBUM, $url_analyzer->getType());
    $this->assertEquals('45666', $url_analyzer->getRefId());
    
    $url_analyzer = new UrlAnalyzer($this->getNewElement('jamendo.com', 'http://www.jamendo.com/fr/playlist/2134354'), UrlMatchs::$jamendo);
    $this->assertFalse($url_analyzer->haveMatch());
    $this->assertEquals(null, $url_analyzer->getType());
    $this->assertEquals(null, $url_analyzer->getRefId());
  }
  
  public function testSoundCloud()
  {
    $url_analyzer = new UrlAnalyzer($this->getNewElement('soundcloud.com', 'http://soundcloud.com/matas/sets/library-project'), UrlMatchs::$soundcloud);
    $this->assertTrue($url_analyzer->haveMatch());
    
    $url_analyzer = new UrlAnalyzer($this->getNewElement('soundcloud.com', 'http://soundcloud.com/noisia/black-sun-empire-noisia-feed'), UrlMatchs::$soundcloud);
    $this->assertTrue($url_analyzer->haveMatch());
    
    $url_analyzer = new UrlAnalyzer($this->getNewElement('soundcloud.com', 'http://soundcloud.com/user4818423/mechanika-crew-andrew-dj-set'), UrlMatchs::$soundcloud);
    $this->assertTrue($url_analyzer->haveMatch());
    
    $url_analyzer = new UrlAnalyzer($this->getNewElement('soundcloud.com', 'https://soundcloud.com/sinkane/okay-africa-mixtape-2011#play'), UrlMatchs::$soundcloud);
    $this->assertTrue($url_analyzer->haveMatch());
    
    $url_analyzer = new UrlAnalyzer($this->getNewElement('soundcloud.com', 'https://soundcloud.com/search?q=toto'), UrlMatchs::$soundcloud);
    $this->assertFalse($url_analyzer->haveMatch());
  }
  
}