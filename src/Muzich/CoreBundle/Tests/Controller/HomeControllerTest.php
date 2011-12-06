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
    $this->connectUser('bux', 'toor');

    // Présence du formulaire d'ajout d'un élément
    $this->exist('form[action="'.($url = $this->generateUrl('element_add')).'"]');
    $this->exist('form[action="'.$url.'"] input[id="element_add_name"]');
    $this->exist('form[action="'.$url.'"] input[id="element_add_url"]');
    $this->exist('form[action="'.$url.'"] select[id="element_add_group"]');
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
    $this->connectUser('bux', 'toor');
    
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
    
    // Un groupe open
    $fan_de_psy = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('Fans de psytrance');
    
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
    $this->connectUser('bux', 'toor');
    
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
    
    // Un groupe no open
    $dudeldrum = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('DUDELDRUM');
    
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
    
    $this->notExist('li:contains("Mon bel element")');
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Mon bel element')
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
    
    $this->notExist('li:contains("Mon bel element")');
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Mon bel element')
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
  
  public function testAddElementAtGroupFailure()
  {
    $this->connectUser('bux', 'toor');
    
    /*
     * Ajout d'un élément lié a un groupe (success)
     */
    $this->procedure_add_element(
      'Mon bel element', 
      'http://www.youtube.com/watch?v=WC8qb_of04E', 
      array($hardtek->getId(), $tribe->getId()),
      $fan_de_psy->getId()
    );
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $this->exist('li:contains("Mon bel element")');
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Mon bel element')
    ;
    $this->assertTrue(!is_null($element));
    if (!is_null($element))
    {
      $this->assertEquals($fan_de_psy->getId(), $element->getGroup()->getId());
    }
  }
  
  /**
   * L'ajout d'un Element a un de ses groupe ne doit pas poser de problème
   */
  public function testAddElementAtMyGroupSuccess()
  {
    
  }
  
}