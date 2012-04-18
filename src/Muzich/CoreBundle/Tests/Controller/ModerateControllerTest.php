<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;
use Muzich\CoreBundle\Managers\CommentsManager;

/**
 * Test des procédure de modérations
 * 
 * 
 */
class ModerateControllerTest extends FunctionalTest
{
 
//  public function testReportElement()
//  {
//    $this->client = self::createClient();
//    $this->connectUser('paul', 'toor');
//    
//    $paul = $this->getUser();
//    $paul_bad_reports_count = $paul->getBadReportCount();
//    $bux_moderated_element_count = $this->getUser('bux')->getModeratedElementCount();
//    
//    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
//      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
//    ;
//    
//    // Paul signale cet élément comme pas bien
//    $url = $this->generateUrl('ajax_report_element', array(
//      'element_id' => $element_ed->getId(),
//      'token'      => $paul->getPersonalHash()
//    ));
//    
//    $crawler = $this->client->request(
//      'GET', 
//      $url, 
//      array(), 
//      array(), 
//      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
//    );
//    
//    $this->isResponseSuccess();
//    
//    $response = json_decode($this->client->getResponse()->getContent(), true);
//    $this->assertEquals($response['status'], 'success');
//    
//    // On check en base
//    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
//      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
//    ;
//    
//    $this->assertEquals($element_ed->getCountReport(), 1);
//    $this->assertEquals($element_ed->getReportIds(), array((string)$paul->getId()));
//    
//    // Si il effectue le signalement une deuxième fois sur le même element
//    // Ca ne doit pas bouger puisqu'il l'a déjà fait
//    $url = $this->generateUrl('ajax_report_element', array(
//      'element_id' => $element_ed->getId(),
//      'token'      => $paul->getPersonalHash()
//    ));
//    
//    $crawler = $this->client->request(
//      'GET', 
//      $url, 
//      array(), 
//      array(), 
//      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
//    );
//    
//    $this->isResponseSuccess();
//    
//    $response = json_decode($this->client->getResponse()->getContent(), true);
//    $this->assertEquals($response['status'], 'success');
//    
//    // On check en base
//    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
//      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
//    ;
//    
//    $this->assertEquals($element_ed->getCountReport(), 1);
//    $this->assertEquals($element_ed->getReportIds(), array((string)$paul->getId()));
//    
//    // Paul va ausi signaler un autre élément
//    // Babylon Pression - Des Tasers et des Pauvres
//    
//    // On check en base
//    $element_bab = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
//      ->findOneByName('Babylon Pression - Des Tasers et des Pauvres')
//    ;
//    
//    $this->assertEquals($element_bab->getCountReport(), 0);
//    $this->assertEquals($element_bab->getReportIds(), null);
//    
//    // Si il effectue le signalement une deuxième fois sur le même element
//    // Ca ne doit pas bouger puisqu'il l'a déjà fait
//    $url = $this->generateUrl('ajax_report_element', array(
//      'element_id' => $element_bab->getId(),
//      'token'      => $paul->getPersonalHash()
//    ));
//    
//    $crawler = $this->client->request(
//      'GET', 
//      $url, 
//      array(), 
//      array(), 
//      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
//    );
//    
//    $this->isResponseSuccess();
//    
//    $response = json_decode($this->client->getResponse()->getContent(), true);
//    $this->assertEquals($response['status'], 'success');
//    
//    // On check en base
//    $element_bab = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
//      ->findOneByName('Babylon Pression - Des Tasers et des Pauvres')
//    ;
//    
//    $this->assertEquals($element_bab->getCountReport(), 1);
//    $this->assertEquals($element_bab->getReportIds(), array((string)$paul->getId()));
//    
//    // On passe a joelle
//    
//    $this->disconnectUser();
//    // On connecte joelle pour faire le même test sur le même élément
//    $this->connectUser('joelle', 'toor');
//    
//    $joelle = $this->getUser();
//    $joelle_bad_reports_count = $joelle->getBadReportCount();
//    
//    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
//      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
//    ;
//    
//    // Paul signale cet élément comme pas bien
//    $url = $this->generateUrl('ajax_report_element', array(
//      'element_id' => $element_ed->getId(),
//      'token'      => $joelle->getPersonalHash()
//    ));
//    
//    $crawler = $this->client->request(
//      'GET', 
//      $url, 
//      array(), 
//      array(), 
//      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
//    );
//    
//    $this->isResponseSuccess();
//    
//    $response = json_decode($this->client->getResponse()->getContent(), true);
//    $this->assertEquals($response['status'], 'success');
//    
//    // On check en base
//    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
//      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
//    ;
//    
//    $this->assertEquals($element_ed->getCountReport(), 2);
//    $this->assertEquals($element_ed->getReportIds(), array((string)$paul->getId(), (string)$joelle->getId()));
//    
//    // Si elle effectue le signalement une deuxième fois sur le même element
//    // Ca ne doit pas bouger puisqu'elle l'a déjà fait
//    $url = $this->generateUrl('ajax_report_element', array(
//      'element_id' => $element_ed->getId(),
//      'token'      => $joelle->getPersonalHash()
//    ));
//    
//    $crawler = $this->client->request(
//      'GET', 
//      $url, 
//      array(), 
//      array(), 
//      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
//    );
//    
//    $this->isResponseSuccess();
//    
//    $response = json_decode($this->client->getResponse()->getContent(), true);
//    $this->assertEquals($response['status'], 'success');
//    
//    // On check en base
//    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
//      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
//    ;
//    
//    $this->assertEquals($element_ed->getCountReport(), 2);
//    $this->assertEquals($element_ed->getReportIds(), array((string)$paul->getId(), (string)$joelle->getId()));
//    
//    /*
//     * Maintenant on va aller modérer ça coté modération.
//     */
//    
//    // On donne le status d'admin a bob
//    $output = $this->runCommand(
//      $this->client, 
//      "fos:user:promote bob ROLE_ADMIN"
//    );
//    
//    // On connecte bob
//    $this->disconnectUser();
//    $this->connectUser('bob', 'toor');
//    
//    // Sur la page de modération d'élément on peux voir l'élément dans la liste
//    $this->crawler = $this->client->request('GET', $this->generateUrl('moderate_elements_index'));
//    $this->isResponseSuccess();
//    
//    $this->exist('li#mod_element_'.$element_ed->getId());
//    $this->exist('li#mod_element_'.$element_bab->getId());
//    
//    // Première action, effectivement le partage ed cox doit être refusé par la modération
//    $this->crawler = $this->client->request(
//      'GET', 
//      $this->generateUrl('moderate_element_delete', array(
//        'element_id' => $element_ed->getId()
//      )),
//      array(), 
//      array(), 
//      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
//    );
//    
//    $this->isResponseSuccess();
//    
//    $response = json_decode($this->client->getResponse()->getContent(), true);
//    $this->assertEquals($response['status'], 'success');
//    
//    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
//      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
//    ;
//    $this->assertEquals(true, is_null($element_ed));
//    // Condéquences, le proprio (bux) vois son compteur d'élément modéré augmenter
//    $this->assertEquals($bux_moderated_element_count+1, $this->getUser('bux')->getModeratedElementCount());
//    // Le compteur de mauvai signalemetn de paul n'a pas bougé par contre.
//    $this->assertEquals($paul_bad_reports_count, $this->getUser('paul')->getBadReportCount());
//    // Ni celui de joelle
//    $this->assertEquals($joelle_bad_reports_count, $this->getUser('joelle')->getBadReportCount());
//    
//    // Deuxième action on considère que l'autre élément n'a pas a être refusé
//    $this->crawler = $this->client->request(
//      'GET', 
//      $this->generateUrl('moderate_element_clean', array(
//        'element_id' => $element_bab->getId()
//      )),
//      array(), 
//      array(), 
//      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
//    );
//    
//    $this->isResponseSuccess();
//    
//    $response = json_decode($this->client->getResponse()->getContent(), true);
//    $this->assertEquals($response['status'], 'success');
//    
//    $element_bab = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
//      ->findOneByName('Babylon Pression - Des Tasers et des Pauvres')
//    ;
//    
//    $this->assertEquals(false, is_null($element_bab));
//    $this->assertEquals($element_bab->getCountReport(), 0);
//    $this->assertEquals($element_bab->getReportIds(), null);
//    
//    // Condéquences, le proprio (bux) ne vois pas son compteur d'élément modéré augmenter encore
//    //                                              +1 car c'est pour la modo juste avant
//    $this->assertEquals($bux_moderated_element_count+1, $this->getUser('bux')->getModeratedElementCount());
//    // Le compteur de mauvais signalemetn de paul a augmenter d'un
//    $this->assertEquals($paul_bad_reports_count+1, $this->getUser('paul')->getBadReportCount());
//    // Celui de joelle non, elle n'a rien a voir avec ce signalement
//    $this->assertEquals($joelle_bad_reports_count, $this->getUser('joelle')->getBadReportCount());
//  }
  
  /**
   * Test du signalement de commentaires non appropriés
   * Puis de leurs modérations
   */
  public function testCommentAlertAndModerate()
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
     *   Le compteur de mauvaise attitude augmente
     * * un commentaire (paul) est considéré comme ok, le compteur (faux signalement) 
     *   de joelle s'incrémente de 1.
     */
    
    $em = $this->client->getKernel()->getContainer()->get('doctrine')->getEntityManager();
    $this->connectUser('joelle', 'toor');
    $joelle = $this->getUser();
    $joelle_fake_alerts = $joelle->getBadReportCount();
    $bux_moderated_element_count = $this->getUser('bux')->getModeratedCommentCount();
    $paul_moderated_element_count = $this->getUser('paul')->getModeratedCommentCount();
    
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
    $this->isResponseSuccess();
    
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
    
    // Le compteur de mauvais comportement de bux a été incrémenté
    $this->assertEquals($bux_moderated_element_count+1, $this->getUser('bux')->getModeratedCommentCount());
    
    $joelle = $this->getUser('joelle');
    // Le compteur de faux signalement de joelle ne bouge pas.
    $this->assertEquals($joelle_fake_alerts, $joelle->getBadReportCount());
    
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
    
    // Le compteur de mauvais comportement de paul n'a pas bougé
    $this->assertEquals($paul_moderated_element_count, $this->getUser('paul')->getModeratedCommentCount());
    
    // Et si on se rend sur la page home, le commentaire de bux a disparu
    $this->crawler = $this->client->request('GET', $this->generateUrl('home'));
    
    $this->exist('li.comment:contains("C\'est pas mal en effet")');
    $this->notExist('li.comment:contains("C\'est trop bon hein ?")');
  }
  
}