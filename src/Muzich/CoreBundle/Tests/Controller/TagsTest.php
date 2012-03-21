<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;

class TagsTest extends FunctionalTest
{ 
  /**
   * Test du listing de ses favoris
   */
  public function testAddTag()
  {    
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    // On commence par ajouter un tag
    $url = $this->generateUrl('ajax_add_tag', array('name' => 'Mon Beau Tag'));
    
    $crawler = $this->client->request('GET', $url, array(), array(), array(
        'X-Requested-With' => 'XMLHttpRequest',
    ));
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    $tag = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->findOneByName('Mon beau tag');
    
    $this->assertTrue(!is_null($tag));
    
    // Paul ajoute un élément avec ce tag
    $this->procedure_add_element(
      'Un nouvel élément', 
      'http://www.youtube.com/watch?v=WC8qb_of04E', 
      array($tag->getId())
    );
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Un nouvel élément')
    ;
    
    $paul = $this->getUser();
    
    // Il ajoute cet élément en favoris
    $url = $this->generateUrl('favorite_add', array(
      'id'    => $element->getId(),
      'token' => $paul->getPersonalHash()
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
    
    /*
     * Contrôle de la vue de ce tag
     */
    
    // Paul, l'ayant ajouté peut le voir.
    
    // sur la page home (l'élément qu'il vient d'envoyer)
    $this->client->request('GET', $this->generateUrl('home'));
    $this->isResponseSuccess();
    
    $this->exist('li.element_tag');
    $this->exist('li.element_tag:contains("Mon beau tag")');
    
    // sur sa page de favoris
    $this->client->request('GET', $this->generateUrl('favorites_my_list'));
    $this->isResponseSuccess();
    
    $this->exist('li.element_tag');
    $this->exist('li.element_tag:contains("Mon beau tag")');
    $this->exist('body:contains("Mon beau tag")');
    
    // Lors d'une recherche de tag
    $url = $this->generateUrl('search_tag', array(
      'string_search' => 'mon beau tag',
      'timestamp'     => time()
    ));
    $crawler = $this->client->request('GET', $url, array(), array(), array(
        'HTTP_X-Requested-With' => 'XMLHttpRequest'
    ));
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertTrue($this->findTagNameInResponse($response, 'Mon beau tag', 'data'));
    
    // En revanche, bux ne pourra pas les voirs lui.
    // Sur la page home, sur la page des favoris de paul, et sur le profil de paul
    $this->disconnectUser();
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    // On enlève les tags pour être sur d'avoir l'élément ajouté par paul
    $this->client->request('GET', $this->generateUrl('filter_clear'));
    $this->client->request('GET', $this->generateUrl('home'));
    $this->isResponseSuccess();
    
    $this->exist('li.element_tag');
    $this->notExist('li.element_tag:contains("Mon beau tag")');
    
    // sur la page de favoris de paul
    $this->client->request('GET', $this->generateUrl('favorite_user_list', array('slug' => $paul->getSlug())));
    $this->isResponseSuccess();
    
    $this->exist('li.element_tag');
    $this->notExist('li.element_tag:contains("Mon beau tag")');
    $this->notExist('body:contains("Mon beau tag")');
    
    // sur la page de profil de paul
    $this->client->request('GET', $this->generateUrl('show_user', array('slug' => $paul->getSlug())));
    $this->isResponseSuccess();
    
    $this->exist('li.element_tag');
    $this->notExist('li.element_tag:contains("Mon beau tag")');
    $this->notExist('body:contains("Mon beau tag")');
    
    // Lors d'une recherche de tag
    $url = $this->generateUrl('search_tag', array(
      'string_search' => 'mon beau tag',
      'timestamp'     => time()
    ));
    $crawler = $this->client->request('GET', $url, array(), array(), array(
        'HTTP_X-Requested-With' => 'XMLHttpRequest'
    ));
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertFalse($this->findTagNameInResponse($response, 'Mon beau tag', 'data'));
  }
  
  protected function findTagNameInResponse($response = array(), $name = null, $i = null)
  {
    if (count($response))
    {
      if (array_key_exists($i, $response))
      {
        foreach ($response[$i] as $tag)
        {
          if ($tag['name'] == $name)
          {
            return true;
          }
        }
        return false;
      }
    }
  }
    
}