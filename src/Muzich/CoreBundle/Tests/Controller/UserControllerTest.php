<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;

class UserControllerTest extends FunctionalTest
{
  
  public function testTagsFavoritesSuccess()
  {
    /**
     * Inscription d'un utilisateur
     */
    $this->client = self::createClient();

    $hardtek_id = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek')->getId();
    $tribe_id   = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe')->getId();
    
    $this->crawler = $this->client->request('GET', $this->generateUrl('index'));
    $this->isResponseSuccess();

    $this->procedure_registration_success(
      'raoulc', 
      'raoulc.def4v65sds@gmail.com', 
      'toor', 
      'toor'
    );
    
    // Il ne doit y avoir aucun enregistrements de tags favoris
    $Favorites = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array(
          'user' => $this->getUser()->getId()
      ))
    ;
    
    $this->assertEquals(0, count($Favorites));
    
    // On a attérit sur la page de présentation et de sleection des tags favoris
    $this->exist('form[action="'.($url = $this->generateUrl('update_tag_favorites')).'"]');
    
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['tag_favorites_form[tags]['.$hardtek_id.']'] = $hardtek_id;
    $form['tag_favorites_form[tags]['.$tribe_id.']'] = $tribe_id;
    $this->submit($form);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // Désormais il y a deux tags favoris pour cet utilisateur
    $Favorites = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array(
          'user' => $this->getUser()->getId()
      ))
    ;
    $this->assertEquals(2, count($Favorites));
    
    $Favorites = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array(
          'user' => $this->getUser()->getId(),
          'tag'  => $hardtek_id
      ))
    ;
    $this->assertEquals(1, count($Favorites));
    
    $Favorites = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array(
          'user' => $this->getUser()->getId(),
          'tag'  => $tribe_id
      ))
    ;
    $this->assertEquals(1, count($Favorites));
  }
  
  /**
   * Test du changement de mot de passe par le baisis de la page 'Mon compte'
   */
  public function testChangePassword()
  {
    $this->client = self::createClient();
    $this->connectUser('bux', 'toor');
    
    // Ouverture de la page Mon compte
    $this->crawler = $this->client->request('GET', $this->generateUrl('my_account'));
    
    $this->exist('form[action="'.($url = $this->generateUrl(
      'change_password'
    )).'"]');
    $this->exist('form[action="'.$url.'"] input[id="fos_user_change_password_form_current"]');
    $this->exist('form[action="'.$url.'"] input[id="fos_user_change_password_form_new_first"]');
    $this->exist('form[action="'.$url.'"] input[id="fos_user_change_password_form_new_second"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['fos_user_change_password_form[current]'] = 'toor';
    $form['fos_user_change_password_form[new][first]'] = 'trololo';
    $form['fos_user_change_password_form[new][second]'] = 'trololo';
    $this->submit($form);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // On se déconnecte
    $this->disconnectUser();
    
    // Et on se connecte avec le nouveau mot de passe
    $this->connectUser('bux', 'trololo');
  }
  
  /**
   * Test du formulaire de mise a jour des tags par le baisis de la page 'Mon compte'
   */
  public function testUpdateFavoriteTags()
  {
    $this->client = self::createClient();
    $this->connectUser('bob', 'toor');
    
    // Ouverture de la page Mon compte
    $this->crawler = $this->client->request('GET', $this->generateUrl('my_account'));
    
    $hardtek_id = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek')->getId();
    $tribe_id   = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe')->getId();
    
    // Bob n'a aucun tag préféré
    $prefereds = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array('user' => $this->getUser()->getId()))
    ;
    
    $this->assertEquals(0, count($prefereds));
    
    $this->exist('form[action="'.($url = $this->generateUrl(
      'update_tag_favorites', array('redirect' => 'account')
    )).'"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['tag_favorites_form[tags]['.$hardtek_id.']'] = $hardtek_id;
    $form['tag_favorites_form[tags]['.$tribe_id.']'] = $tribe_id;
    $this->submit($form);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // On a été redirigé sur la page Mon compte
    $this->exist('form[action="'.$url.'"]');
    
    // On vérifie la présence en base des enregistrements
    $prefereds = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array('user' => $this->getUser()->getId()))
    ;
    
    $this->assertEquals(2, count($prefereds));
    
    // On vérifie la présence en base des enregistrements
    $prefereds = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array(
          'user' => $this->getUser()->getId(),
          'tag'  => $hardtek_id
      ))
    ;
    
    $this->assertEquals(1, count($prefereds));
    
    // On vérifie la présence en base des enregistrements
    $prefereds = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array(
          'user' => $this->getUser()->getId(),
          'tag'  => $tribe_id
      ))
    ;
    
    $this->assertEquals(1, count($prefereds));
  }
  
}