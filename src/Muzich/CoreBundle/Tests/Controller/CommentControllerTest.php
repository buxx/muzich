<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;
use Muzich\CoreBundle\Managers\CommentsManager;

/**
 * Test des commentaires. Doit couvrir:
 * 
 * * La consultation de commentaires en fixtures
 * * L'ajout de commentaire
 * * La modification d'un commentaire
 * * La suppression d'un commentaire
 * 
 */
class CommentControllerTest extends FunctionalTest
{
 
  public function testView()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    // On est sur la page home, d'après les fixtures on a des coms en dom
    $this->exist('div.comments:contains("C\'est trop bon hein ?")');
    $this->exist('div.comments:contains("C\'est pas mal en effet")');
    $this->exist('li.element a.display_comments');
    $this->exist('li.element a.hide_comments');
    $this->exist('li.element a.add_comment');
    $this->exist('div.comments a.add_comment');
  }
  
  public function testAddEditDelete()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // On va ajouter un commentaire a la toute dernière musique posté par bux
    $this->crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_add_comment', array(
        'element_id' => $element->getId(),
        'token'      => $this->getUser()->getPersonalHash()
      )), 
      array(
          'comment' => "J'ai réécouté et ouaa je kiff BrOOO"
        
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // On ré-affiche la page home pour voir si le commentaire y est
    $this->crawler = $this->client->request('GET', $this->generateUrl('home'));
    $this->exist('div.comments:contains("J\'ai réécouté et ouaa je kiff BrOOO")');
    
    $extract = $this->crawler->filter('div.comments li:contains("J\'ai réécouté et ouaa je kiff BrOOO")')
      ->extract(array('id'));
    $id = $extract[0];
    
    // Faut que l'on récupère la date
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    $cm = new CommentsManager($element->getComments());
    $comment = $cm->getLast();
    
    $this->assertEquals($comment['c'], "J'ai réécouté et ouaa je kiff BrOOO");
        
    // On effectue une modification de ce commentaire
    $this->crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_update_comment', array(
        'element_id' => $element->getId(),
        'date'       => $comment['d'],
        'dom_id'     => $id,
        'token'      => $this->getUser()->getPersonalHash()
      )),
      array(
          'comment' => "Je me modifie mon com kwaa"
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    $cm = new CommentsManager($element->getComments());
    $comment = $cm->getLast();
    
    $this->assertEquals($comment['c'], "Je me modifie mon com kwaa");
    // Il y a une date d'edition
    $this->assertTrue(array_key_exists('e', $comment));
    
    // On ré-affiche la page home pour voir si le commentaire modifié y est
    $this->crawler = $this->client->request('GET', $this->generateUrl('home'));
    $this->exist('div.comments:contains("Je me modifie mon com kwaa")');
    
    // maintenant on le supprime
    $this->crawler = $this->client->request(
      'GET', 
      $this->generateUrl('ajax_delete_comment', array(
        'element_id' => $element->getId(),
        'date'       => $comment['d'],
        'token'      => $this->getUser()->getPersonalHash()
      )),
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    $cm = new CommentsManager($element->getComments());
    $comment = $cm->getLast();
    
    $this->assertNotEquals($comment['c'], "Je me modifie mon com kwaa");
    
    // On ré-affiche la page home pour voir si le commentaire modifié y est
    $this->crawler = $this->client->request('GET', $this->generateUrl('home'));
    $this->notExist('div.comments:contains("Je me modifie mon com kwaa")');
  }
  
  /**
   * On test ici la sécurité nous empêchant de modifier / supprimer le comm
   * d'un autre.
   */
  public function testFails()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Babylon Pression - Des Tasers et des Pauvres')
    ;
    
    $cm = new CommentsManager($element->getComments());
    // D'après fixtures: Ce dernier commentaire est à bux
    $comment = $cm->getLast();
    
    $this->assertEquals($comment['c'], "Je répond 13");
    
    // On récupère l'id dom
    $extract = $this->crawler->filter('div.comments li:contains("Je répond 13")')
      ->extract(array('id'));
    $id = $extract[0];
    
    // On essaie de le modifier
    $this->crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_update_comment', array(
        'element_id' => $element->getId(),
        'date'       => $comment['d'],
        'dom_id'     => $id,
        'token'      => $this->getUser()->getPersonalHash()
      )),
      array(
          'comment' => "Je répond 13 HACKED"
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'error');
    
    // On essaie de le supprimer
    $this->crawler = $this->client->request(
      'GET', 
      $this->generateUrl('ajax_delete_comment', array(
        'element_id' => $element->getId(),
        'date'       => $comment['d'],
        'token'      => $this->getUser()->getPersonalHash()
      )),
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'error');
  }
  
  
}