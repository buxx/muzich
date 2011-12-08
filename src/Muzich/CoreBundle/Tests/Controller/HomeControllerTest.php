<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;
use Muzich\CoreBundle\Searcher\ElementSearcher;

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
    $this->exist('form[action="'.($url = $this->generateUrl('search_elements')).'"]');
    $this->exist('form[action="'.$url.'"] select[id="element_search_form_network"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    $hardtek_id = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek')->getId();
    $tribe_id   = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe')->getId();
    
    // On récupére le formulaire de filtrage
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    
    // On décoche les tags
    foreach ($this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findAll() as $tag)
    {
      $form['element_search_form[tags]['.$tag->getId().']']->untick();
    }
    
    // On met ce que l'on veut dans le form
    $form['element_search_form[network]'] = ElementSearcher::NETWORK_PUBLIC;
    $form['element_search_form[tags]['.$hardtek_id.']'] = $hardtek_id;
    $form['element_search_form[tags]['.$tribe_id.']'] = $tribe_id;
    $this->submit($form);
    
    $this->client->submit($form);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $this->assertTrue($this->getSession()->has('user.element_search.params'));
    $this->assertEquals(array(
        'network'   => ElementSearcher::NETWORK_PUBLIC,
        'tags'      => array(
          $hardtek_id, $tribe_id
        ),
        'count'     => $this->getContainer()->getParameter('search_default_count'),
        'user_id'   => null,
        'group_id'  => null,
        'favorite' => false
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
    $this->exist('h2:contains("'.$jean->getName().'")');
    
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
    $this->exist('h2:contains("'.$fdp->getName().'")');
    
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
      array($hardtek->getId(), $tribe->getId())
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
      'Mon bel element mais qui a un nom trop court la vache oui trop long hohoho', 
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
  
}