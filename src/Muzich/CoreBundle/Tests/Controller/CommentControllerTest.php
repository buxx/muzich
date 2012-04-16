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
  
  /**
   * Test du signalement de commentaires non appropriés
   * Puis de leurs modérations
   */
  public function testAlertAndModerate()
  {
    // Première chose: dans ce test on a besoin de tester la modération.
    // Du coup bux doit être promus admin
    $this->client = self::createClient();
    $output = $this->runCommand(
      $this->client, 
      "fos:user:promote bux ROLE_ADMIN"
    );

    /**
     * Scénario: joelle signale deux commentaires: un de bux et un de paul
     * sur l'élément d'ed cox.
     * En moderation 
     * * un commentaire (bux) est effectivement refusé: 
     *   (TODO dans le code) Le compteur de mauvaise attitude augmente
     * * un commentaire (paul) est considéré comme ok, le compteur (faux signalement) 
     *   de joelle s'incrémente de 1.
     */
    
    $em = $this->client->getKernel()->getContainer()->get('doctrine')->getEntityManager();
    $this->connectUser('joelle', 'toor');
    $joelle = $this->getUser();
    $joelle_fake_alerts = $joelle->getBadReportCount();
    
    // On récupère les deux commentaires
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    $cm = new CommentsManager($element->getComments());
    $comment_bux  = $cm->get(0);
    $comment_paul = $cm->get(1);
    
    // En base rien n'a encore été touché
    $this->assertEquals(0, $cm->countCommentAlert());
    $this->assertEquals(null, $element->getCountCommentReport());
    
    // joelle signale deux éléments
    $this->crawler = $this->client->request(
      'GET', 
      $this->generateUrl('ajax_alert_comment', array(
        'element_id' => $element->getId(),
        'date'       => $comment_bux['d'],
        'token'      => $joelle->getPersonalHash()
      )),
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // Les données en bases ont évolués
    // On récupère les deux commentaires
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    $cm = new CommentsManager($element->getComments());
    $comment_bux  = $cm->get(0);
    $comment_paul = $cm->get(1);
    
    $this->assertEquals(1, $cm->countCommentAlert());
    $this->assertEquals(1, $element->getCountCommentReport());
    
    // deuxième signalement
    $this->crawler = $this->client->request(
      'GET', 
      $this->generateUrl('ajax_alert_comment', array(
        'element_id' => $element->getId(),
        'date'       => $comment_paul['d'],
        'token'      => $joelle->getPersonalHash()
      )),
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // Les données en bases ont évolués
    // On récupère les deux commentaires
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    $cm = new CommentsManager($element->getComments());
    $comment_bux  = $cm->get(0);
    $comment_paul = $cm->get(1);
    
    $this->assertEquals(2, $cm->countCommentAlert());
    $this->assertEquals(2, $element->getCountCommentReport());
    
    /**
     * On passe maintenant a la modération
     */
    
    $this->disconnectUser();
    $this->connectUser('bux', 'toor');
    $bux = $this->getUser();
    
    // bux ouvre la page de modération des commentaires
    $this->crawler = $this->client->request('GET', $this->generateUrl('moderate_comments_index'));
    
    // On voit les deux commentaires signalés dans la liste
    $this->exist('li.comment:contains("C\'est trop bon hein ?")');
    $this->exist('li.comment:contains("C\'est pas mal en effet")');
    
    // Refus de celui de bux
    $this->crawler = $this->client->request(
      'GET', 
      $this->generateUrl('moderate_comment_refuse', array(
        'element_id' => $element->getId(),
        'date'       => $comment_bux['d']
      )),
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // TODO: check du compteur de mauvais comportements de bux
    
    // la base est a jour
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    $cm = new CommentsManager($element->getComments());
    
    $this->assertEquals(1, $cm->countCommentAlert());
    $this->assertEquals(1, $element->getCountCommentReport());
    
    // Clean de celui de paul
    $this->crawler = $this->client->request(
      'GET', 
      $this->generateUrl('moderate_comment_clean', array(
        'element_id' => $element->getId(),
        'date'       => $comment_paul['d']
      )),
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // la base est a jour
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    $cm = new CommentsManager($element->getComments());
    
    $this->assertEquals(0, $cm->countCommentAlert());
    $this->assertEquals(0, $element->getCountCommentReport());
    
    // Mais comme joelle a signalé un commentaire considéré comme ok par la modération
    // joelle vois son compteur de faux signalement incrémenté
    $joelle = $this->getUser('joelle');
    $this->assertEquals($joelle_fake_alerts+1, $joelle->getBadReportCount());
    
    // Et si on se rend sur la page home, le commentaire de bux a disparu
    $this->crawler = $this->client->request('GET', $this->generateUrl('home'));
    
    $this->exist('li.comment:contains("C\'est pas mal en effet")');
    $this->notExist('li.comment:contains("C\'est trop bon hein ?")');
  }
  
}