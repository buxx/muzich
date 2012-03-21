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
    $this->assertTrue(strpos($response['html'], 'class="edit_element"') !== false);
    
    // Il faut que l'on récupère le token
    preg_match("#name=\"element_add\[_token\]\" value=\"([a-zA-Z0-9]+)\" />#", $response['html'], $chaines);
    $csrf = $chaines[1];
    
    // On effectue la modification en ajax
    $url = $this->generateUrl('element_update', array(
      'element_id' => $element->getId(),
      'dom_id'     => 'element_'.$element->getId()
    ));
    
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
  
  public function testReportElement()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $paul = $this->getUser();
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // Paul signale cet élément comme pas bien
    $url = $this->generateUrl('ajax_report_element', array(
      'element_id' => $element->getId(),
      'token'      => $paul->getPersonalHash()
    ));
    
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
    
    // On check en base
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    $this->assertEquals($element->getCountReport(), 1);
    $this->assertEquals($element->getReportIds(), array((string)$paul->getId()));
    
    // Si il effectue le signalement une deuxième fois sur le même element
    // Ca ne doit pas bouger puisqu'il l'a déjà fait
    $url = $this->generateUrl('ajax_report_element', array(
      'element_id' => $element->getId(),
      'token'      => $paul->getPersonalHash()
    ));
    
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
    
    // On check en base
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    $this->assertEquals($element->getCountReport(), 1);
    $this->assertEquals($element->getReportIds(), array((string)$paul->getId()));
    
    $this->disconnectUser();
    // On connecte joelle pour faire le même test sur le même élément
    $this->connectUser('joelle', 'toor');
    
    $joelle = $this->getUser();
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // Paul signale cet élément comme pas bien
    $url = $this->generateUrl('ajax_report_element', array(
      'element_id' => $element->getId(),
      'token'      => $joelle->getPersonalHash()
    ));
    
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
    
    // On check en base
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    $this->assertEquals($element->getCountReport(), 2);
    $this->assertEquals($element->getReportIds(), array((string)$paul->getId(), (string)$joelle->getId()));
    
    // Si il effectue le signalement une deuxième fois sur le même element
    // Ca ne doit pas bouger puisqu'il l'a déjà fait
    $url = $this->generateUrl('ajax_report_element', array(
      'element_id' => $element->getId(),
      'token'      => $joelle->getPersonalHash()
    ));
    
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
    
    // On check en base
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    $this->assertEquals($element->getCountReport(), 2);
    $this->assertEquals($element->getReportIds(), array((string)$paul->getId(), (string)$joelle->getId()));
    
  }
  
  /**
   * Procédure de vote
   */
  public function testVote()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $paul = $this->getUser();
    $joelle = $this->getUser('joelle');
    $jean = $this->getUser('jean');
    
    // D'après les fixtures, un des élément porte le vote de paul
    $element_soul = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('SOULFLY - Prophecy')
    ;
    
    // On peut donc voir le lien pour "dé-voter"
    $url_unvote_soul = $this->generateUrl('ajax_element_remove_vote_good', array(
      'element_id' => $element_soul->getId(),
      'token' => $paul->getPersonalHash()
    ));
    $this->exist('a.vote[href="'.$url_unvote_soul.'"]');
    
    // On contrôle le contenu pour cet element
    $this->assertEquals($element_soul->getPoints(), 1);
    
    // Et son id est la
    $this->assertEquals($element_soul->getVoteGoodIds(), array(
      (string)$paul->getId()
    ));
    
    // On va voter pour un element a bux
    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // Actuellement (fixtures) son score est de 2
    $this->assertEquals($element_ed->getPoints(), 2);
    
    // Et ce sont (fixtures) ces deux user qui ont voté
    $this->assertEquals($element_ed->getVoteGoodIds(), array((string)$joelle->getId(), (string)$jean->getId()));
    
    // On peut d'ailleur constater que la reputation de bux est de 7 (fixtures)
    $bux = $this->getUser('bux');
    $this->assertEquals($bux->getReputation(), 7);
    
    // paul va voter 
    $crawler = $this->client->request(
      'GET', 
      $this->generateUrl('ajax_element_add_vote_good', array(
        'element_id' => $element_ed->getId(),
        'token' => $paul->getPersonalHash()
      )), 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // On recontrôle l'élément voir si tout a été enregistré
    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // Son score est mainteannt de 3
    $this->assertEquals($element_ed->getPoints(), 3);
    
    // Et son id est la
    $this->assertEquals($element_ed->getVoteGoodIds(), array(
      (string)$joelle->getId(), 
      (string)$jean->getId(),
      (string)$paul->getId()
    ));
    
    // On peut d'ailleur constater que la reputation de bux est maintenant de 8
    $bux = $this->getUser('bux');
    $this->assertEquals($bux->getReputation(), 8);
    
    // Pau retire son vote de soulfy
    $crawler = $this->client->request(
      'GET', 
      $url_unvote_soul, 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $element_soul = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('SOULFLY - Prophecy')
    ;
    // On contrôle le contenu pour cet element
    $this->assertEquals($element_soul->getPoints(), 0);
    
    // Et son id est la
    $this->assertEquals($element_soul->getVoteGoodIds(), array());
    
    // On peut d'ailleur constater que la reputation de bux est maintenant de 7
    $bux = $this->getUser('bux');
    $this->assertEquals($bux->getReputation(), 7);
    
    // On déconnecte paul, pour faire voter bob sur le partage ed cox
    $this->disconnectUser();
    $this->connectUser('bob', 'toor');
    
    $bob = $this->getUser();
    // bob va donc votre pour le partage d'ed cox
    $crawler = $this->client->request(
      'GET', 
      $this->generateUrl('ajax_element_add_vote_good', array(
        'element_id' => $element_ed->getId(),
        'token' => $bob->getPersonalHash()
      )), 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // On recontrôle l'élément voir si tout a été enregistré
    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // Son score est mainteannt de 3
    $this->assertEquals($element_ed->getPoints(), 4);
    
    // Et son id est la
    $this->assertEquals($element_ed->getVoteGoodIds(), array(
      (string)$joelle->getId(), 
      (string)$jean->getId(),
      (string)$paul->getId(),
      (string)$bob->getId()
    ));
    
    // On peut d'ailleur constater que la reputation de bux est maintenant de 8
    $bux = $this->getUser('bux');
    $this->assertEquals($bux->getReputation(), 8);
  }
  
}