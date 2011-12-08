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
    
    // L'élément est présent sur la page
    $this->exist('li:contains("DUDELDRUM")');
    $this->exist('a[href="'.($url = $this->generateUrl('favorite_add', array(
      'id'    => $element_DUDELDRUM->getId(),
      'token' => $this->getUser()->getPersonalHash()
    ))).'"]');
    $link = $this->selectLink('a[href="'.$url.'"]');
    $this->clickOnLink($link);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // Le lien pour ajouter aux favoris a disparus
    $this->exist('li:contains("DUDELDRUM")');
    $this->notExist('a[href="'.$url.'"]');
    
    // En base l'enregistrement existe
    $favorite = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')->findOneBy(array(
     'user'    => $this->getUser()->getId(),
     'element' => $element_DUDELDRUM->getId()
    ));
    
    $this->assertTrue(!is_null($favorite));
    
    // On se rend sur la page de ses favoris
    $this->crawler = $this->client->request('GET', $this->generateUrl('favorites_my_list'));
    
    $this->exist('li:contains("DUDELDRUM")');
  }
  
}