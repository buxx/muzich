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
 
  public function testReportElement()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $paul = $this->getUser();
    $paul_bad_reports_count = $paul->getBadReportCount();
    $bux_moderated_element_count = $this->getUser('bux')->getModeratedElementCount();
    
    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // Paul signale cet élément comme pas bien
    $url = $this->generateUrl('ajax_report_element', array(
      'element_id' => $element_ed->getId(),
      'token'      => $paul->getPersonalHash($element_ed->getId())
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
    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    $this->assertEquals($element_ed->getCountReport(), 1);
    $this->assertEquals($element_ed->getReportIds(), array((string)$paul->getId()));
    
    // Si il effectue le signalement une deuxième fois sur le même element
    // Ca ne doit pas bouger puisqu'il l'a déjà fait
    $url = $this->generateUrl('ajax_report_element', array(
      'element_id' => $element_ed->getId(),
      'token'      => $paul->getPersonalHash($element_ed->getId())
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
    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    $this->assertEquals($element_ed->getCountReport(), 1);
    $this->assertEquals($element_ed->getReportIds(), array((string)$paul->getId()));
    
    // Paul va ausi signaler un autre élément
    // Babylon Pression - Des Tasers et des Pauvres
    
    // On check en base
    $element_bab = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Babylon Pression - Des Tasers et des Pauvres')
    ;
    
    $this->assertEquals($element_bab->getCountReport(), 0);
    $this->assertEquals($element_bab->getReportIds(), null);
    
    // Si il effectue le signalement une deuxième fois sur le même element
    // Ca ne doit pas bouger puisqu'il l'a déjà fait
    $url = $this->generateUrl('ajax_report_element', array(
      'element_id' => $element_bab->getId(),
      'token'      => $paul->getPersonalHash($element_bab->getId())
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
    $element_bab = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Babylon Pression - Des Tasers et des Pauvres')
    ;
    
    $this->assertEquals($element_bab->getCountReport(), 1);
    $this->assertEquals($element_bab->getReportIds(), array((string)$paul->getId()));
    
    // On passe a joelle
    
    $this->disconnectUser();
    // On connecte joelle pour faire le même test sur le même élément
    $this->connectUser('joelle', 'toor');
    
    $joelle = $this->getUser();
    $joelle_bad_reports_count = $joelle->getBadReportCount();
    
    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // Paul signale cet élément comme pas bien
    $url = $this->generateUrl('ajax_report_element', array(
      'element_id' => $element_ed->getId(),
      'token'      => $joelle->getPersonalHash($element_ed->getId())
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
    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    $this->assertEquals($element_ed->getCountReport(), 2);
    $this->assertEquals($element_ed->getReportIds(), array((string)$paul->getId(), (string)$joelle->getId()));
    
    // Si elle effectue le signalement une deuxième fois sur le même element
    // Ca ne doit pas bouger puisqu'elle l'a déjà fait
    $url = $this->generateUrl('ajax_report_element', array(
      'element_id' => $element_ed->getId(),
      'token'      => $joelle->getPersonalHash($element_ed->getId())
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
    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    $this->assertEquals($element_ed->getCountReport(), 2);
    $this->assertEquals($element_ed->getReportIds(), array((string)$paul->getId(), (string)$joelle->getId()));
    
    /*
     * Maintenant on va aller modérer ça coté modération.
     */
    
    // On donne le status d'admin a bob
    $output = $this->runCommand(
      $this->client, 
      "fos:user:promote bob ROLE_ADMIN"
    );
    
    // On connecte bob
    $this->disconnectUser();
    $this->connectUser('bob', 'toor');
    
    /*
     *
     *
     *
     */
    
    // Sur la page de modération d'élément on peux voir l'élément dans la liste
    $this->crawler = $this->client->request('GET', $this->generateUrl('Muzich_AdminBundle_Moderate_element_list'));
    $this->isResponseSuccess();
    
    $this->exist('body:contains("'.$element_ed->getName().'")');
    $this->exist('body:contains("'.$element_bab->getName().'")');
    
    $this->crawler = $this->client->request('GET', $this->generateUrl(
      'Muzich_AdminBundle_Moderate_element_refuse',
      array('pk' => $element_ed->getId())
    ));
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    $this->assertEquals(true, is_null($element_ed));
    // Condéquences, le proprio (bux) vois son compteur d'élément modéré augmenter
    $this->assertEquals($bux_moderated_element_count+1, $this->getUser('bux')->getModeratedElementCount());
    // Le compteur de mauvai signalemetn de paul n'a pas bougé par contre.
    $this->assertEquals($paul_bad_reports_count, $this->getUser('paul')->getBadReportCount());
    // Ni celui de joelle
    $this->assertEquals($joelle_bad_reports_count, $this->getUser('joelle')->getBadReportCount());
    
    // Deuxième action on considère que l'autre élément n'a pas a être refusé
    $this->crawler = $this->client->request('GET', $this->generateUrl(
      'Muzich_AdminBundle_Moderate_element_accept',
      array('pk' => $element_bab->getId())
    ));
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $element_bab = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Babylon Pression - Des Tasers et des Pauvres')
    ;
    
    $this->assertEquals(false, is_null($element_bab));
    $this->assertEquals($element_bab->getCountReport(), 0);
    $this->assertEquals($element_bab->getReportIds(), null);
    
    // Condéquences, le proprio (bux) ne vois pas son compteur d'élément modéré augmenter encore
    //                                              +1 car c'est pour la modo juste avant
    $this->assertEquals($bux_moderated_element_count+1, $this->getUser('bux')->getModeratedElementCount());
    // Le compteur de mauvais signalemetn de paul a augmenter d'un
    $this->assertEquals($paul_bad_reports_count+1, $this->getUser('paul')->getBadReportCount());
    // Celui de joelle non, elle n'a rien a voir avec ce signalement
    $this->assertEquals($joelle_bad_reports_count, $this->getUser('joelle')->getBadReportCount());
  }
  
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
        'token'      => $joelle->getPersonalHash($element->getId())
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
        'token'      => $joelle->getPersonalHash($element->getId())
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
    $this->crawler = $this->client->request('GET', $this->generateUrl('Muzich_AdminBundle_Moderate_comment_list'));
    $this->isResponseSuccess();
    
    // On voit les deux commentaires signalés dans la liste
    $this->exist('body:contains("C\'est trop bon hein ?")');
    $this->exist('body:contains("C\'est pas mal en effet")');
    
    // Refus de celui de bux
    $this->crawler = $this->client->request('GET', $this->generateUrl(
      'Muzich_AdminBundle_Moderate_comment_refuse',
      array('element_id' => $element->getId(), 'date' => $comment_bux['d'])
    ));
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
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
    
    $this->crawler = $this->client->request('GET', $this->generateUrl(
      'Muzich_AdminBundle_Moderate_comment_accept',
      array('element_id' => $element->getId(), 'date' => $comment_paul['d'])
    ));
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
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
  
  /**
   * Test de la moderation de tag
   */
  public function testTagModerated()
  {
    // bux sera admin
    $this->client = self::createClient();
    $output = $this->runCommand(
      $this->client, 
      "fos:user:promote bux ROLE_ADMIN"
    );
    
    $bux = $this->getUser('bux');
    $paul = $this->getUser('paul');
    $joelle = $this->getUser('joelle');
    
    $paul_moderated_tags_count   = $paul->getModeratedTagCount();
    $joelle_moderated_tags_count = $joelle->getModeratedTagCount();
    
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
    
    // On ajoute quelques tags avec joelle et paul
    
    $this->connectUser('paul', 'toor');
    $crawler = $this->client->request('POST', $this->generateUrl('ajax_add_tag'), 
      array('tag_name' => 'Tag0001'), array(), array(
      'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    $this->isResponseSuccess();
    $Tag0001 = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tag0001');
    
    // Avec ce tag il ajoute un élément
    
      /*
       *  Ajout d'un élément avec succés
       */
      $this->procedure_add_element(
        'Lelement de tag0001', 
        'http://unsite.fr/blaaaaa1', 
        array($Tag0001->getId()),
              null, true
      );
  
      $this->isResponseRedirection();
      $this->followRedirection();
      $this->isResponseSuccess();
      
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Lelement de tag0001')
    ;
    $this->assertEquals(json_encode(array((int)$Tag0001->getId())), $element->getTagsIdsJson());
    
    $crawler = $this->client->request('POST', $this->generateUrl('ajax_add_tag'), 
      array('tag_name' => 'Tag0000'), array(), array(
      'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    $this->isResponseSuccess();
    $Tag0000 = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tag0000');
    // On teste déjà dans d'autre test la présence des champs permettant de montrer qu'il est
    // a modérer.
    
      /*
       *  Ajout d'un élément avec succés
       */
      $this->procedure_add_element(
        'Lelement de tag0000', 
        'http://unsite.fr/blaaaaa0', 
        array($Tag0000->getId()),
              null, true
      );
  
      $this->isResponseRedirection();
      $this->followRedirection();
      $this->isResponseSuccess();
    
    $this->disconnectUser();
    $this->connectUser('joelle', 'toor');
    $crawler = $this->client->request('POST', $this->generateUrl('ajax_add_tag'), 
      array('tag_name' => 'Tag0002'), array(), array(
      'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    $this->isResponseSuccess();
    $Tag0002 = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tag0002');
    
      /*
       *  Ajout d'un élément avec succés
       */
      $this->procedure_add_element(
        'Lelement de tag0002', 
        'http://unsite.fr/blaaaaa2', 
        array($Tag0002->getId()),
              null, true
      );
  
      $this->isResponseRedirection();
      $this->followRedirection();
      $this->isResponseSuccess();
    
    $crawler = $this->client->request('POST', $this->generateUrl('ajax_add_tag'), 
      array('tag_name' => 'Tag0000'), array(), array(
      'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    $this->isResponseSuccess();
    $Tag0000 = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tag0000');
    
    $this->disconnectUser();
    $this->connectUser('bux', 'toor');
    
    // On se rend sur la page de moderation des tags
    $this->crawler = $this->client->request('GET', $this->generateUrl('Muzich_AdminBundle_Moderate_tag_list'));
    
    // On peux voir les tags 
    $this->exist('body:contains("Tag0000")');
    $this->exist('body:contains("Tag0001")');
    $this->exist('body:contains("Tag0002")');
    
    /*
     * Etapes:
     * 
     * On refuse Tag0000
     *  => L'élément lié a ce tag ne l'a plus
     *  => Le ou les users qui l'on demandé sont incrementé
     * On accepte Tag0001
     *  => L'élément lié a ce tag l'a toujours
     * On remplace Tag0002 par tribe
     *  => L'élément lié a ce tag a le nouveau
     *  => Les users qui l'ont demandé ne sont pas pénalisés 
     */
    
    // On refuse Tag0000
    $this->crawler = $this->client->request('GET', $this->generateUrl(
      'Muzich_AdminBundle_Moderate_tag_refuse',
      array('pk' => $Tag0000->getId())
    ));
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // Le tag n'est plus
    $Tag0000 = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tag0000');
    $this->assertEquals(true, is_null($Tag0000));
    
    // L'élément ne dois plus l'avoir comme tag
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Lelement de tag0000')
    ;
    
    $this->assertEquals('[]', $element->getTagsIdsJson());
    
    // les utilisateurs ayant demandé ce tag ont été pénalisés
    $this->assertEquals($paul_moderated_tags_count+1, $this->getUser('paul')->getModeratedTagCount());
    $this->assertEquals($joelle_moderated_tags_count+1, $this->getUser('joelle')->getModeratedTagCount());
    
    // On accepte Tag0001
    $this->crawler = $this->client->request('GET', $this->generateUrl(
      'Muzich_AdminBundle_Moderate_tag_accept',
      array('pk' => $Tag0001->getId())
    ));
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // Le tag est toujours
    $Tag0001 = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tag0001');
    $this->assertEquals(false, is_null($Tag0001));
    
    // Mais n'est plus "a modérer"
    $this->assertEquals(null, $Tag0001->getPrivateids());
    
    // L'élément ne dois plus l'avoir comme tag
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Lelement de tag0001')
    ;
    
    $this->assertEquals(json_encode(array((int)$Tag0001->getId())), $element->getTagsIdsJson());
    
    // les utilisateurs ayant demandé ne sont pas pénalisés
    $this->assertEquals($paul_moderated_tags_count+1, $this->getUser('paul')->getModeratedTagCount());
    $this->assertEquals($joelle_moderated_tags_count+1, $this->getUser('joelle')->getModeratedTagCount());
    
  }
  
}