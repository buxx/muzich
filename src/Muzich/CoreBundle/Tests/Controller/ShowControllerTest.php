<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;

class ShowControllerTest extends FunctionalTest
{
  
  public function testViewMoreOnProfile()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    $bux = $this->getUser();
    
    $hardtek_id = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek')->getId();
    $tribe_id   = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe')->getId();

    // En premier lieux on va devoir ajouter des éléments.
    $this->addElementAjax('PoPElement 1', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)));
    $this->addElementAjax('PoPElement 2', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)));
    $this->addElementAjax('PoPElement 3', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)));
    $this->addElementAjax('PoPElement 4', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)));
    
    // D'après les fixtures on aura 2 éléments en plus des 10 (paramétre) de la page
    // Ouverture de la page profil
    $this->crawler = $this->client->request('GET', $this->generateUrl('show_user', array('slug' => $bux->getSlug())));
    $this->isResponseSuccess();
    
    $this->exist('span.element_name:contains("PoPElement 4")'); 
    $this->exist('span.element_name:contains("PoPElement 3")'); 
    $this->exist('span.element_name:contains("PoPElement 2")'); 
    $this->exist('span.element_name:contains("PoPElement 1")'); 
    $this->exist('span.element_name:contains("Ed Cox - La fanfare des teuffeurs (Hardcordian)")'); 
    $this->exist('span.element_name:contains("Babylon Pression - Des Tasers et des Pauvres")'); 
    $this->exist('span.element_name:contains("AZYD AZYLUM Live au Café Provisoire")'); 
    $this->exist('span.element_name:contains("SOULFLY - Prophecy")'); 
    $this->exist('span.element_name:contains("KoinkOin - H5N1")'); 
    $this->exist('span.element_name:contains("Antropod - Polakatek")'); 
    $this->notExist('span.element_name:contains("Dtc che passdrop")'); 
    $this->notExist('span.element_name:contains("Heretik System Popof - Resistance")'); 
    
    // L'élément de référence est celui en bas de la liste
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Antropod - Polakatek')
    ;
    $tags = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
      ->getElementsTags($bux->getId(), $bux->getId())      
    ;
    
    $ids = array();
    foreach ($tags as $tag)
    {
      $ids[] = $tag->getId();
    }
    
    // On fait la demande pour en voir plus
    $url = $this->generateUrl('show_elements_get', array(
      'type' => 'user',
      'object_id' => $bux->getId(),
      'tags_ids_json' => json_encode($ids),
      'id_limit' => $element->getId()
    ));
    $crawler = $this->client->request(
      'GET', 
      $url, 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    $this->assertTrue(strpos($response['html'], 'Dtc che passdrop') !== false);
    $this->assertTrue(strpos($response['html'], 'Heretik System Popof - Resistance') !== false);
  }
  
  public function testViewMoreOnGroup()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    $bux = $this->getUser();    
    $fan_de_psy = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('Fans de psytrance');

    $hardtek_id = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek')->getId();
    $tribe_id   = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe')->getId();

    // En premier lieux on va devoir ajouter des éléments.
    $this->addElementAjax('PsyElement 1', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)), $fan_de_psy->getSlug());
    $this->addElementAjax('PsyElement 2', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)), $fan_de_psy->getSlug());
    $this->addElementAjax('PsyElement 3', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)), $fan_de_psy->getSlug());
    $this->addElementAjax('PsyElement 4', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)), $fan_de_psy->getSlug());
    $this->addElementAjax('PsyElement 5', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)), $fan_de_psy->getSlug());
    $this->addElementAjax('PsyElement 6', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)), $fan_de_psy->getSlug());
    $this->addElementAjax('PsyElement 7', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)), $fan_de_psy->getSlug());
    $this->addElementAjax('PsyElement 8', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)), $fan_de_psy->getSlug());
    $this->addElementAjax('PsyElement 9', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)), $fan_de_psy->getSlug());
    $this->addElementAjax('PsyElement 10', 'http://labas.com', json_encode(array($hardtek_id, $tribe_id)), $fan_de_psy->getSlug());
    
    // D'après les fixtures on aura 2 éléments en plus des 10 (paramétre) de la page
    // Ouverture de la page profil
    $this->crawler = $this->client->request('GET', $this->generateUrl('show_group', array('slug' => $fan_de_psy->getSlug())));
    $this->isResponseSuccess();
    
    $this->exist('span.element_name:contains("PsyElement 10")'); 
    $this->exist('span.element_name:contains("PsyElement 9")'); 
    $this->exist('span.element_name:contains("PsyElement 8")'); 
    $this->exist('span.element_name:contains("PsyElement 7")'); 
    $this->exist('span.element_name:contains("PsyElement 6")'); 
    $this->exist('span.element_name:contains("PsyElement 5")'); 
    $this->exist('span.element_name:contains("PsyElement 4")'); 
    $this->exist('span.element_name:contains("PsyElement 3")'); 
    $this->exist('span.element_name:contains("PsyElement 5")'); 
    $this->exist('span.element_name:contains("PsyElement 1")'); 
    $this->notExist('span.element_name:contains("Infected mushroom - Muse Breaks")'); 
    $this->notExist('span.element_name:contains("Infected Mushroom - Psycho")'); 
    
    // L'élément de référence est celui en bas de la liste, donc le premier ajouté
    // la haut (vuq ue l'on en a ajouté 10)
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('PsyElement 1')
    ;
    $tags = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')
      ->getElementsTags($fan_de_psy->getId(), $bux->getId())      
    ;
    
    $ids = array();
    foreach ($tags as $tag)
    {
      $ids[] = $tag->getId();
    }
    
    // On fait la demande pour en voir plus
    $url = $this->generateUrl('show_elements_get', array(
      'type' => 'group',
      'object_id' => $fan_de_psy->getId(),
      'tags_ids_json' => json_encode($ids),
      'id_limit' => $element->getId()
    ));
    $crawler = $this->client->request(
      'GET', 
      $url, 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    $this->assertTrue(strpos($response['html'], 'Infected mushroom - Muse Breaks') !== false);
    $this->assertTrue(strpos($response['html'], 'Infected Mushroom - Psycho') !== false);
  }
  
}