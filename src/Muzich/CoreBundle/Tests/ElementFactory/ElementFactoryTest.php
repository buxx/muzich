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
   */
  public function testEngine()
  {
    $r = $this->getDoctrine();
    
    $bux = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bux')
    ;
    
    $hardtek = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
    
    $youtube_width = '425';
    $youtube_height = '350';
    
    $data = array(
      'name'    => 'Mon bel element',
      'url'     => 'http://www.youtube.com/watch?v=WC8qb_of04E',
      'tags'    => array($hardtek->getId(), $tribe->getId()),
      'group'   => ''
    );
    
    $element = new Element();
    
    $factory = new ElementManager($element, $r->getEntityManager(), $this->_container);
    $factory->proceedFill($data, $bux);
    
    $tags = new ArrayCollection();
    $tags->add($hardtek);
    $tags->add($tribe);
    
    $url = 'http://www.youtube.com/v/WC8qb_of04E&rel=1';
    
    $this->assertEquals($element->getName(), 'Mon bel element');
    $this->assertEquals($element->getUrl(), 'http://www.youtube.com/watch?v=WC8qb_of04E');
    $this->assertEquals($element->getTags(), $tags);
    $this->assertEquals($element->getEmbed(), 
      '<object width="'.$youtube_width.'" height="'.$youtube_height.'" >'
     .'<param name="movie" value="'.$url.'"></param><param name="wmode" value="transparent">'
     .'</param><embed src="'.$url.'" type="application/x-shockwave-flash" '
     .'wmode="transparent" width="'.$youtube_width.'" height="'.$youtube_height.'"></embed></object>'
    );
    
    
  }
  
}