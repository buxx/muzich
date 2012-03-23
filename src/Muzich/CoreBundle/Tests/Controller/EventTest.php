<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;
use Muzich\CoreBundle\Entity\Event;

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
        'token'      => $paul->getPersonalHash()
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
        'token'      => $paul->getPersonalHash()
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
        'token'      => $paul->getPersonalHash()
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
    
    // bux doit pouvoir voir dans la barre de droite qu'il a deux elements avec de
    // nouveaux commentaire
    $this->exist('div#events div.comments a span.new_comments:contains("2")');
    
    // Il y a d'ailleurs un lien pour les afficher
    $url = $this->generateUrl('event_view_elements', array('event_id' => $result[0]['id']));
    $this->exist('div#events div.comments a[href="'.$url.'"]');
    
    // On se rend sur ce lien
    $this->crawler = $this->client->request('GET', $url);
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // nous somme sur la page home
    $this->exist('ul.elements');
    // et on peux voir les deux éléments qui ont reçu le nouveau commentaire
    $this->exist('li#element_'.$element->getId());
    $this->exist('li#element_'.$element_2->getId());
    // On voit egallement le bouton dans les filtres
    // /!\ la je ne teste pas si il est affiché ou caché /!\
    $url = $this->generateUrl('ajax_filter_remove_ids');
    $this->exist('div.more_filters a[href="'.$url.'"]');
    
    // L'objet Event ne doit plus être en base maintenant qu'il a été vu
    $result = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid'
      )
      ->setParameter('uid', $bux->getId())
      ->getArrayResult()
    ;
    $this->assertEquals(count($result), 0);
    
    // Du coup on clique dessus pour revenir a un etat normal
    $this->crawler = $this->client->request(
      'GET', 
      $url, 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // la réponse contient bien un des éléments qui n'a pas été commenté tout a l'heure
    $this->assertTrue(!is_null(strpos($response['html'], 'Babylon Pression - Des Tasers et des Pauvres')));
    
    // Et si on réaffiche la page home, le filtre a bien été réinitialisé
    $this->crawler = $this->client->request('GET', $this->generateUrl('home'));
    
    $this->isResponseSuccess();
    $this->exist('li.element:contains("Babylon Pression - Des Tasers et des Pauvres")');
    
  }
  
}