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
        'favorite'  => false
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
        'favorite'  => false
    ));

    $this->assertEquals($ua, $es->getParams());
  }
  
  protected function checkElementSearchResults($es_results, $array_names)
  {
    $cpt = 0;
    $array_names_es = array();
    foreach ($es_results as $element)
    {
      $array_names_es[] = $element->getName();
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
        'Acrotek Hardtek G01',
        'KoinkOin - H5N1',
        'Antropod - Polakatek'
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
        'Acrotek Hardtek G01',
        'RE-FUCK (ReVeRB_FBC) mix.',
        'All Is Full Of Pain',
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
        'DUDELDRUM',
        'Ed Cox - La fanfare des teuffeurs (Hardcordian)',
        'Babylon Pression - Des Tasers et des Pauvres',
        'SOULFLY - Prophecy',
        'CardioT3K - Juggernaut Trap'
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
        'DUDELDRUM',
        'CardioT3K - Juggernaut Trap',
        'Acrotek Hardtek G01',
        'Infected Mushroom - Psycho',
        'Infected mushroom - Muse Breaks'
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
        'CardioT3K - Juggernaut Trap',
        'Infected Mushroom - Psycho',
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
        'Heretik System Popof - Resistance',
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
        'Infected Mushroom - Psycho',
        'Infected mushroom - Muse Breaks'
      )
    );
    
  }
  
}