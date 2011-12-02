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
  
}