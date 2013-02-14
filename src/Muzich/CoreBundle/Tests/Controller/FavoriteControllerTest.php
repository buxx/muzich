<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;

class FavoriteControllerTest extends FunctionalTest
{
 
  /**
   * Test du listing de ses favoris
   */
  public function testMyFavorites()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    // On va cliquer sur le lien 'Mes favoris'
    $this->exist('a[href="'.($url = $this->generateUrl('favorites_my_list')).'"]');
    $link = $this->selectLink('a[href="'.$url.'"]');
    // On clique dessus
    $this->clickOnLink($link);
    $this->isResponseSuccess();
        
    $elements = $this->getDoctrine()->getEntityManager()->createQuery("
      SELECT e FROM MuzichCoreBundle:Element e
      LEFT JOIN e.elements_favorites ef
      WHERE ef.user = :uid
    ")->setParameter('uid', $this->getUser()->getId())
      ->getResult()
    ;
    
    $this->assertTrue(!is_null($elements));
    
    if (count($elements))
    {
      foreach ($elements as $element)
      {
        $this->exist('li:contains("'.$element->getName().'")');
      }
    }
  }
  
  /**
   * Test de la page listant les favoris d'un utilisateur
   */
  public function testHisFavorites()
  {
    $this->client = self::createClient();
    $this->connectUser('bob', 'toor');
    
    $bux = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bux')
    ;
    
    // On se rend sur sa page des favoris de bux
    $this->crawler = $this->client->request('GET', $this->generateUrl('favorite_user_list', array('slug' => 'bux')));
           
    $elements = $this->getDoctrine()->getEntityManager()->createQuery("
      SELECT e FROM MuzichCoreBundle:Element e
      LEFT JOIN e.elements_favorites ef
      WHERE ef.user = :uid
    ")->setParameter('uid', $bux->getId())
      ->getResult()
    ;
    
    $this->assertTrue(!is_null($elements));
    
    if (count($elements))
    {
      foreach ($elements as $element)
      {
        $this->exist('li:contains("'.$element->getName().'")');
      }
    }
  }
  
  /**
   * Test vérifiant que l'étoile apparait bien lorsque un élement est en favoris
   * Test d'affichage dom en somme
   */
  public function testStarFavorites()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    $bux = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bux')
    ;
    
    // On se rend sur sa page des favoris de bux
    $this->crawler = $this->client->request('GET', $this->generateUrl('favorite_user_list', array('slug' => 'bux')));
           
    $elements = $this->getDoctrine()->getEntityManager()->createQuery("
      SELECT e FROM MuzichCoreBundle:Element e
      LEFT JOIN e.elements_favorites ef
      WHERE ef.user = :uid
    ")->setParameter('uid', $bux->getId())
      ->getResult()
    ;
    
    $this->assertTrue(!is_null($elements));
    
    if (count($elements))
    {
      foreach ($elements as $element)
      {
        $this->exist('img#favorite_'.$element->getId().'_is');
      }
    }
  }
  
  /**
   * Test d'ajout en favori un element, puis son retrait
   * Ce test dépend actuellement du fait que l'élément testé se truove sur la 
   * page du groupe en question
   */
  public function testFavoritesManagement()
  {
    $this->client = self::createClient();
    $this->connectUser('bob', 'toor');
    
    // On se rend sur la page du groupe Dudeldrum
    $DUDELDRUM = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')
      ->findOneBySlug('dudeldrum')
      ->getSingleResult()
    ;
    
    $element_DUDELDRUM = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('DUDELDRUM')
    ;
    
    // En base l'enregistrement n'existe pas encore
    $favorite = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')->findOneBy(array(
     'user'    => $this->getUser()->getId(),
     'element' => $element_DUDELDRUM->getId()
    ));
    
    $this->assertTrue(is_null($favorite));
    
    $this->crawler = $this->client->request(
      'GET', 
      $this->generateUrl('show_group', array('slug' => $DUDELDRUM->getSlug()))
    );
    
    $this->isResponseSuccess();
    
    // Controle de l'évolution du score de joelle
    $joelle = $this->getUser('joelle');
    $this->assertEquals($joelle->getReputation(), 2);
    
    // L'élément est présent sur la page
    $this->exist('li:contains("DUDELDRUM")');
    $this->exist('a[href="'.($url = $this->generateUrl('favorite_add', array(
      'id'    => $element_DUDELDRUM->getId(),
      'token' => $this->getUser()->getPersonalHash($element_DUDELDRUM->getId())
    ))).'"]');
    $link = $this->selectLink('a[href="'.$url.'"]');
    $this->clickOnLink($link);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // Le lien pour ajouter aux favoris a disparus
    $this->exist('li:contains("DUDELDRUM")');
    $this->notExist('a[href="'.$url.'"]');
    
    // Il a laissé place aux lien pour le retirer
    $this->exist('a[href="'.($url_rm = $this->generateUrl('favorite_remove', array(
      'id'    => $element_DUDELDRUM->getId(),
      'token' => $this->getUser()->getPersonalHash($element_DUDELDRUM->getId())
    ))).'"]');
    
    // En base l'enregistrement existe
    $favorite = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')->findOneBy(array(
     'user'    => $this->getUser()->getId(),
     'element' => $element_DUDELDRUM->getId()
    ));
    
    $this->assertTrue(!is_null($favorite));
    
    // Controle de l'évolution du score de joelle
    $joelle = $this->getUser('joelle');
    $this->assertEquals($joelle->getReputation(), 7);
    
    // On se rend sur la page de ses favoris
    $this->crawler = $this->client->request('GET', $this->generateUrl('favorites_my_list'));
    
    $this->exist('li:contains("DUDELDRUM")');
    
    // On va maintenant le retirer de nox favoris
    $this->exist('a[href="'.$url_rm.'"]');
    $link = $this->selectLink('a[href="'.$url_rm.'"]');
    $this->clickOnLink($link);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $this->NotExist('li:contains("DUDELDRUM")');
    
    // En base l'enregistrement a été supprimé
    $favorite = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')->findOneBy(array(
     'user'    => $this->getUser()->getId(),
     'element' => $element_DUDELDRUM->getId()
    ));
    
    $this->assertTrue(is_null($favorite));
    
    // Controle de l'évolution du score de joelle
    $joelle = $this->getUser('joelle');
    $this->assertEquals($joelle->getReputation(), 2);
  }
  
  public function testAjax()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    $bux = $this->getUser();
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Ed Cox - La fanfare des teuffeurs (Hardcordian)')
    ;
    
    // Controle de l'évolution du score de joelle
    $bux = $this->getUser('bux');
    $this->assertEquals($bux->getReputation(), 22);
    
    // Ajout d'un élément en favoris
    // Il ajoute cet élément en favoris
    $url = $this->generateUrl('favorite_add', array(
      'id'    => $element->getId(),
      'token' => $bux->getPersonalHash($element->getId())
    ));
    
    $crawler = $this->client->request('GET', $url, array(), array(), array(
        'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    
    $this->isResponseSuccess();
    
    // On contrôle la présence du favoris
    $fav = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->findOneBy(array(
        'user'    => $bux->getId(),
        'element' => $element->getId()
      ));
    
    $this->assertTrue(!is_null($fav));
    
    // Controle de l'évolution du score de joelle
    $bux = $this->getUser('bux');
    $this->assertEquals($bux->getReputation(), 22);
    
    // On enlève des favoris
    $url = $this->generateUrl('favorite_remove', array(
      'id'    => $element->getId(),
      'token' => $bux->getPersonalHash($element->getId())
    ));
    
    $crawler = $this->client->request('GET', $url, array(), array(), array(
        'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    
    // On contrôle l'absence du favoris
    $fav = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->findOneBy(array(
        'user'    => $bux->getId(),
        'element' => $element->getId()
      ));
    
    $this->assertTrue(is_null($fav));
    
    // Controle de l'évolution du score de joelle
    $bux = $this->getUser('bux');
    $this->assertEquals($bux->getReputation(), 22);
  }
  
  /**
   * Test de la fonction ajax de récupération de plus de favoris
   */
  public function testFilterElements()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $paul = $this->getUser();
        
    $hardtek_id = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek')->getId();
    $tribe_id   = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe')->getId();
    
    // Ouverture de la page favoris
    $this->crawler = $this->client->request('GET', $this->generateUrl('favorites_my_list'));
    
    // On doit voir deux elements pour paul
    $this->exist('span.element_name:contains("All Is Full Of Pain")');
    $this->exist('span.element_name:contains("Heretik System Popof - Resistance")');
    
    // Récupération de la liste avec la kekete ajax pour Tribe
    $url = $this->generateUrl('favorite_get', array(
      'user_id'       => $paul->getId(), // Utilisateur pour lequel on demande les favoris
      'tags_ids_json' => json_encode(array($tribe_id))
    ));
    $this->crawler = $this->client->request('GET', $url, array(), array(), array(
      'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    
    $response_content = json_decode($this->client->getResponse()->getContent(), true);
    $html = $response_content['html'];
    
    $this->isResponseSuccess();
    $this->assertTrue(strpos($html, 'All Is Full Of Pain') !== false);
    $this->assertTrue(strpos($html, 'Heretik System Popof - Resistance') === false);
    
    // Récupération de la liste avec la kekete ajax pour Hardtek
    $url = $this->generateUrl('favorite_get', array(
      'user_id'       => $paul->getId(), // Utilisateur pour lequel on demande les favoris
      'tags_ids_json' => json_encode(array($hardtek_id))
    ));
    
    $this->crawler = $this->client->request('GET', $url, array(), array(), array(
      'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    
    $this->isResponseSuccess();
    
    $response_content = json_decode($this->client->getResponse()->getContent(), true);
    $html = $response_content['html'];
    
    $this->isResponseSuccess();
    $this->assertTrue(strpos($html, 'All Is Full Of Pain') !== false);
    $this->assertTrue(strpos($html, 'Heretik System Popof - Resistance') !== false);
    
    // Récupération de la liste avec la kekete ajax pour Tribe + Hardtek
    $url = $this->generateUrl('favorite_get', array(
      'user_id'       => $paul->getId(), // Utilisateur pour lequel on demande les favoris
      'tags_ids_json' => json_encode(array($hardtek_id, $tribe_id))
    ));
    
    $this->crawler = $this->client->request('GET', $url, array(), array(), array(
      'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    
    $this->isResponseSuccess();
    
    $response_content = json_decode($this->client->getResponse()->getContent(), true);
    $html = $response_content['html'];
    
    $this->isResponseSuccess();
    $this->assertTrue(strpos($html, 'All Is Full Of Pain') !== false);
    $this->assertTrue(strpos($html, 'Heretik System Popof - Resistance') !== false);
  }
  
}