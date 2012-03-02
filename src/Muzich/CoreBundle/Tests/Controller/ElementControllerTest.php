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
  
  public function testUpdateElement()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    $bux = $this->getUser();
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // On est sur la page home, on peut voir le lien de modification de l'élément
    $this->exist('a[href="'.($url = $this->generateUrl('element_edit', array('element_id' => $element->getId()))).'"]');
  
    // On effectue la demande ajax d'edition
    $crawler = $this->client->request(
      'GET', 
      $url, 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    $this->assertEquals($response['form_name'], 'element_'.$element->getId());
    $this->assertTrue(strpos($response['html'], '<form novalidate class="edit_element"') !== false);
    
    // Il faut que l'on récupère le token
    preg_match("#name=\"element_add\[_token\]\" value=\"([a-zA-Z0-9]+)\" />#", $response['html'], $chaines);
    $csrf = $chaines[1];
    
    // On effectue la modification en ajax
    $url = $this->generateUrl('element_update', array('element_id' => $element->getId()));
    
    $crawler = $this->client->request(
      'POST', 
      $url, 
      array(
          'element_add' => array(
              '_token' => $csrf,
              'name'   => $element->getName().'555',
              'url'    => $element->getUrl(),
              'tags'   => $element->getTagsIdsJson()
          )
        
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->outputDebug();
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    $this->assertTrue(strpos($response['html'], $element->getName().'555') !== false);
    
    $this->crawler = $this->client->request('GET', $this->generateUrl('home'));
    $this->exist('span.element_name:contains("'.$element->getName().'555'.'")');
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName($element->getName().'555')
    ;
    $this->assertTrue(!is_null($element));
  }
  
  public function testDeleteElement()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    $bux = $this->getUser();
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // On est sur la page home, on peut voir le lien de suppression l'élément
    $this->exist('a[href="'.($url = $this->generateUrl('element_remove', array(
        'element_id' => $element->getId()
    ))).'"]');
  
    // Suppression de l'élément
 
    // On effectue la demande ajax d'edition
    $crawler = $this->client->request(
      'GET', 
      $url, 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    $this->crawler = $this->client->request('GET', $this->generateUrl('home'));
    $this->notExist('span.element_name:contains("'.$element->getName().'")');
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    $this->assertTrue(is_null($element));
  }
  
}