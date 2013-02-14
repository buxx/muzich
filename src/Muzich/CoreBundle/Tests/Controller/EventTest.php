<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;
use Muzich\CoreBundle\Entity\Event;
use Muzich\CoreBundle\Managers\CommentsManager;

class EventTest extends FunctionalTest
{
  
  /**
   * Test de l'inscription d'événement lorsque des commentaires sont ajoutés
   * 
   */
  public function testNewCommentEvent()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $paul = $this->getUser();
    $bux = $this->getUser('bux');
    
    // Actuellement il n'y a aucun event d'ouvert pour bux (fixtures)
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $bux->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 0);
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // paul écrit un commentaire sur un des elements a bux
    $this->crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_add_comment', array(
        'element_id' => $element->getId(),
        'token'      => $paul->getPersonalHash($element->getId())
      )), 
      array(
          'comment' => "Du coup ce com va emettre un event"
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // bux a maintenant un event en base
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('
        SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $bux->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 1);
    $this->assertEquals($result[0]['type'], Event::TYPE_COMMENT_ADDED_ELEMENT);
    $this->assertEquals($result[0]['count'], 1);
    $this->assertEquals($result[0]['ids'], json_encode(array((string)$element->getId())));
    
    // paul écrit un autre commentaire sur un deuxième element
    $element_2 = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('AZYD AZYLUM Live au Café Provisoire')
    ;
    
    $this->crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_add_comment', array(
        'element_id' => $element_2->getId(),
        'token'      => $paul->getPersonalHash($element_2->getId())
      )), 
      array(
          'comment' => "Du coup ce com va aussi emettre un event"
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // bux a toujours 1 seul event en base
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('
        SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $bux->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 1);
    $this->assertEquals($result[0]['type'], Event::TYPE_COMMENT_ADDED_ELEMENT);
    // Mais il dénombre deux elements
    $this->assertEquals($result[0]['count'], 2);
    $this->assertEquals($result[0]['ids'], json_encode(array((string)$element->getId(), (string)$element_2->getId())));
    
    // Par contre si paul écrit un com sur un de ces deux éléments, pas de changement 
    // au niveau de l'event
    $this->crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_add_comment', array(
        'element_id' => $element_2->getId(),
        'token'      => $paul->getPersonalHash($element_2->getId())
      )), 
      array(
          'comment' => "Du coup ce com va aussi emettre un event"
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // bux a toujours 1 seul event en base
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $bux->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 1);
    $this->assertEquals($result[0]['type'], Event::TYPE_COMMENT_ADDED_ELEMENT);
    // Mais il dénombre deux elements
    $this->assertEquals($result[0]['count'], 2);
    $this->assertEquals($result[0]['ids'], json_encode(array((string)$element->getId(), (string)$element_2->getId())));
    
    // Nous allons maintenant consulter ces events avec bux
    $this->disconnectUser();
    $this->connectUser('bux', 'toor');
    $bux = $this->getUser();
    
    // bux doit pouvoir voir dans la barre de droite qu'il a deux elements avec de
    // nouveaux commentaire
    $this->exist('ul.user_events_infos li.user_messages span.new_comments:contains("2")');
    
    // Il y a d'ailleurs un lien pour les afficher
    $url = $this->generateUrl('event_view_elements', array('event_id' => $result[0]['id']));
    $this->exist('ul.user_events_infos li.user_messages a[href="'.$url.'"]');
    
    // On se rend sur ce lien
    $this->crawler = $this->client->request('GET', $url);
    $this->isResponseSuccess();
    
    // nous somme sur la page home
    $this->exist('ul.elements');
    // et on peux voir les deux éléments qui ont reçu le nouveau commentaire
    $this->exist('li#element_'.$element->getId());
    $this->exist('li#element_'.$element_2->getId());
    
    // On voit egallement le bouton pour ne plus voir l'event
    $url = $this->generateUrl('event_delete', array(
      'event_id' => $result[0]['id'],
      'token'    => $bux->getPersonalHash($result[0]['id'])
    ));
    $this->exist('a[href="'.$url.'"]');
    
    // L'objet Event est encore en base
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $bux->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 1);
    
    // On va sur le liens pour ne plus voir l'event
    $this->crawler = $this->client->request('GET', $url);
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // L'objet Event ne doit plus être en base maintenant qu'il a été vu
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $bux->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 0);
    //
    //// Du coup on clique dessus pour revenir a un etat normal
    //$this->crawler = $this->client->request(
    //  'GET', 
    //  $url, 
    //  array(), 
    //  array(), 
    //  array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    //);
    //
    //$this->isResponseSuccess();
    //
    //$response = json_decode($this->client->getResponse()->getContent(), true);
    //$this->assertEquals($response['status'], 'success');
    //
    //// la réponse contient bien un des éléments qui n'a pas été commenté tout a l'heure
    //$this->assertTrue(!is_null(strpos($response['html'], 'Babylon Pression - Des Tasers et des Pauvres')));
    //
    // Et si on réaffiche la page home, le filtre a bien été réinitialisé
    $this->crawler = $this->client->request('GET', $this->generateUrl('home'));
    
    $this->isResponseSuccess();
    $this->exist('li.element:contains("Babylon Pression - Des Tasers et des Pauvres")');
    
  }
  
  public function testFavoriteAdded()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $paul = $this->getUser();
    $bux = $this->getUser('bux');
    
    // Actuellement il n'y a aucun event d'ouvert pour bux (fixtures)
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $bux->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 0);
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
        
    // Ajout d'un élément en favoris
    // Il ajoute cet élément en favoris
    $url = $this->generateUrl('favorite_add', array(
      'id'    => $element->getId(),
      'token' => $paul->getPersonalHash($element->getId())
    ));
    
    $crawler = $this->client->request('GET', $url, array(), array(), array(
        'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    
    $this->isResponseSuccess();
    
    // On contrôle la présence du favoris
    $fav = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->findOneBy(array(
        'user'    => $paul->getId(),
        'element' => $element->getId()
      ));
    
    $this->assertTrue(!is_null($fav));
    
    // bux a maintenant un event en base
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('
        SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $bux->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 1);
    $this->assertEquals($result[0]['type'], Event::TYPE_FAV_ADDED_ELEMENT);
    $this->assertEquals($result[0]['count'], 1);
    $this->assertEquals($result[0]['ids'], json_encode(array((string)$element->getId())));
    
    // On enlève des favoris
    $url = $this->generateUrl('favorite_remove', array(
      'id'    => $element->getId(),
      'token' => $paul->getPersonalHash($element->getId())
    ));
    
    $crawler = $this->client->request('GET', $url, array(), array(), array(
        'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    
    // On contrôle l'absence du favoris
    $fav = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->findOneBy(array(
        'user'    => $paul->getId(),
        'element' => $element->getId()
      ));
    
    $this->assertTrue(is_null($fav));
    
    // bux a toujours qu'un event avec un seul element signalé.
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('
        SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $bux->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 1);
    $this->assertEquals($result[0]['type'], Event::TYPE_FAV_ADDED_ELEMENT);
    $this->assertEquals($result[0]['count'], 1);
    $this->assertEquals($result[0]['ids'], json_encode(array((string)$element->getId())));
    
    // Pour le moment pas de tests supplémentaire comme mettre de nouveaux favoris 
    // ou consulter la liste des éléments concernés. Il faudrait coder ces test certe.
    // Mais la refactorisation du code fait qu'il n'y a que le type (Event) de diféfrent.
    // donc a coder (tests) mais pas urgent a l'isntant.
  }
  
  /**
   * Ce test teste le déclenchement d'événement qui s'effctue lorsque un 
   * nouveau commentaire est écrit sur un élément que l'on a choisis de "suivre"
   * 
   */
  public function testNewCommentEventOnOtherElement()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $paul = $this->getUser();
    
    // Actuellement il n'y a aucun event d'ouvert pour paul (fixtures)
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $paul->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 0);
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // paul écrit un commentaire sur un des elements a bux
    $this->crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_add_comment', array(
        'element_id' => $element->getId(),
        'token'      => $paul->getPersonalHash($element->getId())
      )), 
      array(
          'comment' => "Je choisis en commentant de suivre l'élément",
          'follow'  => true
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // On vérifie que paul fait bien partis des suiveurs
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    $cm = new CommentsManager($element->getComments());
    $this->assertTrue($cm->userFollow($paul->getId()));
        
    // joelle va egallement suivre cet élément
    $this->disconnectUser();
    
    $this->connectUser('joelle', 'toor');
    
    $joelle = $this->getUser();
    
    // Actuellement il n'y a aucun event d'ouvert pour joelle (fixtures)
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $joelle->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 0);
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // joelle écrit un commentaire sur un des elements a bux
    $this->crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_add_comment', array(
        'element_id' => $element->getId(),
        'token'      => $joelle->getPersonalHash($element->getId())
      )), 
      array(
          'comment' => "Je choisis en commentant de suivre l'élément (joelle)",
          'follow'  => true
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // On vérifie que jioelle fait bien partis des suiveurs
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    $cm = new CommentsManager($element->getComments());
    $this->assertTrue($cm->userFollow($joelle->getId()));
    
    // bux va aller commenter son élément
    $this->disconnectUser();
    
    $this->connectUser('bux', 'toor');
    
    $bux = $this->getUser();
    
    // Actuellement il n'y a aucun event d'ouvert pour bux (fixtures)
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $joelle->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 0);
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // bux écrit un commentaire sur un des elements a bux
    $this->crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_add_comment', array(
        'element_id' => $element->getId(),
        'token'      => $bux->getPersonalHash($element->getId())
      )), 
      array(
          'comment' => "Voila le com qui declenche les événemetns chez paul et joelle"
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // Paul et Joelle on maintenant des events
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type'
      )
      ->setParameters(array(
        'uid' => $paul->getId(),
        'type' => Event::TYPE_COMMENT_ADDED_ELEMENT
      ))
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 1);
    $this->assertEquals($result[0]['type'], Event::TYPE_COMMENT_ADDED_ELEMENT);
    // 
    $this->assertEquals($result[0]['count'], 1);
    $this->assertEquals($result[0]['ids'], json_encode(array((string)$element->getId())));
    
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type'
      )
      ->setParameters(array(
        'uid' => $joelle->getId(),
        'type' => Event::TYPE_COMMENT_ADDED_ELEMENT
      ))
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 1);
    $this->assertEquals($result[0]['type'], Event::TYPE_COMMENT_ADDED_ELEMENT);
    // 
    $this->assertEquals($result[0]['count'], 1);
    $this->assertEquals($result[0]['ids'], json_encode(array((string)$element->getId())));
    
    // bux va envoyer un deuxième commentaire
    $this->crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_add_comment', array(
        'element_id' => $element->getId(),
        'token'      => $bux->getPersonalHash($element->getId())
      )), 
      array(
          'comment' => "un nouveau com"
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // Pas de mouvement coté événements
    $result_paul = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type'
      )
      ->setParameters(array(
        'uid' => $paul->getId(),
        'type' => Event::TYPE_COMMENT_ADDED_ELEMENT
      ))
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 1);
    $this->assertEquals($result[0]['type'], Event::TYPE_COMMENT_ADDED_ELEMENT);
    // 
    $this->assertEquals($result[0]['count'], 1);
    $this->assertEquals($result[0]['ids'], json_encode(array((string)$element->getId())));
    
    $result_jo = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type'
      )
      ->setParameters(array(
        'uid' => $joelle->getId(),
        'type' => Event::TYPE_COMMENT_ADDED_ELEMENT
      ))
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 1);
    $this->assertEquals($result[0]['type'], Event::TYPE_COMMENT_ADDED_ELEMENT);
    // 
    $this->assertEquals($result[0]['count'], 1);
    $this->assertEquals($result[0]['ids'], json_encode(array((string)$element->getId())));
    
    // Paul va aller consulter son event
    $this->disconnectUser();
    $this->connectUser('paul', 'toor');
    
    $url = $this->generateUrl('event_view_elements', array('event_id' => $result_paul[0]['id']));
    // il le consulte
    $this->crawler = $this->client->request('GET', $url);
    $this->isResponseSuccess();
    
    // L'objet Event doit encore être en bas etant que l'on a pas validé sa suppression
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $paul->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 1);
        
    // On le supprime de la base
    $this->crawler = $this->client->request('GET', $this->generateUrl('event_delete', array(
      'event_id' => $result_paul[0]['id'],
      'token'    => $paul->getPersonalHash($result_paul[0]['id'])
    )));
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
        
    // L'objet Event ne doit plus être en base maintenant qu'il a été vu
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $paul->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 0);
    
    // paul désactive le fait qu'il veut être avertis
    $this->crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_add_comment', array(
        'element_id' => $element->getId(),
        'token'      => $paul->getPersonalHash($element->getId())
      )), 
      array(
          'comment' => "ze veux plus",
          'follow'  => false
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // On vérifie que paul fait bien partis des suiveurs
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    $cm = new CommentsManager($element->getComments());
    $this->assertFalse($cm->userFollow($paul->getId()));
    
    // au tour de joelle de consulter son event
    $this->disconnectUser();
    $this->connectUser('joelle', 'toor');
    
    $url = $this->generateUrl('event_view_elements', array('event_id' => $result_jo[0]['id']));
    // il le consulte
    $this->crawler = $this->client->request('GET', $url);
    $this->isResponseSuccess();
        
    $this->crawler = $this->client->request('GET', $url);
    
    
    $delete_url = $this->generateUrl('event_delete', array(
      'event_id' => $result_jo[0]['id'],
      'token'    => $joelle->getPersonalHash($result_jo[0]['id'])
    ));
    $this->crawler = $this->client->request('GET', $delete_url);
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
        
    // L'objet Event ne doit plus être en base maintenant qu'il a été vu
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $joelle->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 0);
    
    // joelle garde son follow sur cet élément
    
    // bux va de nouveau metre un commentaire
    $this->disconnectUser();
    
    $this->connectUser('bux', 'toor');
    
    $bux = $this->getUser();
            
    // bux écrit un commentaire sur un des elements a bux
    $this->crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_add_comment', array(
        'element_id' => $element->getId(),
        'token'      => $bux->getPersonalHash($element->getId())
      )), 
      array(
          'comment' => "ce com va declencher un event chez joelle mais pas chez paul"
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // Paul et Joelle on maintenant des events
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type'
      )
      ->setParameters(array(
        'uid' => $paul->getId(),
        'type' => Event::TYPE_COMMENT_ADDED_ELEMENT
      ))
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 0);
    
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type'
      )
      ->setParameters(array(
        'uid' => $joelle->getId(),
        'type' => Event::TYPE_COMMENT_ADDED_ELEMENT
      ))
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 1);
    $this->assertEquals($result[0]['type'], Event::TYPE_COMMENT_ADDED_ELEMENT);
    // 
    $this->assertEquals($result[0]['count'], 1);
    $this->assertEquals($result[0]['ids'], json_encode(array((string)$element->getId())));
  }
  
  public function testFollowEvent()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    $bux = $this->getUser();
    $bob = $this->getUser('bob');
    
    // Actuellement il n'y a aucun event d'ouvert pour paul (fixtures)
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $bob->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 0);
    
    // On tente de récupérer l'entité FollowUser
    $FollowUser = $this->getDoctrine()->getRepository('MuzichCoreBundle:FollowUser')
      ->findOneBy(array(
        'follower' => $bux->getId(),
        'followed' => $bob->getId()
      ))
    ;
    
    // Mais celle-ci doit-être innexistante
    $this->assertTrue(is_null($FollowUser));
    
    // On va suivre bob
    $url_follow = $this->generateUrl('follow', array(
      'type' => 'user', 
      'id' => $bob->getId(),
      'token' => $bux->getPersonalHash($bob->getId())
    ));
    
    $this->crawler = $this->client->request('GET', $url_follow);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // Désormais bob doit avoir un event
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type'
      )
      ->setParameters(array(
        'uid' => $bob->getId(),
        'type' => Event::TYPE_USER_FOLLOW
      ))
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 1);
    $this->assertEquals($result[0]['type'], Event::TYPE_USER_FOLLOW);
    $this->assertEquals($result[0]['count'], 1);
    $this->assertEquals($result[0]['ids'], json_encode(array((string)$bux->getId())));
    
    // On va se connecter avec bob
    $this->disconnectUser();
    $this->connectUser('bob', 'toor');
    
    // bob doit pouvoir voir dans la barre de droite l'event
    $this->exist('ul.user_events_infos li.user_friends span.new_follows:contains("1")');
    
    // Il y a d'ailleurs un lien pour les afficher
    $url = $this->generateUrl('mynetwork_index', array('event_id' => $result[0]['id']));
    $this->exist('ul.user_events_infos li.user_friends a[href="'.$url.'"]');
    
    // On se rend sur ce lien
    $this->crawler = $this->client->request('GET', $url);
    $this->isResponseSuccess();
    
    // On peux voir le lien vers bux en class 'new'
    $this->exist('ul#followers_users li:contains(\'bux\')');
    
    // L'event n'existe d'ailleurs plus en base
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $bob->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 0);
  }
  
  public function testElementTagsPropositions()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    $paul = $this->getUser('paul');
    $bux = $this->getUser('bux');
    
    $element_1 = $this->findOneBy('Element', 'Infected Mushroom - Psycho');
    $element_2 = $this->findOneBy('Element', 'CardioT3K - Juggernaut Trap ');
    $tag_1     = $this->findOneBy('Tag', 'Metal');
    
    // Bux propose un tag de remplacement sur son element 1
    $this->eventCount($paul, Event::TYPE_TAGS_PROPOSED, 0);
    $this->proposeElementTags($element_1, $bux, array($tag_1->getId()));
    $this->eventCount($paul, Event::TYPE_TAGS_PROPOSED, 1);
    $this->eventHasElementId($paul, Event::TYPE_TAGS_PROPOSED, $element_1->getId());
    // Deuxieme proposition
    $this->proposeElementTags($element_2, $bux, array($tag_1->getId()));
    $this->eventCount($paul, Event::TYPE_TAGS_PROPOSED, 1);
    $this->eventHasElementId($paul, Event::TYPE_TAGS_PROPOSED, $element_1->getId());
    $event = $this->eventHasElementId($paul, Event::TYPE_TAGS_PROPOSED, $element_2->getId());
    // On connecte paul
    $this->disconnectUser();
    $this->connectUser('paul', 'toor');
    $this->acceptTagProposition($paul, $this->getElementTagProposition($element_1->getId(), $bux->getId())->getId());
    $this->eventCount($paul, Event::TYPE_TAGS_PROPOSED, 1);
    $this->eventHasNotElementId($paul, Event::TYPE_TAGS_PROPOSED, $element_1->getId());
    $this->eventHasElementId($paul, Event::TYPE_TAGS_PROPOSED, $element_2->getId());
    $this->refuseTagProposition($paul, $element_2->getId());
    $this->eventCount($paul, Event::TYPE_TAGS_PROPOSED, 0);
  }
  
  protected function eventCount($user, $type, $count)
  {
    $events = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type'
      )
      ->setParameters(array(
        'uid' => $user->getId(),
        'type' => $type
      ))
      ->getArrayResult()
    ;
    $this->assertEquals(count($events), $count);
  }
  
  protected function proposeElementTags($element, $user, $tags_ids)
  {
    $crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_element_propose_tags_proceed', 
        array('element_id' => $element->getId(), 'token' => $user->getPersonalHash())
      ), 
      array(
        'element_tag_proposition_'.$element->getId() => array(
          'tags' => json_encode($tags_ids)
        )
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
  }
  
  protected function eventHasElementId($user, $type, $element_id)
  {
    $events = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type AND e.ids LIKE :eid'
      )
      ->setParameters(array(
        'uid' => $user->getId(),
        'type' => $type,
        'eid' => '%"'.$element_id.'"%'
      ))
      ->getArrayResult()
    ;
    $this->assertEquals(count($events), 1);
    return $events[0];
  }
  
  protected function eventHasNotElementId($user, $type, $element_id)
  {
    $events = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type AND e.ids LIKE :eid'
      )
      ->setParameters(array(
        'uid' => $user->getId(),
        'type' => $type,
        'eid' => '%"'.$element_id.'"%'
      ))
      ->getArrayResult()
    ;
    $this->assertEquals(count($events), 0);
  }
  
  protected function acceptTagProposition($user, $proposition_id)
  {
    $crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_element_proposed_tags_accept', 
        array(
          'proposition_id' => $proposition_id,
          'token' => $user->getPersonalHash($proposition_id)
        )
      ), 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
  }        
  
  protected function refuseTagProposition($user, $element_id)
  {
    $crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_element_proposed_tags_refuse', 
        array(
          'element_id' => $element_id,
          'token' => $user->getPersonalHash($element_id)
        )
      ), 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
  }
  
  protected function getElementTagProposition($element_id, $user_id)
  {
    $propositions = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT p, t FROM MuzichCoreBundle:ElementTagsProposition p'
        .' JOIN p.tags t WHERE p.element = :eid AND p.user = :uid')
      ->setParameters(array(
        'eid' => $element_id,
        'uid' => $user_id
      ))
      ->getResult();
    if (count($propositions))
    {
      return $propositions[0];
    }
  }
  
}