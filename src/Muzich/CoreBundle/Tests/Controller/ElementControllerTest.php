<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;

class ElementControllerTest extends FunctionalTest
{ 
  
  public function testAddElementAjax()
  {
    $this->client = self::createClient();
    $this->connectUser('joelle', 'toor');
    
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
      
    // L'élément n'existe pas encore
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Musique qui dechire')
    ;
    $this->assertTrue(is_null($element));
    
    // On commence par ajouter un tag
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
              'name'   => 'Musique qui dechire',
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
    
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Musique qui dechire')
    ;
    $this->assertTrue(!is_null($element));
    
  }
  
  public function testAddElementInGroupAjax()
  {
    $this->client = self::createClient();
    $this->connectUser('joelle', 'toor');
    
    $Fans_de_psytrance = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('Fans de psytrance');
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
      
    // L'élément n'existe pas encore
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Musique qui dechire dans psytrance')
    ;
    $this->assertTrue(is_null($element));
    
    // On commence par ajouter un tag
    $url = $this->generateUrl('element_add', array('group_slug' => $Fans_de_psytrance->getSlug()));
   
    $extract = $this->crawler->filter('input[name="element_add[_token]"]')
      ->extract(array('value'));
    $csrf = $extract[0];
    
    $crawler = $this->client->request(
      'POST', 
      $url, 
      array(
          'element_add' => array(
              '_token' => $csrf,
              'name'   => 'Musique qui dechire dans psytrance',
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
    
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneBy(array(
        'name' => 'Musique qui dechire dans psytrance', 
        'group' => $Fans_de_psytrance->getId()
      ))
    ;
    $this->assertTrue(!is_null($element));
    
  }
  
  public function testAddElementAjaxFail()
  {
    $this->client = self::createClient();
    $this->connectUser('joelle', 'toor');
    
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
      
    // L'élément n'existe pas encore
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Musique qui dechire')
    ;
    $this->assertTrue(is_null($element));
    
    // On commence par ajouter un tag
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
              'name'   => 'Musique qui dechire',
              'url'    => 'http://www',
              'tags'   => json_encode(array($hardtek->getId(), $tribe->getId()))
          )
        
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'error');
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Musique qui dechire')
    ;
    $this->assertTrue(is_null($element));
    
  }
  
  public function testAddElementInGroupAjaxFail()
  {
    $this->client = self::createClient();
    $this->connectUser('joelle', 'toor');
    
    $Fans_de_psytrance = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('Fans de psytrance');
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
      
    // L'élément n'existe pas encore
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('zo')
    ;
    $this->assertTrue(is_null($element));
    
    // On commence par ajouter un tag
    $url = $this->generateUrl('element_add', array('group_slug' => $Fans_de_psytrance->getSlug()));
   
    $extract = $this->crawler->filter('input[name="element_add[_token]"]')
      ->extract(array('value'));
    $csrf = $extract[0];
    
    $crawler = $this->client->request(
      'POST', 
      $url, 
      array(
          'element_add' => array(
              '_token' => $csrf,
              'name'   => 'zo',
              'url'    => 'http://www.youtube.com/watch?v=WC8qb_of04E',
              'tags'   => json_encode(array($hardtek->getId(), $tribe->getId()))
          )
        
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'error');
    
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneBy(array(
        'name' => 'zo', 
        'group' => $Fans_de_psytrance->getId()
      ))
    ;
    $this->assertTrue(is_null($element));
    
  }
  
  public function testUpdateElement()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    $bux = $this->getUser();
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // On est sur la page home, on peut voir le lien de modification de l'élément
    $this->exist('a[href="'.($url = $this->generateUrl('element_edit', array('element_id' => $element->getId()))).'"]');
  
    // On effectue la demande ajax d'edition
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
    $this->assertEquals($response['form_name'], 'element_'.$element->getId());
    $this->assertTrue(strpos($response['html'], 'class="edit_element"') !== false);
    
    // Il faut que l'on récupère le token
    preg_match("#name=\"element_add\[_token\]\" value=\"([a-zA-Z0-9]+)\" />#", $response['html'], $chaines);
    $csrf = $chaines[1];
    
    // On effectue la modification en ajax
    $url = $this->generateUrl('element_update', array(
      'element_id' => $element->getId(),
      'dom_id'     => 'element_'.$element->getId()
    ));
    
    $crawler = $this->client->request(
      'POST', 
      $url, 
      array(
          'element_add' => array(
              '_token' => $csrf,
              'name'   => $element->getName().'555',
              'url'    => $element->getUrl(),
              'tags'   => $element->getTagsIdsJson()
          )
        
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    $this->assertTrue(strpos($response['html'], $element->getName().'555') !== false);
    
    $this->crawler = $this->client->request('GET', $this->generateUrl('home'));
    $this->exist('span.element_name:contains("'.$element->getName().'555'.'")');
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName($element->getName().'555')
    ;
    $this->assertTrue(!is_null($element));
  }
  
  public function testDeleteElement()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    $bux = $this->getUser();
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // On est sur la page home, on peut voir le lien de suppression l'élément
    $this->exist('a[href="'.($url = $this->generateUrl('element_remove', array(
        'element_id' => $element->getId(), 'token' => $bux->getPersonalHash($element->getId())
    ))).'"]');
  
    // Suppression de l'élément
 
    // On effectue la demande ajax d'edition
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
    
    $this->crawler = $this->client->request('GET', $this->generateUrl('home'));
    $this->notExist('span.element_name:contains("'.$element->getName().'")');
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    $this->assertTrue(is_null($element));
  }
  
  /**
   * Procédure de vote
   */
  public function testVote()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $paul = $this->getUser();
    $joelle = $this->getUser('joelle');
    $jean = $this->getUser('jean');
    
    // D'après les fixtures, un des élément porte le vote de paul
    $element_soul = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('SOULFLY - Prophecy')
    ;
    
    // On peut donc voir le lien pour "dé-voter"
    $url_unvote_soul = $this->generateUrl('ajax_element_remove_vote_good', array(
      'element_id' => $element_soul->getId(),
      'token' => $paul->getPersonalHash($element_soul->getId())
    ));
    $this->exist('a.vote[href="'.$url_unvote_soul.'"]');
    
    // On contrôle le contenu pour cet element
    $this->assertEquals($element_soul->getPoints(), 1);
    
    // Et son id est la
    $this->assertEquals($element_soul->getVoteGoodIds(), array(
      (string)$paul->getId()
    ));
    
    // On va voter pour un element a bux
    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // Actuellement (fixtures) son score est de 2
    $this->assertEquals($element_ed->getPoints(), 2);
    
    // Et ce sont (fixtures) ces deux user qui ont voté
    $this->assertEquals($element_ed->getVoteGoodIds(), array((string)$joelle->getId(), (string)$jean->getId()));
    
    // On peut d'ailleur constater que la reputation de bux est de 7 (fixtures)
    $bux = $this->getUser('bux');
    $this->assertEquals($bux->getReputation(), 22);
    
    // paul va voter 
    $crawler = $this->client->request(
      'GET', 
      $this->generateUrl('ajax_element_add_vote_good', array(
        'element_id' => $element_ed->getId(),
        'token' => $paul->getPersonalHash($element_ed->getId())
      )), 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // On recontrôle l'élément voir si tout a été enregistré
    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // Son score est mainteannt de 3
    $this->assertEquals($element_ed->getPoints(), 3);
    
    // Et son id est la
    $this->assertEquals($element_ed->getVoteGoodIds(), array(
      (string)$joelle->getId(), 
      (string)$jean->getId(),
      (string)$paul->getId()
    ));
    
    // On peut d'ailleur constater que la reputation de bux est maintenant de 8
    $bux = $this->getUser('bux');
    $this->assertEquals($bux->getReputation(), 23);
    
    // Pau retire son vote de soulfy
    $crawler = $this->client->request(
      'GET', 
      $url_unvote_soul, 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $element_soul = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('SOULFLY - Prophecy')
    ;
    // On contrôle le contenu pour cet element
    $this->assertEquals($element_soul->getPoints(), 0);
    
    // Et son id est la
    $this->assertEquals($element_soul->getVoteGoodIds(), array());
    
    // On peut d'ailleur constater que la reputation de bux est maintenant de 7
    $bux = $this->getUser('bux');
    $this->assertEquals($bux->getReputation(), 22);
    
    // On déconnecte paul, pour faire voter bob sur le partage ed cox
    $this->disconnectUser();
    $this->connectUser('bob', 'toor');
    
    $bob = $this->getUser();
    // bob va donc votre pour le partage d'ed cox
    $crawler = $this->client->request(
      'GET', 
      $this->generateUrl('ajax_element_add_vote_good', array(
        'element_id' => $element_ed->getId(),
        'token' => $bob->getPersonalHash($element_ed->getId())
      )), 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // On recontrôle l'élément voir si tout a été enregistré
    $element_ed = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // Son score est mainteannt de 3
    $this->assertEquals($element_ed->getPoints(), 4);
    
    // Et son id est la
    $this->assertEquals($element_ed->getVoteGoodIds(), array(
      (string)$joelle->getId(), 
      (string)$jean->getId(),
      (string)$paul->getId(),
      (string)$bob->getId()
    ));
    
    // On peut d'ailleur constater que la reputation de bux est maintenant de 8
    $bux = $this->getUser('bux');
    $this->assertEquals($bux->getReputation(), 23);
  }
  
  /**
   * Test des procédure concernants al proposition de tags sur un élément
   * 
   * On test ici: 
   * * Proposition de tags
   * * La consultation de ces propositions
   * * L'acceptation
   */
  public function testTagsPropositionAccept()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe   = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
    $tsouzoumi = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tsouzoumi');
    $soug = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Soug');
    $metal = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Metal');
    
    $paul = $this->getUser();
    $bux = $this->getUser('bux');
    $joelle = $this->getUser('joelle');
    
    $points_pour_tags_add = $this->getContainer()->getParameter('reputation_element_tags_element_prop_value');
    $points_joelle = $joelle->getReputation();
    $points_bux    = $bux->getReputation();
    $points_paul  = $paul->getReputation();
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('AZYD AZYLUM Live au Café Provisoire')
    ;
    
    // Pas de proposition en base pur cet élément
    $propositions = $this->getDoctrine()->getEntityManager()->getRepository('MuzichCoreBundle:ElementTagsProposition')
      ->findOneByElement($element->getId())
    ;
    
    $this->assertEquals(0, count($propositions));
    
    // Pas d'événement pour bux
    $events = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type'
      )
      ->setParameters(array(
        'uid' => $bux->getId(),
        'type' => \Muzich\CoreBundle\Entity\Event::TYPE_TAGS_PROPOSED
      ))
      ->getArrayResult()
    ;
    $this->assertEquals(count($events), 0);
    
    // On teste la récupération du formulaire au moin une fois
    $crawler = $this->client->request(
      'GET',
      $this->generateUrl('ajax_element_propose_tags_open', 
        array('element_id' => $element->getId())
      ), 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    $this->assertEquals($response['form_name'], 'element_tag_proposition_'.$element->getId());
    $this->assertTrue(strpos($response['html'], 'class="tag_proposition"') !== false);
    
    // paul propose une serie de tags
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
    
    // On a maintenant la proposition en base
    $propositions = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT p, t FROM MuzichCoreBundle:ElementTagsProposition p'
        .' JOIN p.tags t WHERE p.element = :eid AND p.user = :uid')
      ->setParameters(array(
        'eid' => $element->getId(),
        'uid'    => $paul->getId()
      ))
      ->getResult()
    ;
    
    $this->assertEquals(1, count($propositions));
    $proposition_paul = $propositions[0];
    
    // Les tags sont aussi en base
    foreach ($propositions[0]->getTags() as $tag)
    {
      if (in_array($tag->getId(), array($hardtek->getId(), $tribe->getId())))
      {
        $this->assertTrue(true);
      }
      else
      {
        $this->assertTrue(false);
      }
    }
    
    // Il y a maintenant un event pour bux
    $events = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type'
      )
      ->setParameters(array(
        'uid' => $bux->getId(),
        'type' => \Muzich\CoreBundle\Entity\Event::TYPE_TAGS_PROPOSED
      ))
      ->getArrayResult()
    ;
    $this->assertEquals(count($events), 1);
    $this->assertEquals($events[0]['type'], \Muzich\CoreBundle\Entity\Event::TYPE_TAGS_PROPOSED);
    // 
    $this->assertEquals($events[0]['count'], 1);
    $this->assertEquals($events[0]['ids'], json_encode(array((string)$element->getId())));
    
    // si il propose un liste vide de tags, c'est refusé bien entendu
    $crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_element_propose_tags_proceed', 
        array('element_id' => $element->getId(), 'token' => $paul->getPersonalHash())
      ), 
      array(
        'element_tag_proposition_'.$element->getId() => array(
          'tags' => json_encode(array())
        )
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'error');
    
    /*
     *  joelle va aussi proposer des tags sur cet élément
     */
    $this->disconnectUser();
    $this->connectUser('joelle', 'toor');
    
    $joelle = $this->getUser();
    
    // joelle propose une serie de tags
    $crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_element_propose_tags_proceed', 
        array('element_id' => $element->getId(), 'token' => $joelle->getPersonalHash())
      ), 
      array(
        'element_tag_proposition_'.$element->getId() => array(
          'tags' => json_encode(array($tsouzoumi->getId(), $soug->getId(), $metal->getId()))
        )
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // On a maintenant la proposition en base
    $propositions = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT p, t FROM MuzichCoreBundle:ElementTagsProposition p'
        .' JOIN p.tags t WHERE p.element = :eid AND p.user = :uid')
      ->setParameters(array(
        'eid' => $element->getId(),
        'uid'    => $joelle->getId()
      ))
      ->getResult()
    ;
    
    $this->assertEquals(1, count($propositions));
    $proposition_joelle = $propositions[0];
    
    // Les tags sont aussi en base
    foreach ($propositions[0]->getTags() as $tag)
    {
      if (in_array($tag->getId(), array($tsouzoumi->getId(), $soug->getId(), $metal->getId())))
      {
        $this->assertTrue(true);
      }
      else
      {
        $this->assertTrue(false);
      }
    }
    
    // avec la propsoition de joelle le nombre d'event n'a pas bougé (le compteur compte les éléments)
    $events = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type'
      )
      ->setParameters(array(
        'uid' => $bux->getId(),
        'type' => \Muzich\CoreBundle\Entity\Event::TYPE_TAGS_PROPOSED
      ))
      ->getArrayResult()
    ;
    $this->assertEquals(count($events), 1);
    $this->assertEquals($events[0]['type'], \Muzich\CoreBundle\Entity\Event::TYPE_TAGS_PROPOSED);
    // 
    $this->assertEquals($events[0]['count'], 1);
    $this->assertEquals($events[0]['ids'], json_encode(array((string)$element->getId())));
    
    /*
     *  C'est au tour de bux d'aller voir ces proposition
     */
    
    $this->disconnectUser();
    $this->connectUser('bux', 'toor');
    
    $bux = $this->getUser();
    
    // Il peut voir le lien vers l'ouverture des propositions
    $url = $this->generateUrl('ajax_element_proposed_tags_view', array('element_id' => $element->getId()));
    $this->exist('a[href="'.$url.'"]');
    
    // On récupére ces propositions
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
    
    $url_accept_paul = $this->generateUrl('ajax_element_proposed_tags_accept', array(
      'proposition_id' => $proposition_paul->getId(),
      'token'          => $bux->getPersonalHash($proposition_paul->getId())
    ));
    $url_accept_joelle = $this->generateUrl('ajax_element_proposed_tags_accept', array(
      'proposition_id' => $proposition_joelle->getId(),
      'token'          => $bux->getPersonalHash($proposition_joelle->getId())
    ));
    $this->assertTrue(strpos($response['html'], 'href="'.$url_accept_paul.'"') !== false);
    $this->assertTrue(strpos($response['html'], 'href="'.$url_accept_joelle.'"') !== false);
    $url_refuse = $this->generateUrl('ajax_element_proposed_tags_refuse', array(
      'element_id' => $element->getId(),
      'token'      => $bux->getPersonalHash($element->getId())
    ));
    
    // On accepete la poposition de joelle
    $crawler = $this->client->request(
      'GET',
      $url_accept_joelle, 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('AZYD AZYLUM Live au Café Provisoire')
    ;
    // Les tags de l'élément ont bien été mis a jour
    foreach (json_decode($element->getTagsIdsJson(), true) as $id)
    {
      if (in_array($id, array($metal->getId(), $soug->getId(), $tsouzoumi->getId())))
      {
        $this->assertTrue(true);
      }
      else
      {
        $this->assertTrue(false);
      }
    }
    $ids = json_decode($element->getTagsIdsJson(), true);
    foreach (array($metal->getId(), $soug->getId(), $tsouzoumi->getId()) as $id)
    {
      if (in_array($id, $ids))
      {
        $this->assertTrue(true);
      }
      else
      {
        $this->assertTrue(false);
      }
    }
        
    // La proposition de joelle a disparu
    $propositions = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT p, t FROM MuzichCoreBundle:ElementTagsProposition p'
        .' JOIN p.tags t WHERE p.element = :eid AND p.user = :uid')
      ->setParameters(array(
        'eid' => $element->getId(),
        'uid'    => $joelle->getId()
      ))
      ->getResult()
    ;
    $this->assertEquals(0, count($propositions));
    
    // celle de paul aussi 
    $propositions = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT p, t FROM MuzichCoreBundle:ElementTagsProposition p'
        .' JOIN p.tags t WHERE p.element = :eid AND p.user = :uid')
      ->setParameters(array(
        'eid' => $element->getId(),
        'uid'    => $paul->getId()
      ))
      ->getResult()
    ;
    $this->assertEquals(0, count($propositions));
    
    // Mais on a un event en archive pour joelle
    $archives = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT a FROM MuzichCoreBundle:EventArchive a'
        .' WHERE a.user = :uid AND a.type = :type')
      ->setParameters(array(
        'uid'    => $joelle->getId(),
        'type'    => \Muzich\CoreBundle\Entity\EventArchive::PROP_TAGS_ELEMENT_ACCEPTED
      ))
      ->getResult()
    ;
    $this->assertEquals(1, count($archives));
    $this->assertEquals(1, $archives[0]->getCount());
    
    // paul lui n'a pas d'archives
    $archives = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT a FROM MuzichCoreBundle:EventArchive a'
        .' WHERE a.user = :uid AND a.type = :type')
      ->setParameters(array(
        'uid'    => $paul->getId(),
        'type'    => \Muzich\CoreBundle\Entity\EventArchive::PROP_TAGS_ELEMENT_ACCEPTED
      ))
      ->getResult()
    ;
    $this->assertEquals(0, count($archives));
       
    // contrôle de l'évolution des points
    $bux = $this->getUser('bux');
    $joelle = $this->getUser('joelle');
    $paul = $this->getUser('paul');
    
    $this->assertEquals($points_bux, $bux->getReputation());
    $this->assertEquals($points_joelle + $points_pour_tags_add, $joelle->getReputation());
    $this->assertEquals($points_paul, $paul->getReputation());
    
  }
  
  /**
   * Test des procédure concernants al proposition de tags sur un élément
   * 
   * On test ici: 
   * * Proposition de tags
   * * La consultation de ces propositions
   * * Le refus
   */
  public function testTagsPropositionRefuse()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe   = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
    $tsouzoumi = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tsouzoumi');
    $soug = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Soug');
    $metal = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Metal');
    
    $paul = $this->getUser();
    $bux = $this->getUser('bux');
    $joelle = $this->getUser('joelle');
    
    $points_pour_tags_add = $this->getContainer()->getParameter('reputation_element_tags_element_prop_value');
    $points_joelle = $joelle->getReputation();
    $points_bux    = $bux->getReputation();
    $points_paul  = $paul->getReputation();
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('AZYD AZYLUM Live au Café Provisoire')
    ;
    
    // Pas de proposition en base pur cet élément
    $propositions = $this->getDoctrine()->getEntityManager()->getRepository('MuzichCoreBundle:ElementTagsProposition')
      ->findOneByElement($element->getId())
    ;
    
    $this->assertEquals(0, count($propositions));
    
    // Pas d'événement pour bux
    $events = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type'
      )
      ->setParameters(array(
        'uid' => $bux->getId(),
        'type' => \Muzich\CoreBundle\Entity\Event::TYPE_TAGS_PROPOSED
      ))
      ->getArrayResult()
    ;
    $this->assertEquals(count($events), 0);
    
    // On teste la récupération du formulaire au moin une fois
    $crawler = $this->client->request(
      'GET',
      $this->generateUrl('ajax_element_propose_tags_open', 
        array('element_id' => $element->getId())
      ), 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    $this->assertEquals($response['form_name'], 'element_tag_proposition_'.$element->getId());
    $this->assertTrue(strpos($response['html'], 'class="tag_proposition"') !== false);
    
    // paul propose une serie de tags
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
    
    // On a maintenant la proposition en base
    $propositions = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT p, t FROM MuzichCoreBundle:ElementTagsProposition p'
        .' JOIN p.tags t WHERE p.element = :eid AND p.user = :uid')
      ->setParameters(array(
        'eid' => $element->getId(),
        'uid'    => $paul->getId()
      ))
      ->getResult()
    ;
    
    $this->assertEquals(1, count($propositions));
    $proposition_paul = $propositions[0];
    
    // Les tags sont aussi en base
    foreach ($propositions[0]->getTags() as $tag)
    {
      if (in_array($tag->getId(), array($hardtek->getId(), $tribe->getId())))
      {
        $this->assertTrue(true);
      }
      else
      {
        $this->assertTrue(false);
      }
    }
    
    // Il y a maintenant un event pour bux
    $events = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type'
      )
      ->setParameters(array(
        'uid' => $bux->getId(),
        'type' => \Muzich\CoreBundle\Entity\Event::TYPE_TAGS_PROPOSED
      ))
      ->getArrayResult()
    ;
    $this->assertEquals(count($events), 1);
    $this->assertEquals($events[0]['type'], \Muzich\CoreBundle\Entity\Event::TYPE_TAGS_PROPOSED);
    // 
    $this->assertEquals($events[0]['count'], 1);
    $this->assertEquals($events[0]['ids'], json_encode(array((string)$element->getId())));
    
    // si il propose un liste vide de tags, c'est refusé bien entendu
    $crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_element_propose_tags_proceed', 
        array('element_id' => $element->getId(), 'token' => $paul->getPersonalHash())
      ), 
      array(
        'element_tag_proposition_'.$element->getId() => array(
          'tags' => json_encode(array())
        )
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'error');
    
    /*
     *  joelle va aussi proposer des tags sur cet élément
     */
    $this->disconnectUser();
    $this->connectUser('joelle', 'toor');
    
    $joelle = $this->getUser();
    
    // joelle propose une serie de tags
    $crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_element_propose_tags_proceed', 
        array('element_id' => $element->getId(), 'token' => $joelle->getPersonalHash())
      ), 
      array(
        'element_tag_proposition_'.$element->getId() => array(
          'tags' => json_encode(array($tsouzoumi->getId(), $soug->getId(), $metal->getId()))
        )
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // On a maintenant la proposition en base
    $propositions = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT p, t FROM MuzichCoreBundle:ElementTagsProposition p'
        .' JOIN p.tags t WHERE p.element = :eid AND p.user = :uid')
      ->setParameters(array(
        'eid' => $element->getId(),
        'uid'    => $joelle->getId()
      ))
      ->getResult()
    ;
    
    $this->assertEquals(1, count($propositions));
    $proposition_joelle = $propositions[0];
    
    // Les tags sont aussi en base
    foreach ($propositions[0]->getTags() as $tag)
    {
      if (in_array($tag->getId(), array($tsouzoumi->getId(), $soug->getId(), $metal->getId())))
      {
        $this->assertTrue(true);
      }
      else
      {
        $this->assertTrue(false);
      }
    }
    
    // avec la propsoition de joelle le nombre d'event n'a pas bougé (le compteur compte les éléments)
    $events = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type'
      )
      ->setParameters(array(
        'uid' => $bux->getId(),
        'type' => \Muzich\CoreBundle\Entity\Event::TYPE_TAGS_PROPOSED
      ))
      ->getArrayResult()
    ;
    $this->assertEquals(count($events), 1);
    $this->assertEquals($events[0]['type'], \Muzich\CoreBundle\Entity\Event::TYPE_TAGS_PROPOSED);
    // 
    $this->assertEquals($events[0]['count'], 1);
    $this->assertEquals($events[0]['ids'], json_encode(array((string)$element->getId())));
    
    /*
     *  C'est au tour de bux d'aller voir ces proposition
     */
    
    $this->disconnectUser();
    $this->connectUser('bux', 'toor');
    
    $bux = $this->getUser();
    
    // Il peut voir le lien vers l'ouverture des propositions
    $url = $this->generateUrl('ajax_element_proposed_tags_view', array('element_id' => $element->getId()));
    $this->exist('a[href="'.$url.'"]');
    
    // On récupére ces propositions
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
    
    $url_accept_paul = $this->generateUrl('ajax_element_proposed_tags_accept', array(
      'proposition_id' => $proposition_paul->getId(),
      'token'          => $bux->getPersonalHash($proposition_paul->getId())
    ));
    $url_accept_joelle = $this->generateUrl('ajax_element_proposed_tags_accept', array(
      'proposition_id' => $proposition_joelle->getId(),
      'token'          => $bux->getPersonalHash($proposition_joelle->getId())
    ));
    $this->assertTrue(strpos($response['html'], 'href="'.$url_accept_paul.'"') !== false);
    $this->assertTrue(strpos($response['html'], 'href="'.$url_accept_joelle.'"') !== false);
    $url_refuse = $this->generateUrl('ajax_element_proposed_tags_refuse', array(
      'element_id' => $element->getId(),
      'token'      => $bux->getPersonalHash($element->getId())
    ));
    
    // On accepete la poposition de joelle
    $crawler = $this->client->request(
      'GET',
      $url_refuse, 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('AZYD AZYLUM Live au Café Provisoire')
    ;
    // Les tags de l'élément n'ont pas bougés
    $this->assertEquals(
      json_encode(array($metal->getId())),
      $element->getTagsIdsJson()
    );
    
    // La proposition de joelle a disparu
    $propositions = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT p, t FROM MuzichCoreBundle:ElementTagsProposition p'
        .' JOIN p.tags t WHERE p.element = :eid AND p.user = :uid')
      ->setParameters(array(
        'eid' => $element->getId(),
        'uid'    => $joelle->getId()
      ))
      ->getResult()
    ;
    $this->assertEquals(0, count($propositions));
    
    // celle de paul aussi 
    $propositions = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT p, t FROM MuzichCoreBundle:ElementTagsProposition p'
        .' JOIN p.tags t WHERE p.element = :eid AND p.user = :uid')
      ->setParameters(array(
        'eid' => $element->getId(),
        'uid'    => $paul->getId()
      ))
      ->getResult()
    ;
    $this->assertEquals(0, count($propositions));
    
    // Et on as pas d'archive pour joelle
    $archives = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT a FROM MuzichCoreBundle:EventArchive a'
        .' WHERE a.user = :uid AND a.type = :type')
      ->setParameters(array(
        'uid'    => $joelle->getId(),
        'type'    => \Muzich\CoreBundle\Entity\EventArchive::PROP_TAGS_ELEMENT_ACCEPTED
      ))
      ->getResult()
    ;
    $this->assertEquals(0, count($archives));
    
    // paul lui n'a pas d'archives non plus
    $archives = $this->getDoctrine()->getEntityManager()
      ->createQuery('SELECT a FROM MuzichCoreBundle:EventArchive a'
        .' WHERE a.user = :uid AND a.type = :type')
      ->setParameters(array(
        'uid'    => $paul->getId(),
        'type'    => \Muzich\CoreBundle\Entity\EventArchive::PROP_TAGS_ELEMENT_ACCEPTED
      ))
      ->getResult()
    ;
    $this->assertEquals(0, count($archives));
       
    // contrôle de l'évolution des points
    $bux = $this->getUser('bux');
    $joelle = $this->getUser('joelle');
    $paul = $this->getUser('paul');
    
    $this->assertEquals($points_bux, $bux->getReputation());
    $this->assertEquals($points_joelle, $joelle->getReputation());
    $this->assertEquals($points_paul, $paul->getReputation());
    
  }
  
  public function testResharing()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $paul = $this->getUser();
    
    // Cet élément a été partagé par bux
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('AZYD AZYLUM Live au Café Provisoire')
    ;
    
    // On effectue la requete ajax
    // paul propose une serie de tags
    $crawler = $this->client->request(
      'POST', 
      $this->generateUrl('ajax_reshare_element', 
        array(
          'element_id' => $element->getId(), 
          'token' => $paul->getPersonalHash('reshare_'.$element->getId())
       )
      ), 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    // tout c'est bien passé
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // L'objet est en base
    $element_reshared = $this->findOneBy('Element', array(
      'name'   => 'AZYD AZYLUM Live au Café Provisoire',
      'owner'  => $paul->getId(),
      'parent' => $element->getId()
    ));
    
    // L'objet est bien en base
    $this->assertTrue(!is_null($element_reshared));
    
    
  }
  
  public function testAddElementNeedTags()
  {
    $this->client = self::createClient();
    $this->connectUser('joelle', 'toor');
    
    $joelle = $this->getUser();
    
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
      
    // L'élément n'existe pas encore
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Musique qui dechire bis4d5456aqd')
    ;
    $this->assertTrue(is_null($element));
    
    // On commence par ajouter un tag
    $url = $this->generateUrl('element_add');
   
    $extract = $this->crawler->filter('input[name="element_add[_token]"]')
      ->extract(array('value'));
    $csrf = $extract[0];
    
    $crawler = $this->client->request(
      'POST', 
      $url, 
      array(
          'element_add' => array(
              '_token'     => $csrf,
              'name'       => 'Musique qui dechire bis4d5456aqd',
              'url'        => 'http://www.youtube.com/watch?v=WC8qb_of04E',
              'tags'       => json_encode(array($hardtek->getId(), $tribe->getId())),
              'need_tags'  => '1'
          )
        
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    
    $element_need_tags = $this->findOneBy('Element', array(
      'name'   => 'Musique qui dechire bis4d5456aqd',
      'owner'  => $joelle->getId(),
      'need_tags' => true
    ));
    
    // L'objet est bien en base
    $this->assertTrue(!is_null($element_need_tags));
    
  }
  
  public function testDatasApi()
  {
    $this->client = self::createClient();
    $this->connectUser('joelle', 'toor');
    
    $joelle = $this->getUser();
    $url = $this->generateUrl('element_retrieve_api_datas');
    
    $crawler = $this->client->request(
      'POST', 
      $url, 
      array(
        'element_add' => array('url' => 'http://www.jamendo.com/fr/album/30661')
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals(array(
    'status' => 'success',
    'name' => 'ZwaNe 01 - Ptit lutin',
    'tags' => array(
      0 => array(
        'original_name' => 'Basse',
        'like_found' => false,
        'like' => array()
      ),
      1 => array(
        'original_name' => 'Batterie',
        'like_found' => true,
        'like' => array(
          'name' => 'Batterie',
          'id' => '495',
          'slug' => 'batterie',
        )
      ),
      2 => array(
        'original_name' => 'Hardtek',
        'like_found' => true,
        'like' => array(
          'name' => 'Hardtek',
          'id' => '174',
          'slug' => 'hardtek',
        )
      ),
      3 => array(
        'original_name' => 'Tek',
        'like_found' => false,
        'like' => array()
      ),
    ),
    'thumb' => 'http://imgjam.com/albums/s30/30661/covers/1.100.jpg'
    ), $response);
    
    
    
  }
  
}