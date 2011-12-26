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
    
    $this->outputDebug();
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
    
    $this->getDoctrine()->getEntityManager()->flush();
    
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
  
}