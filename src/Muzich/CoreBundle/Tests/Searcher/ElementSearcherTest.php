<?php

namespace Muzich\CoreBundle\Tests\Searcher;

use Muzich\CoreBundle\lib\UnitTest;
use Muzich\CoreBundle\Searcher\ElementSearcher;

class ElementSearcherTest extends UnitTest
{  
  public function testInit()
  {
    $es = new ElementSearcher();
    $es->init($ia = array(
        'network'   => ElementSearcher::NETWORK_PERSONAL, 
        'tags'      => array(1 => '', 2 => '', 6 => ''), 
        'count'     => 20, 
        'user_id'   => 185, 
        'group_id'  => null, 
        'favorite'  => false,
        'ids'       => null,
        'ids_display' => null,
        'tag_strict' => false,
        'string'     => null
    ));

    $this->assertEquals($ia, $es->getParams());
  }
  
  public function testUpdate()
  {
    $es = new ElementSearcher();
    $es->init($ia = array(
        'network'   => ElementSearcher::NETWORK_PERSONAL, 
        'tags'      => array(1 => '', 2 => '', 6 => ''), 
        'count'     => 20, 
        'user_id'   => 185, 
        'group_id'  => null, 
        'favorite'  => false
    ));
    $es->init($ua = array(
        'network'   => ElementSearcher::NETWORK_PUBLIC, 
        'tags'      => array(5 => '', 8 => '', 123 => ''), 
        'count'     => 21, 
        'user_id'   => 115, 
        'group_id'  => null, 
        'favorite'  => false,
        'ids'       => null,
        'ids_display' => null,
        'tag_strict' => false,
        'string'     => null
    ));

    $this->assertEquals($ua, $es->getParams());
  }
  
  protected function checkElementSearchResults($es_results, $array_names)
  {
    $cpt = 0;
    $array_names_es = array();
    foreach ($es_results as $element)
    {
      $array_names_es[] = (string)$element->getName();
    }
    
    $this->assertEquals($array_names, $array_names_es);
  }
  
  /**
   * Test pour la configuration:
   * public
   * tags
   * limit
   * 
   * Test basés sur les FIXTURES
   */
  public function testGetPublicForTags()
  {
    $r = $this->getDoctrine();
    $bux = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bux')
    ;
    $hardtek = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe   = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
    $electro = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Electro');
    
    $es = new ElementSearcher();
    $es->init(array(
      'network'   => ElementSearcher::NETWORK_PUBLIC,
      'tags'      => array(
        $hardtek->getId() => 'Hardtek', 
        $tribe->getId()   => 'Tribe', 
        $electro->getId() => 'Electro'
      ),
      'count'     => 5
    ));
    
    $this->checkElementSearchResults(
      $es->getElements($r, $bux->getId()), 
      array(
        'Ed Cox - La fanfare des teuffeurs (Hardcordian)',
        'CardioT3K - Juggernaut Trap',
        'RE-FUCK (ReVeRB_FBC) mix.',
        'All Is Full Of Pain',
        'Acrotek Hardtek G01'
      )
    );
    
  }
  
  /**
   * Test pour la configuration:
   * personal
   * tags
   * limit
   * 
   * Test basés sur les FIXTURES
   */
  public function testGetPersonalForTags()
  {
    $r = $this->getDoctrine();
    $bux = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bux')
    ;
    $hardtek = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe   = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
    $electro = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Electro');
    
    $es = new ElementSearcher();
    $es->init(array(
      'network'   => ElementSearcher::NETWORK_PERSONAL,
      'tags'      => array(
        $hardtek->getId() => 'Hardtek', 
        $tribe->getId()   => 'Tribe', 
        $electro->getId() => 'Electro'
      ),
      'count'     => 5
    ));
    
    $this->checkElementSearchResults(
      $es->getElements($r, $bux->getId()), 
      array(
        'CardioT3K - Juggernaut Trap',
        'RE-FUCK (ReVeRB_FBC) mix.',
        'All Is Full Of Pain',
        'Acrotek Hardtek G01',
        'Dj antoine'
      )
    );
    
  }
  
  /**
   * Test pour la configuration:
   * public
   * limit
   * 
   * Test basés sur les FIXTURES
   */
  public function testGetPublicWithoutTags()
  {
    $r = $this->getDoctrine();
    $bux = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bux')
    ;
    
    $es = new ElementSearcher();
    $es->init(array(
      'network'   => ElementSearcher::NETWORK_PUBLIC,
      'count'     => 5
    ));
    
    $this->checkElementSearchResults(
      $es->getElements($r, $bux->getId()), 
      array(
        'Ed Cox - La fanfare des teuffeurs (Hardcordian)',
        'Babylon Pression - Des Tasers et des Pauvres',
        'AZYD AZYLUM Live au Café Provisoire',
        'SOULFLY - Prophecy',
        'Dubstep Beatbox'
      )
    );
    
  }
  
  /**
   * Test pour la configuration:
   * personal
   * limit
   * 
   * Test basés sur les FIXTURES
   */
  public function testGetPersonalWithoutTags()
  {
    $r = $this->getDoctrine();
    $bux = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bux')
    ;
    
    $es = new ElementSearcher();
    $es->init(array(
      'network'   => ElementSearcher::NETWORK_PERSONAL,
      'count'     => 5
    ));
    
    $this->checkElementSearchResults(
      $es->getElements($r, $bux->getId()), 
      array(
        'Infected mushroom - Muse Breaks',
        'Infected Mushroom - Psycho',
        'DUDELDRUM',
        'CardioT3K - Juggernaut Trap',
        'RE-FUCK (ReVeRB_FBC) mix.'
      )
    );
    
  }
  
  /**
   * Test pour la configuration:
   * personal
   * limit
   * 
   * Test basés sur les FIXTURES
   */
  public function testGetProfile()
  {
    $r = $this->getDoctrine();
    $bux = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bux')
    ;
    $jean = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('jean')
    ;
    
    $es = new ElementSearcher();
    $es->init(array(
      'user_id'   => $jean->getId(),
      'count'     => 5
    ));
    
    $this->checkElementSearchResults(
      $es->getElements($r, $bux->getId()), 
      array(
        'Acrotek Hardtek G01',
        'Dj antoine',
        'DJ FAB'
      )
    );
    
    $paul = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('paul')
    ;
    
    $es = new ElementSearcher();
    $es->init(array(
      'user_id'   => $paul->getId(),
      'count'     => 5
    ));
    
    $this->checkElementSearchResults(
      $es->getElements($r, $bux->getId()), 
      array(
        'Infected Mushroom - Psycho',
        'CardioT3K - Juggernaut Trap',
        'RE-FUCK (ReVeRB_FBC) mix.',
        'All Is Full Of Pain'
      )
    );
    
  }
  
  /**
   * Test pour la configuration:
   * personal
   * limit
   * 
   * Test basés sur les FIXTURES
   */
  public function testGetFavoriteProfile()
  {
    $r = $this->getDoctrine();
    $bux = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bux')
    ;
    
    $paul = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('paul')
    ;
    
    $es = new ElementSearcher();
    $es->init(array(
      'user_id'   => $paul->getId(),
      'favorite'  => true,
      'count'     => 5
    ));
    
    $this->checkElementSearchResults(
      $es->getElements($r, $bux->getId()), 
      array(
        'All Is Full Of Pain',
        'Heretik System Popof - Resistance'
      )
    );
    
  }
  
  /**
   * Test pour la configuration:
   * personal
   * limit
   * 
   * Test basés sur les FIXTURES
   */
  public function testGetGroup()
  {
    $r = $this->getDoctrine();
    $bux = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bux')
    ;
    $fdepsy = $r->getRepository('MuzichCoreBundle:Group')
      ->findOneByName('Fans de psytrance')
    ;
    
    $es = new ElementSearcher();
    $es->init(array(
      'group_id'   => $fdepsy->getId(),
      'count'     => 5
    ));
    
    $this->checkElementSearchResults(
      $es->getElements($r, $bux->getId()), 
      array(
        'Infected mushroom - Muse Breaks',
        'Infected Mushroom - Psycho'
      )
    );
    
  }
  
  public function testTagStrict()
  {
    $r = $this->getDoctrine();
    $bux = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bux')
    ;
    $hardtek   = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe     = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
    $electro   = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Electro');
    $metal     = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Metal');
    $hardcore  = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardcore');
    $psytrance = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Psytrance');
    $dubstep   = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Dubstep');
    
    $es = new ElementSearcher();
    $es->init(array(
      'network'   => ElementSearcher::NETWORK_PUBLIC,
      'tags'      => array(
        $hardtek->getId() => 'Hardtek', 
        $tribe->getId()   => 'Tribe'
      ),
      'count'      => 5,
      'tag_strict' => true
    ));
    
    $this->checkElementSearchResults(
      $es->getElements($r, $bux->getId()), 
      array(
        0 => 'All Is Full Of Pain',
        1 => 'Dj antoine'
      )
    );
    
    $es = new ElementSearcher();
    $es->init(array(
      'network'   => ElementSearcher::NETWORK_PUBLIC,
      'tags'      => array(
        $electro->getId()   => 'Electro', 
        $hardtek->getId()   => 'Hardtek'
      ),
      'count'      => 5,
      'tag_strict' => true
    ));
    
    $this->checkElementSearchResults(
      $es->getElements($r, $bux->getId()), 
      array(
        'KoinkOin - H5N1'
      )
    );
    
    $es = new ElementSearcher();
    $es->init(array(
      'network'   => ElementSearcher::NETWORK_PUBLIC,
      'tags'      => array(
        $metal->getId()      => 'Metal', 
        $hardcore->getId()   => 'Hardcore'
      ),
      'count'      => 5,
      'tag_strict' => true
    ));
    
    $this->checkElementSearchResults(
      $es->getElements($r, $bux->getId()), 
      array(
        'Babylon Pression - Des Tasers et des Pauvres'
      )
    );
    
  }
  
}