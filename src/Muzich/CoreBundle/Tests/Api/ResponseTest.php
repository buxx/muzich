<?php

namespace Muzich\CoreBundle\Tests\Api;

use Muzich\CoreBundle\lib\Api\Response as ApiResponse;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
  
  protected $response_content = array(
    'id'     => 1,
    'title'  => '',
    'xtitle'  => '   ',
    'tags'   => array(),
    'url'    => null,
    'count'  => 0,
    'xcount' => '0',
    'user' => array(
      'username' => 'bob',
      'email'    => 'bob@mail.com',
      'address'  => array(
        'town'        => 'Paris',
        'country'     => 'France',
        'departement' => ''
      )
    )
  );
  
  public function testHaving()
  {
    $response = new ApiResponse($this->response_content);
    
    $this->assertTrue($response->have('id'));
    $this->assertTrue($response->have('user'));
    $this->assertFalse($response->have('toto'));
    $this->assertFalse($response->have('title'));
    $this->assertFalse($response->have('xtitle'));
    $this->assertFalse($response->have('url'));
    $this->assertTrue($response->have('count'));
    $this->assertTrue($response->have('xcount'));
    
    $this->assertFalse($response->have('toto', false));
    $this->assertTrue($response->have('title', false));
    $this->assertTrue($response->have('xtitle', false));
    $this->assertTrue($response->have('tags', false));
    
    $this->assertTrue($response->have(array('user' => 'username')));
    $this->assertTrue($response->have(array('user' => 'address')));
    $this->assertFalse($response->have(array('user' => 'toto')));
    $this->assertTrue($response->have(array('user' => array('address' => 'town'))));
    $this->assertFalse($response->have(array('user' => array('address' => 'toto'))));
    $this->assertFalse($response->have(array('user' => array('address' => 'departement'))));
    
    $this->assertFalse($response->have(array('user' => array('address' => 'toto'))));
    $this->assertTrue($response->have(array('user' => array('address' => 'departement')), false));
  }
  
  public function testGet()
  {
    $response = new ApiResponse($this->response_content);
    
    $this->assertEquals(1, $response->get('id'));
    $this->assertEquals(array(
      'username' => 'bob',
      'email'    => 'bob@mail.com',
      'address'  => array(
        'town'        => 'Paris',
        'country'     => 'France',
        'departement' => ''
      )
    ), $response->get('user'));
    $this->assertEquals(null, $response->get('toto'));
    $this->assertEquals(null, $response->get('title'));
    $this->assertEquals(null, $response->get('xtitle'));
    $this->assertEquals(null, $response->get('tags'));
    $this->assertEquals(null, $response->get('url'));
    $this->assertEquals(0, $response->get('count'));
    $this->assertEquals('0', $response->get('xcount'));
    
    $this->assertEquals('bob', $response->get(array('user' => 'username')));
    $this->assertEquals(array(
      'town'        => 'Paris',
      'country'     => 'France',
      'departement' => ''
    ), $response->get(array('user' => 'address')));
    $this->assertEquals(null, $response->get(array('user' => 'toto')));
    $this->assertEquals('Paris', $response->get(array('user' => array('address' => 'town'))));
    $this->assertEquals(null, $response->get(array('user' => array('address' => 'toto'))));
    $this->assertEquals(null, $response->get(array('user' => array('address' => 'departement'))));
  }
  
  public function testGetNotStrict()
  {
    $response = new ApiResponse($this->response_content);
    
    $this->assertEquals(1, $response->get('id', false));
    $this->assertEquals(array(
      'username' => 'bob',
      'email'    => 'bob@mail.com',
      'address'  => array(
        'town'        => 'Paris',
        'country'     => 'France',
        'departement' => ''
      )
    ), $response->get('user', false));
    $this->assertEquals(null, $response->get('toto', false));
    $this->assertEquals('', $response->get('title', false));
    $this->assertEquals('   ', $response->get('xtitle', false));
    $this->assertEquals(array(), $response->get('tags', false));
    $this->assertEquals(null, $response->get('url', false));
    $this->assertEquals(0, $response->get('count', false));
    $this->assertEquals('0', $response->get('xcount', false));
    
    $this->assertEquals('bob', $response->get(array('user' => 'username'), false));
    $this->assertEquals(array(
      'town'        => 'Paris',
      'country'     => 'France',
      'departement' => ''
    ), $response->get(array('user' => 'address'), false));
    $this->assertEquals(null, $response->get(array('user' => 'toto'), false));
    $this->assertEquals('Paris', $response->get(array('user' => array('address' => 'town'), false)));
    $this->assertEquals(null, $response->get(array('user' => array('address' => 'toto'), false)));
    $this->assertEquals('', $response->get(array('user' => array('address' => 'departement'), false)));
  }
  
}