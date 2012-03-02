<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;

class ElementControllerTest extends FunctionalTest
{ 
  
  public function testAddElementAjax()
  {
    $this->client = self::createClient();
    $this->connectUser('joelle', 'toor');
    
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
      
    // L'élément n'existe pas encore
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Musique qui dechire')
    ;
    $this->assertTrue(is_null($element));
    
    // On commence par ajouter un tag
    $url = $this->generateUrl('element_add');
   
    $extract = $this->crawler->filter('input[name="element_add[_token]"]')
      ->extract(array('value'));
    $csrf = $extract[0];
    
    $crawler = $this->client->request(
      'POST', 
      $url, 
      array(
          'element_add' => array(
              '_token' => $csrf,
              'name'   => 'Musique qui dechire',
              'url'    => 'http://www.youtube.com/watch?v=WC8qb_of04E',
              'tags'   => json_encode(array($hardtek->getId(), $tribe->getId()))
          )
        
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Musique qui dechire')
    ;
    $this->assertTrue(!is_null($element));
    
  }
  
  public function testAddElementInGroupAjax()
  {
    $this->client = self::createClient();
    $this->connectUser('joelle', 'toor');
    
    $Fans_de_psytrance = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('Fans de psytrance');
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
      
    // L'élément n'existe pas encore
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Musique qui dechire dans psytrance')
    ;
    $this->assertTrue(is_null($element));
    
    // On commence par ajouter un tag
    $url = $this->generateUrl('element_add', array('group_slug' => $Fans_de_psytrance->getSlug()));
   
    $extract = $this->crawler->filter('input[name="element_add[_token]"]')
      ->extract(array('value'));
    $csrf = $extract[0];
    
    $crawler = $this->client->request(
      'POST', 
      $url, 
      array(
          'element_add' => array(
              '_token' => $csrf,
              'name'   => 'Musique qui dechire dans psytrance',
              'url'    => 'http://www.youtube.com/watch?v=WC8qb_of04E',
              'tags'   => json_encode(array($hardtek->getId(), $tribe->getId()))
          )
        
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    $this->outputDebug($this->client->getResponse()->getContent());
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneBy(array(
        'name' => 'Musique qui dechire dans psytrance', 
        'group' => $Fans_de_psytrance->getId()
      ))
    ;
    $this->assertTrue(!is_null($element));
    
  }
  
  public function testAddElementAjaxFail()
  {
    $this->client = self::createClient();
    $this->connectUser('joelle', 'toor');
    
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
      
    // L'élément n'existe pas encore
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Musique qui dechire')
    ;
    $this->assertTrue(is_null($element));
    
    // On commence par ajouter un tag
    $url = $this->generateUrl('element_add');
   
    $extract = $this->crawler->filter('input[name="element_add[_token]"]')
      ->extract(array('value'));
    $csrf = $extract[0];
    
    $crawler = $this->client->request(
      'POST', 
      $url, 
      array(
          'element_add' => array(
              '_token' => $csrf,
              'name'   => 'Musique qui dechire',
              'url'    => 'http://www',
              'tags'   => json_encode(array($hardtek->getId(), $tribe->getId()))
          )
        
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'error');
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Musique qui dechire')
    ;
    $this->assertTrue(is_null($element));
    
  }
  
  public function testAddElementInGroupAjaxFail()
  {
    $this->client = self::createClient();
    $this->connectUser('joelle', 'toor');
    
    $Fans_de_psytrance = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('Fans de psytrance');
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
      
    // L'élément n'existe pas encore
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('zo')
    ;
    $this->assertTrue(is_null($element));
    
    // On commence par ajouter un tag
    $url = $this->generateUrl('element_add', array('group_slug' => $Fans_de_psytrance->getSlug()));
   
    $extract = $this->crawler->filter('input[name="element_add[_token]"]')
      ->extract(array('value'));
    $csrf = $extract[0];
    
    $crawler = $this->client->request(
      'POST', 
      $url, 
      array(
          'element_add' => array(
              '_token' => $csrf,
              'name'   => 'zo',
              'url'    => 'http://www.youtube.com/watch?v=WC8qb_of04E',
              'tags'   => json_encode(array($hardtek->getId(), $tribe->getId()))
          )
        
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'error');
    
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneBy(array(
        'name' => 'zo', 
        'group' => $Fans_de_psytrance->getId()
      ))
    ;
    $this->assertTrue(is_null($element));
    
  }
  
}