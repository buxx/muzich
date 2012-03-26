<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;

class MynetworkControllerTest extends FunctionalTest
{
  /**
   * Test de l'affichage de la page "mon réseau"
   */
  public function testMyNetwork()
  {
    /**
     * Avec l'utilisateur 'bux' , qui d'après les fixtures suis: jean, paul,
     * les groupes DUDELDRUM et Fans de psytrance
     * et est suivis par joelle
     */
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    $link = $this->selectLink('a[href="'.$this->generateUrl('mynetwork_index').'"]');
    $this->clickOnLink($link);
    $this->isResponseSuccess();
    
    // Récupération des entités
    $jean = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneByUsername('jean');
    $paul = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneByUsername('paul');
    $joelle = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneByUsername('joelle');
    $DUDELDRUM = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('DUDELDRUM');
    $Fans_de_psytrance = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('Fans de psytrance');
    
    $this->exist('ul#followeds_users li a[href="'.$this->generateUrl('show_user', array('slug' => $jean->getSlug())).'"]');
    $this->exist('ul#followeds_users li a[href="'.$this->generateUrl('show_user', array('slug' => $paul->getSlug())).'"]');
    $this->exist('ul#followers_users li a[href="'.$this->generateUrl('show_user', array('slug' => $joelle->getSlug())).'"]');
    $this->exist('ul#followeds_groups li a[href="'.$this->generateUrl('show_group', array('slug' => $DUDELDRUM->getSlug())).'"]');
    $this->exist('ul#followeds_groups li a[href="'.$this->generateUrl('show_group', array('slug' => $Fans_de_psytrance->getSlug())).'"]');
  }
  
  /**
   * Test de la recherche
   */
  public function testSearch()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    $link = $this->selectLink('a[href="'.$this->generateUrl('mynetwork_index').'"]');
    $this->clickOnLink($link);
    $this->isResponseSuccess();
    $link = $this->selectLink('a[href="'.$this->generateUrl('mynetwork_search').'"]');
    $this->clickOnLink($link);
    $this->isResponseSuccess();
    
    $this->exist('form[action="'.($url = $this->generateUrl('mynetwork_search')).'"]');
    $this->exist('form[action="'.$url.'"] input[id="form_string"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    $bob = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneByUsername('bob');
    // On va rechercher l'utilisateur bob
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['form[string]'] = 'bob';
    $this->submit($form);
    
    $this->isResponseSuccess();
    
    // On trouve bob
    $this->exist('ul#search_users li a[href="'.$this->generateUrl('show_user', array('slug' => $bob->getSlug())).'"]');
    
    $joelle = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneByUsername('joelle');
    $Le_groupe_de_joelle = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('Le groupe de joelle');
    // On va maintenant rechercher le groupe de joelle
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['form[string]'] = 'joelle';
    $this->submit($form);
    $this->isResponseSuccess();
    
    // On trouve joelle mais aussi son groupe (il y a joelle dans le nom)
    $this->exist('ul#search_users li a[href="'.$this->generateUrl('show_user', array('slug' => $joelle->getSlug())).'"]');
    $this->exist('ul#search_groups li a[href="'.$this->generateUrl('show_group', array('slug' => $Le_groupe_de_joelle->getSlug())).'"]');
  }
  
  /**
   * Test de l'action 'suivre' et 'ne plus suivre' sur un user
   */
  public function testUserFollow()
  {
    $this->client = self::createClient();
    // Connection de bob
    $this->connectUser('bob', 'toor');
    
    // Récupération des entités (bob ne les suit pas)
    $bux = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneByUsername('bux');
    
    // On tente de récupérer l'entité FollowUser
    $FollowUser = $this->getDoctrine()->getRepository('MuzichCoreBundle:FollowUser')
      ->findOneBy(array(
        'follower' => $this->getUser()->getId(),
        'followed' => $bux->getId()
      ))
    ;
    
    // Mais celle-ci doit-être innexistante
    $this->assertTrue(is_null($FollowUser));
    
    // Ouverture de la page d'un user (bux)
    $this->crawler = $this->client->request('GET', $this->generateUrl('show_user', array('slug' => $bux->getSlug())));
    $this->isResponseSuccess();
    
    // Controle de l'évolution du score de bux
    $bux = $this->getUser('bux');
    $this->assertEquals($bux->getReputation(), 22);
    
    $url_follow = $this->generateUrl('follow', array(
      'type' => 'user', 
      'id' => $bux->getId(),
      'token' => $this->getUser()->getPersonalHash()
    ));
    
    // On lance l'action de suivre
    $this->exist('a.notfollowing[href="'.$url_follow.'"]');
    $link = $this->selectLink('a[href="'.$url_follow.'"]');
    $this->clickOnLink($link);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $this->exist('a.following[href="'.$url_follow.'"]');
    
    // On tente de récupérer l'entité FollowUser
    $FollowUser = $this->getDoctrine()->getRepository('MuzichCoreBundle:FollowUser')
      ->findOneBy(array(
        'follower' => $this->getUser()->getId(),
        'followed' => $bux->getId()
      ))
    ;
    
    // Celle-ci doit exister maintenant
    $this->assertTrue(!is_null($FollowUser));
    
    // Controle de l'évolution du score de bux
    $bux = $this->getUser('bux');
    $this->assertEquals($bux->getReputation(), 32);
    
    // On lance l'action de ne plus suivre
    $link = $this->selectLink('a[href="'.$url_follow.'"]');
    $this->clickOnLink($link);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $this->exist('a.notfollowing[href="'.$url_follow.'"]');
    
    // On tente de récupérer l'entité FollowUser
    $FollowUser = $this->getDoctrine()->getRepository('MuzichCoreBundle:FollowUser')
      ->findOneBy(array(
        'follower' => $this->getUser()->getId(),
        'followed' => $bux->getId()
      ))
    ;
    
    // Celle-ci ne doit plus exister maintenant
    $this->assertTrue(is_null($FollowUser));
    
    // Controle de l'évolution du score de bux
    $bux = $this->getUser('bux');
    $this->assertEquals($bux->getReputation(), 22);
  }
  
  /**
   * Test de l'action 'suivre' et 'ne plus suivre' sur un groupe
   */
  public function testGroupFollow()
  {
    $this->client = self::createClient();
    // Connection de bob
    $this->connectUser('bob', 'toor');
    
    // Récupération des entités (bob ne les suit pas)
    $DUDELDRUM = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('DUDELDRUM');
    
    // On tente de récupérer l'entité FollowUser
    $FollowGroup = $this->getDoctrine()->getRepository('MuzichCoreBundle:FollowGroup')
      ->findOneBy(array(
        'follower' => $this->getUser()->getId(),
        'group' => $DUDELDRUM->getId()
      ))
    ;
    
    // Mais celle-ci doit-être innexistante
    $this->assertTrue(is_null($FollowGroup));
    
    // Ouverture de la page d'un user (bux)
    $this->crawler = $this->client->request('GET', $this->generateUrl('show_group', array('slug' => $DUDELDRUM->getSlug())));
    $this->isResponseSuccess();
    
    $url_follow = $this->generateUrl('follow', array(
      'type' => 'group', 
      'id' => $DUDELDRUM->getId(),
      'token' => $this->getUser()->getPersonalHash()
    ));
    
    // On lance l'action de suivre
    $this->exist('a.notfollowing[href="'.$url_follow.'"]');
    $link = $this->selectLink('a[href="'.$url_follow.'"]');
    $this->clickOnLink($link);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $this->exist('a.following[href="'.$url_follow.'"]');
    
    // On tente de récupérer l'entité FollowUser
    $FollowGroup = $this->getDoctrine()->getRepository('MuzichCoreBundle:FollowGroup')
      ->findOneBy(array(
        'follower' => $this->getUser()->getId(),
        'group' => $DUDELDRUM->getId()
      ))
    ;
    
    // Celle-ci doit exister maintenant
    $this->assertTrue(!is_null($FollowGroup));
    
    // On lance l'action de ne plus suivre
    $link = $this->selectLink('a[href="'.$url_follow.'"]');
    $this->clickOnLink($link);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $this->exist('a.notfollowing[href="'.$url_follow.'"]');
    
    // On tente de récupérer l'entité FollowUser
    $FollowGroup = $this->getDoctrine()->getRepository('MuzichCoreBundle:FollowGroup')
      ->findOneBy(array(
        'follower' => $this->getUser()->getId(),
        'group' => $DUDELDRUM->getId()
      ))
    ;
    
    // Celle-ci ne doit plus exister maintenant
    $this->assertTrue(is_null($FollowGroup));
  }
}
