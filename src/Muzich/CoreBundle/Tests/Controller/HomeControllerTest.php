<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;
use Muzich\CoreBundle\Searcher\ElementSearcher;
use Muzich\CoreBundle\Entity\Element;

class HomeControllerTest extends FunctionalTest
{
  /**
   * Ce test contrôle l'affichage des elements sur la page d'accueil
   * Il modifie egallement le filtre d'éléments
   */
  public function testFilter()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
  
    // Présence du formulaire d'ajout d'un élément
    $this->exist('form[action="'.($url = $this->generateUrl('element_add')).'"]');
    $this->exist('form[action="'.$url.'"] input[id="element_add_name"]');
    $this->exist('form[action="'.$url.'"] input[id="element_add_url"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    // Présence du formulaire de filtrage
    $this->exist('form[action="'.($url = $this->generateUrl('search_elements', array('context'=>'home'))).'"]');
    $this->exist('form[action="'.$url.'"] select[id="element_search_form_network"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    $hardtek_id = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek')->getId();
    $tribe_id   = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe')->getId();
    
    // On récupére le formulaire de filtrage
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
        
    // On met ce que l'on veut dans le form
    $form['element_search_form[network]'] = ElementSearcher::NETWORK_PUBLIC;
    $form['element_search_form[tags]'] = json_encode(array($hardtek_id, $tribe_id));
    $this->submit($form);
    
    $this->client->submit($form);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $this->assertTrue($this->getSession()->has('user.element_search.params'));
    $this->assertEquals(array(
        'network'   => ElementSearcher::NETWORK_PUBLIC,
        'tags'      => array(
          $hardtek_id => 'Hardtek', 
          $tribe_id   => 'Tribe'
        ),
        'count'       => $this->getContainer()->getParameter('search_default_count'),
        'user_id'     => null,
        'group_id'    => null,
        'favorite'    => false,
        'ids'         => null,
        'ids_display' => null,
        'tag_strict'  => false,
        'string'      => null,
        'need_tags'   => false,
    ), $this->getSession()->get('user.element_search.params'));
    
    // On fabrique l'ElementSearcher correspondant
    $es = new ElementSearcher();
    $es->init($this->getSession()->get('user.element_search.params'));
    
    foreach ($es->getElements($this->getDoctrine(), $this->getUser()->getId()) as $element)
    {
      $this->exist('html:contains("'.$element->getName().'")');
    }
  }
  
  /**
   * Test de la présence des elements sur la page d'un utilisateur
   */
  public function testUserPage()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    $jean = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('jean')
    ;
    
    $this->crawler = $this->client->request(
      'GET', 
      $this->generateUrl('show_user', array('slug' => $jean->getSlug()))
    );
    
    $this->isResponseSuccess();
    $this->exist('h1:contains("'.$jean->getName().'")');
    
    $es = new ElementSearcher();
    $es->init(array(
      'user_id' => $jean->getId()
    ));
    
    foreach ($es->getElements($this->getDoctrine(), $this->getUser()->getId()) as $element)
    {
      $this->exist('html:contains("'.$element->getName().'")');
    }
  }
  
  /**
   * Test de la présence des elements sur la page d'un utilisateur
   */
  public function testGroupPage()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    $fdp = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')
      ->findOneBySlug('fans-de-psytrance')
      ->getSingleResult()
    ;
    
    $this->crawler = $this->client->request(
      'GET', 
      $this->generateUrl('show_group', array('slug' => $fdp->getSlug()))
    );
    
    $this->isResponseSuccess();
    $this->exist('h1:contains("'.$fdp->getName().'")');
    
    $es = new ElementSearcher();
    $es->init(array(
      'group_id' => $fdp->getId()
    ));
    
    foreach ($es->getElements($this->getDoctrine(), $this->getUser()->getId()) as $element)
    {
      $this->exist('html:contains("'.$element->getName().'")');
    }
  }
  
  /**
   * Ajouts d'éléments et tests de cas refusés
   */
  public function testAddElementSuccess()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
    
    /*
     *  Ajout d'un élément avec succés
     */
    $this->procedure_add_element(
      'Mon bel element', 
      'http://www.youtube.com/watch?v=WC8qb_of04E', 
      array($hardtek->getId(), $tribe->getId()),
            null, true
    );
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $this->exist('li:contains("Mon bel element")');
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Mon bel element')
    ;
    $this->assertTrue(!is_null($element));
        
  }
  
  /**
   * Ajouts d'éléments et tests de cas refusés
   */
  public function testAddElementFailure()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
    
    /*
     *  Ajouts d'éléments avec echec
     */
    
    // Nom trop court
    $this->procedure_add_element(
      'Mo', 
      'http://www.youtube.com/watch?v=WC8qb_of04E', 
      array($hardtek->getId(), $tribe->getId())
    );
    $this->isResponseSuccess();
        
    $this->notExist('li:contains("Mon bel element a4er563a1r")');
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Mon bel element a4er563a1r')
    ;
    $this->assertTrue(is_null($element));
    
    // Nom trop long
    $this->procedure_add_element(
      'Mon bel element mais qui a un nom trop court  la vache oui trop long hohoho la vache oui trop long hohoho la vache oui trop long hohoho', 
      'http://www.youtube.com/watch?v=WC8qb_of04E', 
      array($hardtek->getId(), $tribe->getId())
    );
    
    $this->isResponseSuccess();
    
    $this->notExist('li:contains("Mon bel element mais qui a un nom trop court la vache oui trop long hohoho")');
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Mon bel element mais qui a un nom trop court la vache oui trop long hohoho')
    ;
    $this->assertTrue(is_null($element));
    
    // Pas d'url
    $this->procedure_add_element(
      'Mon bel element', 
      '', 
      array($hardtek->getId(), $tribe->getId())
    );
    
    $this->isResponseSuccess();
    
    $this->notExist('li:contains("Mon bel element gfez7f")');
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Mon bel element gfez7f')
    ;
    $this->assertTrue(is_null($element));
    
    // url non conforme
    $this->procedure_add_element(
      'Mon bel element 789e', 
      'http://', 
      array($hardtek->getId(), $tribe->getId())
    );
    
    $this->isResponseSuccess();
    
    $this->notExist('li:contains("Mon bel element 789e")');
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Mon bel element 789e')
    ;
    $this->assertTrue(is_null($element));
    
    // url non conforme
    $this->procedure_add_element(
      'Mon bel element 789f', 
      'http://youtube', 
      array($hardtek->getId(), $tribe->getId())
    );
    
    $this->isResponseSuccess();
    
    $this->notExist('li:contains("Mon bel element 789f")');
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Mon bel element 789f')
    ;
    $this->assertTrue(is_null($element));
    
    // url non conforme
    $this->procedure_add_element(
      'Mon bel element 789g', 
      'youtube.com?lalala', 
      array($hardtek->getId(), $tribe->getId())
    );
    
    $this->isResponseSuccess();
    
    $this->notExist('li:contains("Mon bel element 789g")');
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Mon bel element 789g')
    ;
    $this->assertTrue(is_null($element));
    
    // Pas de nom
    $this->procedure_add_element(
      '', 
      'http://www.youtube.com/watch?v=WC8qb_of04E', 
      array($hardtek->getId(), $tribe->getId())
    );
    
    $this->isResponseSuccess();
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('')
    ;
    $this->assertTrue(is_null($element));
    
  }
  
  /**
   * L'ajout d'un Element a un de ses groupe ne doit pas poser de problème
   */
  public function testAddElementAtMyGroupSuccess()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    // Un groupe open, donc pas de soucis
    $fan_de_psy = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('Fans de psytrance');
    
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
        
    $this->isResponseSuccess();
    $this->procedure_add_element(
      'Element mis dans le groupe de psytrance', 
      'http://www.youtube.com/watch?v=WC8qb_of04E', 
      array($hardtek->getId(), $tribe->getId()),
      $fan_de_psy->getSlug()
    );
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $this->exist('li:contains("Element mis dans le groupe de psytrance")');
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Element mis dans le groupe de psytrance')
    ;
    $this->assertTrue(!is_null($element));
    
    if (!is_null($element))
    {
      $this->assertEquals($fan_de_psy->getId(), $element->getGroup()->getId());
    }
    else
    {
      $this->assertTrue(false);
    }
    
    $this->disconnectUser();
    
    /*
     * Ajout d'un element dans un groupe que l'on posséde.
     */
    $this->client = self::createClient();
    $this->connectUser('joelle', 'toor');
    $this->isResponseSuccess();
    
    // Ce groupe appartient a joelle
    $groupe_de_joelle = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('Le groupe de joelle');
    
    $this->procedure_add_element(
      'Element mis dans le groupe de joelle', 
      'http://www.youtube.com/watch?v=WC8qb_of04E', 
      array($hardtek->getId(), $tribe->getId()),
      $groupe_de_joelle->getSlug()
    );
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $this->exist('li:contains("Element mis dans le groupe de joelle")');
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Element mis dans le groupe de joelle')
    ;
    $this->assertTrue(!is_null($element));
    
    if (!is_null($element))
    {
      $this->assertEquals($groupe_de_joelle->getId(), $element->getGroup()->getId());
    }
    else
    {
      $this->assertTrue(false);
    }
  }
  
  /**
   * L'ajout a un group qui n'est pas a sois, ou qui n'est pas open
   * doit être impossible.
   */
  public function testAddElementAtGroupFailure()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    // Un groupe no open
    $dudeldrum = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('DUDELDRUM');
    
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
        
    // Nous tentons d'ouvrir l'url d'ajout d'élément avec un groupe qui n'est pas ouvert
    // et qui n'appartient pas a l'utilisateur connecté
    $this->crawler = $this->client->request(
      'POST', 
      $this->generateUrl('element_add', array('group_slug' => $dudeldrum->getSlug())),
      array(
        'element_add[name]' => 'Yohoho trululu',
        'element_add[url]'  => 'http://www.youtube.com/watch?v=WC8qb_of04E',
        'element_add[tags]['.$hardtek->getId().']' => $hardtek->getId(),
        'element_add[tags]['.$tribe->getId().']' => $tribe->getId()
      )
    );
    
    $this->isResponseNotFound();
  }
  
  /**
   * Test de la fonction ajax de récupération de plus d'éléments sur la page
   * de profil
   */
  public function testFilterUserElements()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $paul = $this->getUser();
    $bux  = $this->getUser('bux');
     
    $hardtek_id = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->findOneByName('Hardtek')->getId();
    $hardcore_id   = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->findOneByName('Hardcore')->getId();
    $electro_id   = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->findOneByName('Electro')->getId();
    $metal_id   = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->findOneByName('Metal')->getId();
    
    
    // Ouverture de la page favoris
    $this->crawler = $this->client->request('GET', $this->generateUrl('show_user', array('slug' => $bux->getSlug())));
    
    // On doit voir deux elements pour paul
    $this->exist('span.element_name:contains("Ed Cox - La fanfare des teuffeurs (Hardcordian)")');
    $this->exist('span.element_name:contains("Babylon Pression - Des Tasers et des Pauvres")');
    $this->exist('span.element_name:contains("AZYD AZYLUM Live au Café Provisoire")');
    $this->exist('span.element_name:contains("SOULFLY - Prophecy")');
    $this->exist('span.element_name:contains("KoinkOin - H5N1")');
    $this->exist('span.element_name:contains("Antropod - Polakatek")');
    $this->exist('span.element_name:contains("Dtc che passdrop")');
    $this->exist('span.element_name:contains("Heretik System Popof - Resistance")');
    
    // Récupération de la liste avec la kekete ajax pour Tribe
    $url = $this->generateUrl('show_elements_get', array(
      'type'          => 'user',
      'object_id'     => $bux->getId(),
      'tags_ids_json' => json_encode(array($hardtek_id))
    ));
    $this->crawler = $this->client->request('GET', $url, array(), array(), array(
      'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    
    $response_content = json_decode($this->client->getResponse()->getContent(), true);
    $html = $response_content['html'];
    
    $this->assertTrue(strpos($html, 'Ed Cox - La fanfare des teuffeurs (Hardcordian)') === false);
    $this->assertTrue(strpos($html, 'Babylon Pression - Des Tasers et des Pauvres') === false);
    $this->assertTrue(strpos($html, 'AZYD AZYLUM Live au Café Provisoire') === false);
    $this->assertTrue(strpos($html, 'SOULFLY - Prophecy') === false);
    $this->assertTrue(strpos($html, 'KoinkOin - H5N1') !== false);
    $this->assertTrue(strpos($html, 'Antropod - Polakatek') !== false);
    $this->assertTrue(strpos($html, 'Dtc che passdrop') !== false);
    $this->assertTrue(strpos($html, 'Heretik System Popof - Resistance') !== false);
    
    // Récupération de la liste avec la kekete ajax pour Hardtek
    $url = $this->generateUrl('show_elements_get', array(
      'type'          => 'user',
      'object_id'     => $bux->getId(),
      'tags_ids_json' => json_encode(array($metal_id))
    ));
    
    $this->crawler = $this->client->request('GET', $url, array(), array(), array(
      'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    
    $this->isResponseSuccess();
    
    $response_content = json_decode($this->client->getResponse()->getContent(), true);
    $html = $response_content['html'];
    
    $this->assertTrue(strpos($html, 'Ed Cox - La fanfare des teuffeurs (Hardcordian)') === false);
    $this->assertTrue(strpos($html, 'Babylon Pression - Des Tasers et des Pauvres') !== false);
    $this->assertTrue(strpos($html, 'AZYD AZYLUM Live au Café Provisoire') !== false);
    $this->assertTrue(strpos($html, 'SOULFLY - Prophecy') !== false);
    $this->assertTrue(strpos($html, 'KoinkOin - H5N1') === false);
    $this->assertTrue(strpos($html, 'Antropod - Polakatek') === false);
    $this->assertTrue(strpos($html, 'Dtc che passdrop') === false);
    $this->assertTrue(strpos($html, 'Heretik System Popof - Resistance') === false);
    
    // Récupération de la liste avec la kekete ajax pour Tribe + Hardtek
    $url = $this->generateUrl('show_elements_get', array(
      'type'          => 'user',
      'object_id'     => $bux->getId(),
      'tags_ids_json' => json_encode(array($hardtek_id, $hardcore_id, $electro_id, $metal_id))
    ));
    
    $this->crawler = $this->client->request('GET', $url, array(), array(), array(
      'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    
    $this->isResponseSuccess();
    
    $response_content = json_decode($this->client->getResponse()->getContent(), true);
    $html = $response_content['html'];
    
    $this->assertTrue(strpos($html, 'Ed Cox - La fanfare des teuffeurs (Hardcordian)') !== false);
    $this->assertTrue(strpos($html, 'Babylon Pression - Des Tasers et des Pauvres') !== false);
    $this->assertTrue(strpos($html, 'AZYD AZYLUM Live au Café Provisoire') !== false);
    $this->assertTrue(strpos($html, 'SOULFLY - Prophecy') !== false);
    $this->assertTrue(strpos($html, 'KoinkOin - H5N1') !== false);
    $this->assertTrue(strpos($html, 'Antropod - Polakatek') !== false);
    $this->assertTrue(strpos($html, 'Dtc che passdrop') !== false);
    $this->assertTrue(strpos($html, 'Heretik System Popof - Resistance') !== false);
  }
  
  /**
   * Test de la fonction ajax de récupération de plus d'éléments sur la page
   * de profil
   */
  public function testFilterGroupElements()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $paul = $this->getUser();
    $group  = $this->getGroup('dudeldrum');
     
    $medieval_id = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->findOneByName('Medieval')->getId();
    
    // Ouverture de la page favoris
    $this->crawler = $this->client->request('GET', $this->generateUrl('show_group', array('slug' => $group->getSlug())));
    
    // On doit voir deux elements pour paul
    $this->exist('span.element_name:contains("DUDELDRUM")');
    
    // Récupération de la liste avec la kekete ajax pour Tribe
    $url = $this->generateUrl('show_elements_get', array(
      'type'          => 'group',
      'object_id'     => $group->getId(),
      'tags_ids_json' => json_encode(array($medieval_id))
    ));
    $this->crawler = $this->client->request('GET', $url, array(), array(), array(
      'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    
    $response_content = json_decode($this->client->getResponse()->getContent(), true);
    $html = $response_content['html'];
    
    $this->assertTrue(strpos($html, 'DUDELDRUM') !== false);
    
  }
  
  /**
   * Test de la récupération de nouveaux éléments
   * 
   */
  public function testSeeNew()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    $bux = $this->getUser();
    $bob = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bob')
    ;
    $hardtek_id = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek')->getId();
    $tribe_id   = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe')->getId();
  
    // On récupère l'id du dernier element affiché
    $extract = $this->crawler->filter('ul.elements li.element')
       ->extract(array('id'));
    
    $first_id = (int)str_replace('element_', '', $extract[0]);
    
    $url = $this->generateUrl('element_new_count', array('refid' => $first_id));
    // On effectue la kekete ajax
    $crawler = $this->client->request(
      'POST', 
      $url, 
      array('element_search_form' => array(
        'network' => ElementSearcher::NETWORK_PUBLIC,
        'tags'    => json_encode(array($hardtek_id, $tribe_id))
      )), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    $this->assertEquals($response['count'], '0');
    $this->assertEquals($response['message'], '');
    
    $this->addElementAjax('NewElement One', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)));
    $this->addElementAjax('NewElement Two', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)));
    
    // On refait la même demande, deux éléments sont nouveaux
    $url = $this->generateUrl('element_new_count', array('refid' => $first_id));
    // On effectue la kekete ajax
    $crawler = $this->client->request(
      'POST', 
      $url, 
      array('element_search_form' => array(
        'network' => ElementSearcher::NETWORK_PUBLIC,
        'tags'    => json_encode(array($hardtek_id, $tribe_id))
      )), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    $this->assertEquals($response['count'], '2');
    
    // Si on demande la récupération des nouveaux éléments on doit les obtenirs
    $url = $this->generateUrl('element_new_get', array('refid' => $first_id));
    // On effectue la kekete ajax
    $crawler = $this->client->request(
      'POST', 
      $url, 
      array('element_search_form' => array(
        'network' => ElementSearcher::NETWORK_PUBLIC,
        'tags'    => json_encode(array($hardtek_id, $tribe_id))
      )), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    $this->assertEquals($response['count'], '0');
    $this->assertTrue(!is_null($response['html']));
    
    $this->assertTrue(strpos($response['html'], 'NewElement One') !== false);
    $this->assertTrue(strpos($response['html'], 'NewElement Two') !== false);
    
    // On ajoute 10 autres éléments (NOTE: le 10 est hardcodé dans ce test
    // , c'est la limite d'affichage d'éléments)
    $this->addElementAjax('NewElement 3', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)));
    $this->addElementAjax('NewElement 4', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)));
    $this->addElementAjax('NewElement 5', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)));
    $this->addElementAjax('NewElement 6', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)));
    $this->addElementAjax('NewElement 7', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)));
    $this->addElementAjax('NewElement 8', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)));
    $this->addElementAjax('NewElement 9', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)));
    $this->addElementAjax('NewElement 10', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)));
    $this->addElementAjax('NewElement 11', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)));
    $this->addElementAjax('NewElement 12', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)));
  
    // On va refaire un count des nouveaux éléments
    // Ca devrat nous répondree 12 puisque on utilise l'id de référence du début
    
    // On refait la même demande, deux éléments sont nouveaux
    $url = $this->generateUrl('element_new_count', array('refid' => $first_id));
    // On effectue la kekete ajax
    $crawler = $this->client->request(
      'POST', 
      $url, 
      array('element_search_form' => array(
        'network' => ElementSearcher::NETWORK_PUBLIC,
        'tags'    => json_encode(array($hardtek_id, $tribe_id))
      )), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    $this->assertEquals($response['count'], '12');
    
    // Si on demande la récupération des nouveaux éléments on doit en obtenir 10
    // et en rester 2
    $url = $this->generateUrl('element_new_get', array('refid' => $first_id));
    // On effectue la kekete ajax
    $crawler = $this->client->request(
      'POST', 
      $url, 
      array('element_search_form' => array(
        'network' => ElementSearcher::NETWORK_PUBLIC,
        'tags'    => json_encode(array($hardtek_id, $tribe_id))
      )),  
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    $this->assertEquals($response['count'], '2');
    $this->assertTrue(!is_null($response['html']));
    $this->assertTrue(strpos($response['html'], 'NewElement One') !== false);
    $this->assertTrue(strpos($response['html'], 'NewElement Two') !== false);
    $this->assertTrue(strpos($response['html'], 'NewElement 3') !== false);
    $this->assertTrue(strpos($response['html'], 'NewElement 4') !== false);
    $this->assertTrue(strpos($response['html'], 'NewElement 5') !== false);
    $this->assertTrue(strpos($response['html'], 'NewElement 6') !== false);
    $this->assertTrue(strpos($response['html'], 'NewElement 7') !== false);
    $this->assertTrue(strpos($response['html'], 'NewElement 8') !== false);
    $this->assertTrue(strpos($response['html'], 'NewElement 9') !== false);
    $this->assertTrue(strpos($response['html'], 'NewElement 10') !== false);
    $this->assertTrue(strpos($response['html'], 'NewElement 11') === false);
    $this->assertTrue(strpos($response['html'], 'NewElement 12') === false);
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('NewElement 10')
    ;
    // notre nouvel id référent en celui de NewElement 10
    // On renouvelle la demande, il ne doit y avoir que 2 élément nouveau a afficher
    $url = $this->generateUrl('element_new_count', array('refid' => $element->getId()));
    // On effectue la kekete ajax
    $crawler = $this->client->request(
      'POST', 
      $url, 
      array('element_search_form' => array(
        'network' => ElementSearcher::NETWORK_PUBLIC,
        'tags'    => json_encode(array($hardtek_id, $tribe_id))
      )), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    $this->assertEquals($response['count'], '2');
    
  }
  
  public function testMoreElements()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    $bux = $this->getUser();
    
    // On récupère l'id du dernier element affiché
    $extract = $this->crawler->filter('ul.elements li.element')
       ->extract(array('id'));
    
    // !!!!!! NOTE !!!!! : 9 est hardcodé ici: la config est de 10 éléments en affichage
    $id_limit = (int)str_replace('element_', '', $extract[9]);
    
    $url = $this->generateUrl('search_elements_more', array(
      'context' => 'home',
      'id_limit'  => $id_limit
    ));
    
    // We want mooooore
    // On effectue la kekete ajax
    $crawler = $this->client->request(
      'GET', 
      $url, 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    // TODO: améliorer ce test !!
//    $this->assertEquals($response['count'], '4');  // HARDCODE fixtures
//    $this->assertEquals($response['end'], false);  // HARDCODE fixtures
    
  }
  
  public function testAddedElementToGroup()
  {
    $this->client = self::createClient();
    $this->connectUser('bob', 'toor');
    // bob administre le groupe fans de psytrance
    $bob = $this->getUser();
    
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
    $psytrance = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Psytrance');
    
    $fan_de_psy = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('Fans de psytrance');
    
    // On envoie d'abord un élément sans tags associé
    // On ne devra pas avoir de proposition de groupe
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
              'name'   => 'Musique 1976824673',
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
    $this->assertEquals($response['groups'], array());
    
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Musique 1976824673')
    ;
    $this->assertTrue(!is_null($element));
    
    // Maintenant on ajout un élément qui a comme tag psytrance
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
              'name'   => 'Musique 4gbz65g4afa',
              'url'    => 'http://www.youtube.com/watch?v=WC8qb_of04E',
              'tags'   => json_encode(array($psytrance->getId()))
          )
        
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Musique 4gbz65g4afa')
    ;
    $this->assertTrue(!is_null($element));
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    
    $this->assertEquals($response['status'], 'success');
    $this->assertEquals($response['groups'], array(
      array(
        'name' => $fan_de_psy->getName(),
        'id'   => $fan_de_psy->getId(),
        'url'  => $this->generateUrl('ajax_set_element_group', array(
          'token'      => $this->getUser()->getPersonalHash($element->getId()),
          'element_id' => $element->getId(),
          'group_id'   => $fan_de_psy->getId()
        ))
      )
    ));
    
    // Du coup on effectue la diffusion de l'élément dans le groupe
    $crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_set_element_group', array(
        'element_id' => $element->getId(),
        'group_id'   => $fan_de_psy->getId(),
        'token'      => $this->getUser()->getPersonalHash($element->getId())
      )), 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneBy(array(
        'name'  => 'Musique 4gbz65g4afa',
        'group' => $fan_de_psy->getId()
      ))
    ;
    $this->assertTrue(!is_null($element));
    
  }
  
  public function testElementNeedTags()
  {
    
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
    
    /*
     *  Ajout d'un élément avec succés
     */
    $this->procedure_add_element(
      'Mon bel element 98he4gez', 
      'http://www.youtube.com/watch?v=WC8qb_of04E', 
      array($hardtek->getId(), $tribe->getId()),
      null,
      true
    );
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $this->exist('li:contains("Mon bel element 98he4gez")');
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Mon bel element 98he4gez')
    ;
    $this->assertTrue(!is_null($element));
    $this->assertTrue($element->getNeedTags());
      
    // On va voir si elle se trouve dans la liste des demandes avec paul
    $this->disconnectUser();
    $this->connectUser('paul', 'toor');
    
    $paul = $this->getUser();
    
    // On ouvre la page
    $this->goToPage($this->generateUrl('element_show_need_tags'));
    
    // On y voir le partage
    $this->exist('li:contains("Mon bel element 98he4gez")');
    
    // On fait une proposition
    $crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_element_propose_tags_proceed', 
        array('element_id' => $element->getId(), 'token' => $paul->getPersonalHash())
      ), 
      array(
        'element_tag_proposition_'.$element->getId() => array(
          'tags' => json_encode(array($hardtek->getId(), $tribe->getId()))
        )
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // On se reconnecte avec bux pour l'accepter
    $this->disconnectUser();
    $this->connectUser('bux', 'toor');
    
    $bux = $this->getUser();
    
    // On récupère la proposition
    $propositions = $this->getDoctrine()->getManager()
      ->createQuery('SELECT p, t FROM MuzichCoreBundle:ElementTagsProposition p'
        .' JOIN p.tags t WHERE p.element = :eid AND p.user = :uid')
      ->setParameters(array(
        'eid' => $element->getId(),
        'uid' => $paul->getId()
      ))
      ->getResult()
    ;
    
    $this->assertEquals(1, count($propositions));
    $proposition_paul = $propositions[0];
    
    // On accepte la proposition de paul
    $url_accept_paul = $this->generateUrl('ajax_element_proposed_tags_accept', array(
      'proposition_id' => $proposition_paul->getId(),
      'token'          => $bux->getPersonalHash($proposition_paul->getId())
    ));
    
    $crawler = $this->client->request(
      'GET',
      $url_accept_paul, 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // On check l'élémetn pour voir si il est bien plsuen demande de tags
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Mon bel element 98he4gez')
    ;
    $this->assertTrue(!is_null($element));
    $this->assertFalse($element->getNeedTags());
  }
  
  
}