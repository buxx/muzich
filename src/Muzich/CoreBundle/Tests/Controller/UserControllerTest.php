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
    
    $this->crawler = $this->client->request('GET', $this->generateUrl('home'));
    $this->isResponseSuccess();
  
    $this->procedure_registration_success(
      'raoulc.def4v65sds@gmail.com'
    );
    
    // Il ne doit y avoir aucun enregistrements de tags favoris
    $Favorites = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array(
          'user' => $this->getUser()->getId()
      ))
    ;
    
    $this->assertEquals(0, count($Favorites));
    
    $this->goToPage($this->generateUrl('start'));
    
    // On a attérit sur la page de présentation et de selection des tags favoris
    $this->exist('form[action="'.($url = $this->generateUrl('update_tag_favorites')).'"]');
    
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['tag_favorites_form[tags]'] = json_encode(array($hardtek_id,$tribe_id));
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
      'change_password', array('open' => 'change_password')
    )).'"]');
    $this->exist('form[action="'.$url.'"] input[id="user_password_plain_password_first"]');
    $this->exist('form[action="'.$url.'"] input[id="user_password_plain_password_second"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['user_password[plain_password][first]'] = 'trololo';
    $form['user_password[plain_password][second]'] = 'trololo';
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
    $form['tag_favorites_form[tags]'] = json_encode(array($hardtek_id,$tribe_id));
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
  
  /**
   * Test de al procédure de changement d'email.
   */
  public function testChangeEmail()
  {
    $this->client = self::createClient();
    $this->connectUser('bob', 'toor');
    $bob = $this->findUserByUsername('bob');
    
    // Ouverture de la page Mon compte
    $this->crawler = $this->client->request('GET', $this->generateUrl('my_account'));
    
    // Le mail en cours n'est pas celui que nous voulons mettre
    $this->assertFalse($bob->getEmail() == 'trololololooo@trolo.com');
    // Nous n'avons pas encore demandé de nouveau mail
    $this->assertTrue($bob->getEmailRequested() == null);
    $this->assertTrue($bob->getEmailRequestedDatetime() == null);
    
    // On fait un premier essaie avec une email mal formulé
    $this->exist('form[action="'.($url = $this->generateUrl(
      'change_email_request'
    )).'"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['form[email]'] = 'trololololooo@trolo';
    $this->submit($form);
    
    // Il n'y as pas de redirection
    $this->isResponseSuccess();
    
    $bob = $this->findUserByUsername('bob');
    // Les champs n'ont pas bougés
    $this->assertFalse($bob->getEmail() == 'trololololooo@trolo.com');
    // Nous n'avons pas encore demandé de nouveau mail
    $this->assertTrue($bob->getEmailRequested() == null);
    $this->assertTrue($bob->getEmailRequestedDatetime() == null);
    
    $this->exist('form[action="'.($url = $this->generateUrl(
      'change_email_request'
    )).'"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['form[email]'] = 'trololololooo@trolo.com';
    $this->submit($form);
    
    // Ce coup-ci c'est bien une redirection
    $this->isResponseRedirection();
    
    // Un mail a été envoyé
    $mc = $this->getMailerMessageDataCollector();
    $this->assertEquals(1, $mc->getMessageCount());
    
    $mails = $mc->getMessages();
    $mail = $mails[0];
    
    // Les champs ont bougés
    $bob = $this->findUserByUsername('bob');
    $this->assertFalse($bob->getEmail() == 'trololololooo@trolo.com');
    $this->assertFalse($bob->getEmailRequested() == null);
    $this->assertTrue($bob->getEmailRequested() == 'trololololooo@trolo.com');
    $this->assertFalse($bob->getEmailRequestedDatetime() == null);
    
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // On ouvre un lien erroné
    $badurl = $this->generateUrl(
      'change_email_confirm', 
      array('token' => $this->getUser()->getConfirmationToken()), 
      true
    );
    $this->crawler = $this->client->request('GET', $badurl);
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    $this->exist('div.error');
    
    // Et les champs ont pas bougés
    $bob = $this->findUserByUsername('bob');
    $this->assertFalse($bob->getEmail() == 'trololololooo@trolo.com');
    $this->assertFalse($bob->getEmailRequested() == null);
    $this->assertTrue($bob->getEmailRequested() == 'trololololooo@trolo.com');
    $this->assertFalse($bob->getEmailRequestedDatetime() == null);
    
    $this->assertTrue(!is_null(strpos($mail->getBody(), ($url = $this->generateUrl(
      'change_email_confirm', 
      array('token' => $token = hash('sha256', $bob->getConfirmationToken().'trololololooo@trolo.com')), 
      true
    )))));
    
    // On ouvre le bon lien
    $this->crawler = $this->client->request('GET', $url);
    
    // C'est un succés
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $this->notExist('div.error');
    
    // Et les champs ont bougés
    $bob = $this->findUserByUsername('bob');
    $this->assertTrue($bob->getEmail() == 'trololololooo@trolo.com');
    $this->assertTrue($bob->getEmailRequested() == null);
    $this->assertFalse($bob->getEmailRequestedDatetime() == null);
    
    // Par contre si on refait une demande maintenant ca échoue (délais entre demandes)
    $this->exist('form[action="'.($url = $this->generateUrl(
      'change_email_request'
    )).'"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['form[email]'] = 'trololololooo222@trolo.com';
    $this->submit($form);
    
    // Il n'y as pas de redirection
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    $this->exist('div.error');
    
    // Et les champs ont bougés
    $bob = $this->findUserByUsername('bob');
    $this->assertTrue($bob->getEmail() == 'trololololooo@trolo.com');
    $this->assertTrue($bob->getEmailRequested() == null);
    $this->assertFalse($bob->getEmailRequestedDatetime() == null);
    
    // Si par contre on manipule le dateTime on pourra
    $bob = $this->findUserByUsername('bob');
    $bob->setEmailRequestedDatetime(
      $this->getUser()->getEmailRequestedDatetime() 
      - $this->getContainer()->getParameter('changeemail_security_delay')
    );
    
    $this->getDoctrine()->getManager()->flush();
    
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['form[email]'] = 'trololololooo222@trolo.com';
    $this->submit($form);
    
    // Ce coup-ci c'est bien une redirection
    $this->isResponseRedirection();
    
    // Un mail a été envoyé
    $mc = $this->getMailerMessageDataCollector();
    $this->assertEquals(1, $mc->getMessageCount());
    
    $mails = $mc->getMessages();
    $mail = $mails[0];
       
    $this->assertTrue(!is_null(strpos($mail->getBody(), ($url = $this->generateUrl(
      'change_email_confirm', 
      array('token' => hash('sha256', $this->getUser()->getConfirmationToken().'trololololooo222@trolo.com')), 
      true
    )))));
    
    // Les champs ont bougés
    $bob = $this->findUserByUsername('bob');
    $this->assertFalse($bob->getEmail() == 'trololololooo222@trolo.com');
    $this->assertFalse($bob->getEmailRequested() == null);
    $this->assertTrue($bob->getEmailRequested() == 'trololololooo222@trolo.com');
    $this->assertFalse($bob->getEmailRequestedDatetime() == null);
    
    $this->followRedirection();
    $this->isResponseSuccess();
  }
  
  public function testAddElementTagToFavorites()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $paul = $this->getUser();
    
    // D'après les fixtures paul n'a pas de tags favoris
    $fav = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array(
        'user'    => $paul->getId()
      ))
    ;
    
    $this->assertEquals(0, count($fav));
    
    // Ajout d'un tag en favoris (ajax)
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->findOneByName('Tribe')
    ;
    
    $url = $this->generateUrl('ajax_tag_add_to_favorites', array(
      'tag_id' => $tribe->getId(),
      'token'  => $paul->getPersonalHash($tribe->getId())
    ));
    
    $crawler = $this->client->request('GET', $url, array(), array(), array(
        'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    
    $this->isResponseSuccess();
    
    $fav = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array(
        'user'    => $paul->getId()
      ))
    ;
    
    $this->assertEquals(1, count($fav));
    $this->assertEquals('Tribe', $fav[0]->getTag()->getName());
    
    // Si on rajoute le même tag il ne doit pas y avoir de changement
    $tribe = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->findOneByName('Tribe')
    ;
    
    $url = $this->generateUrl('ajax_tag_add_to_favorites', array(
      'tag_id' => $tribe->getId(),
      'token'  => $paul->getPersonalHash($tribe->getId())
    ));
    
    $crawler = $this->client->request('GET', $url, array(), array(), array(
        'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    
    $this->isResponseSuccess();
    
    $fav = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array(
        'user'    => $paul->getId()
      ))
    ;
    
    $this->assertEquals(1, count($fav));
    $this->assertEquals('Tribe', $fav[0]->getTag()->getName());
    
    // Si on ajoute un nouveau tag
    $hardtek = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->findOneByName('Hardtek')
    ;
    
    $url = $this->generateUrl('ajax_tag_add_to_favorites', array(
      'tag_id' => $hardtek->getId(),
      'token'  => $paul->getPersonalHash($hardtek->getId())
    ));
    
    $crawler = $this->client->request('GET', $url, array(), array(), array(
        'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    
    $this->isResponseSuccess();
    
    $fav = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array(
        'user'    => $paul->getId()
      ))
    ;
    
    $this->assertEquals(2, count($fav));
    $this->assertEquals('Tribe', $fav[0]->getTag()->getName());
    $this->assertEquals('Hardtek', $fav[1]->getTag()->getName());
    
  }
  
  public function testUpdateAddress()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $paul = $this->getUser();
    
    // D'après les fixtures, pas d'adresse pour paul
    $this->assertEquals($paul->getTown(), null);
    $this->assertEquals($paul->getCountry(), null);
    
    $crawler = $this->client->request(
      'POST', 
      $this->generateUrl('update_address', array('token' => $this->getUser()->getPersonalHash())), 
      array(
          'town' => '',
          'country' => ''
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    $response = json_decode($this->client->getResponse()->getContent(), true);
    
    $this->assertEquals($response['status'], 'error');
    $this->assertEquals(count($response['errors']), '2');
    $this->assertEquals($response['errors'], array(
      $this->getContainer()->get('translator')->trans('my_account.address.form.errors.notown', array(), 'userui'),
      $this->getContainer()->get('translator')->trans('my_account.address.form.errors.nocountry', array(), 'userui')
    ));
    $paul = $this->getUser();
    $this->assertEquals($paul->getTown(), null);
    $this->assertEquals($paul->getCountry(), null);
    
    /////
    
    $crawler = $this->client->request(
      'POST', 
      $this->generateUrl('update_address', array('token' => $this->getUser()->getPersonalHash())), 
      array(
          'town' => 'peyruis',
          'country' => ''
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    $response = json_decode($this->client->getResponse()->getContent(), true);
    
    $this->assertEquals($response['status'], 'error');
    $this->assertEquals(count($response['errors']), '1');
    $this->assertEquals($response['errors'], array(
      $this->getContainer()->get('translator')->trans('my_account.address.form.errors.nocountry', array(), 'userui')
    ));
    $paul = $this->getUser();
    $this->assertEquals($paul->getTown(), null);
    $this->assertEquals($paul->getCountry(), null);
    
    /////
    
    $crawler = $this->client->request(
      'POST', 
      $this->generateUrl('update_address', array('token' => $this->getUser()->getPersonalHash())), 
      array(
          'town' => 'peyruis',
          'country' => 'france'
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    
    $this->isResponseSuccess();
    $response = json_decode($this->client->getResponse()->getContent(), true);
    
    $paul = $this->getUser();
    $this->assertEquals($response['status'], 'success');
    $this->assertEquals($paul->getTown(), 'peyruis');
    $this->assertEquals($paul->getCountry(), 'france');
    
  }
  
  public function testPreferencesUpdate()
  {
    
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    
    $paul = $this->getUser();
    $this->crawler = $this->client->request('GET', $this->generateUrl('my_account'));
    $this->isResponseSuccess();
    
    $this->assertEquals(true, $paul->mail_newsletter);
    $this->assertEquals(true, $paul->mail_partner);
    
    $form = $this->selectForm('div#myaccount_preferences form input[type="submit"]');
    $form['form[mail_newsletter]']->untick();
    $form['form[mail_partner]']->untick();
    $this->submit($form);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    $paul = $this->getUser('paul');
    $this->assertEquals(false, $paul->mail_newsletter);
    $this->assertEquals(false, $paul->mail_partner);
  }
  
  public function testPrivacyUpdate()
  {
    $this->client = self::createClient();
    
    $bux = $this->findUserByUsername('bux');
    
    $this->checkFavoritesViewable($bux, true);
    $this->connectUser('bux', 'toor');
    $this->updateFavoritePrivacy(false);
    $this->disconnectUser();
    $this->checkFavoritesViewable($bux, false);
    $this->connectUser('paul', 'toor');
    $this->checkFavoritesViewable($bux, false);
    $this->disconnectUser();
    $this->connectUser('bux', 'toor');
    $this->checkFavoritesViewable($bux, true);
  }
  
  protected function checkFavoritesViewable($user, $public)
  {
    $this->goToPage($this->generateUrl('favorite_user_list', array('slug' => $user->getSlug(), '_locale' => 'fr')));
    $this->isResponseSuccess();
    if ($public)
      $this->notExist('p.favorites_no_publics');
    if (!$public)
      $this->exist('p.favorites_no_publics');
  }
  
  protected function updateFavoritePrivacy($public)
  {
    $this->goToPage($this->generateUrl('my_account'));
    $form = $this->selectForm('form.privacy input[type="submit"]');
    if ($public)
      $form['user_privacy[favorites_publics]']->tick();
    if (!$public)
      $form['user_privacy[favorites_publics]']->untick();
    $this->submit($form);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
  }
  
  public function testDeleteUser()
  {
    $this->client = self::createClient();
    
    $joelle = $this->findUserByUsername('joelle');
    $this->assertEquals('joelle', $joelle->getUsername());
    $this->assertEquals('joelle@root', $joelle->getEmail());
    $this->connectUser('joelle', 'toor');
    $this->deleteUser('toor');
    $joelle = $this->findOneBy('User', array('id' => $joelle->getId()));
    $this->assertTrue('joelle' != $joelle->getUsername());
    $this->assertTrue('joelle@mail.com' != $joelle->getEmail());
    $this->connectUser('joelle', 'toor', null, false);
  }
  
  protected function deleteUser($password)
  {
    $this->goToPage($this->generateUrl('my_account'));
    $this->exist('form.delete');
    $form = $this->selectForm('form.delete input[type="submit"]');
    $form['delete_user_form[current_password]'] = $password;
    $this->submit($form);
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseRedirection();
    $this->followRedirection();
    //$this->outputDebug();
    $this->isResponseSuccess();
  }
  
}