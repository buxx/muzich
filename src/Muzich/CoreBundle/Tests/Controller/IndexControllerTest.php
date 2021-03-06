<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;
use Muzich\CoreBundle\Entity\RegistrationToken;

class IndexControllerTest extends FunctionalTest
{
  public function testIdentificationSuccess()
  {
    /**
     * Test de l'identification de paul
     */
    $this->client = self::createClient();

    $this->crawler = $this->client->request('GET', $this->generateUrl('index'));
    $this->isResponseSuccess();

    $this->assertEquals('anon.', $this->getUser());

    $this->exist('div.login');
    $this->exist('form[action="'.($url = $this->generateUrl('fos_user_security_check')).'"]');
    $this->exist('form[action="'.$url.'"] input[id="username"]');
    $this->exist('form[action="'.$url.'"] input[id="password"]');
    $this->exist('form[action="'.$url.'"] input[id="remember_me"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');

    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['_username'] = 'paul';
    $form['_password'] = 'toor';
    $form['_remember_me'] = true;
    $this->submit($form);

    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();

    $user = $this->getUser();
    $this->assertEquals('paul', $user->getUsername());
  }
  
  public function testIdentificationFail()
  {
    /**
     * Test de l'identification de paul, avec erreur
     */
    $this->client = self::createClient();

    $this->crawler = $this->client->request('GET', $this->generateUrl('index'));
    $this->isResponseSuccess();

    $this->assertEquals('anon.', $this->getUser());

    $form = $this->selectForm('form[action="'.$this->generateUrl('fos_user_security_check').'"] input[type="submit"]');
    $form['_username'] = 'paul';
    $form['_password'] = 'toorr';
    $form['_remember_me'] = true;
    $this->submit($form);

    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();

    $this->assertEquals('anon.', $this->getUser());
  }
  
  public function testRegistrationSuccess()
  {
    /**
     * Inscription d'un utilisateur
     */
    $this->client = self::createClient();

    $this->crawler = $this->client->request('GET', $this->generateUrl('index'));
    $this->isResponseSuccess();

    $this->assertEquals('anon.', $this->getUser());
    
    // On a besoin d'un token pour le moment
    $token = new RegistrationToken();
    $token->setToken('4vcsdv54svqcc3q1v54sdv6qs');
    $em = $this->getDoctrine()->getManager();
    $em->persist($token);
    $em->flush();
    
    $this->exist('div.register');
    $this->exist('form[action="'.($url = $this->generateUrl('register')).'"]');
    $this->exist('form[action="'.$url.'"] input[id="muzich_user_registration_email"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    $this->procedure_registration_success(
      'raoula.def4v65sds@gmail.com'
    );
  }
  
  public function testRegistrationFailure()
  {
    
    /**
     * Inscription d'un utilisateur
     */
    $this->client = self::createClient();
  
    // On a besoin d'un token pour le moment
    $token = new RegistrationToken();
    $token_name = '45gf645jgf6xqz4dc'.time();
    $token->setToken($token_name);
    $em = $this->getDoctrine()->getManager();
    $em->persist($token);
    $em->flush();
    
    // Pseudo trop long
    $this->procedure_registration_failure(
      'raoulb.def4v65sdsgmail.com'
    );
  }
  
  /**
   * Test du changement de mot de passe
   */
  public function testPasswordLost()
  {
    $this->client = self::createClient();
    $this->crawler = $this->client->request('GET', $this->generateUrl('index'));
    
    $bux = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneByUsername('bux');
    
    // On peux voir le lien vers al page de demande de mot de passe
    $this->exist('a[href="'.($url = $this->generateUrl('fos_user_resetting_request')).'"]');
    $link = $this->selectLink('a[href="'.$url.'"]');
    $this->clickOnLink($link);
    
    $this->isResponseSuccess();
    
    // On trouve le formulaire
    $this->exist('form[action="'.($url = $this->generateUrl('fos_user_resetting_send_email')).'"]');
    $this->exist('form[action="'.$url.'"] input[id="username"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    // On selectionne le form
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['username'] = 'bux';
    $this->submit($form);
    
    $mc = $this->getMailerMessageDataCollector();
    $this->assertEquals(1, $mc->getMessageCount());
    
    $mails = $mc->getMessages();
    $mail = $mails[0];
       
    // $mail = new Swift_Message();
    
    $bux = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneByUsername('bux');
    $this->assertTrue(!is_null(strpos($mail->getBody(), ($url = $this->generateUrl(
      'fos_user_resetting_reset', 
      array('token' => $bux->getConfirmationToken()), 
      true
    )))));
    
    $keys = array_keys($mail->getTo());
    $this->assertEquals($bux->getEmail(), $keys[0]);
        
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // On se rend sur le lien envoyé dans le mail
    $this->crawler = $this->client->request('GET', $url);
    
    $this->exist('form[action="'.($url = $this->generateUrl(
      'fos_user_resetting_reset', 
      array('token' => $bux->getConfirmationToken())
    )).'"]');
    
    $this->exist('form[action="'.$url.'"] input[id="fos_user_resetting_form_new_first"]');
    $this->exist('form[action="'.$url.'"] input[id="fos_user_resetting_form_new_second"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['fos_user_resetting_form[new][first]'] = 'trololo';
    $form['fos_user_resetting_form[new][second]'] = 'trololo';
    $this->submit($form);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // A ce stade on a été connecté
    $this->assertEquals('bux', $this->getUser()->getUsername());
    
    // On se déconnecte pour aller tester ce nouveau mot de passe
    $this->disconnectUser();
    $this->connectUser('bux', 'trololo');
  }
  
}