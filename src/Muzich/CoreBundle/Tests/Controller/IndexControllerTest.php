<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;

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

    $user = $this->getUser();
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
    
    $this->exist('div.register');
    $this->exist('form[action="'.($url = $this->generateUrl('register')).'"]');
    $this->exist('form[action="'.$url.'"] input[id="fos_user_registration_form_username"]');
    $this->exist('form[action="'.$url.'"] input[id="fos_user_registration_form_email"]');
    $this->exist('form[action="'.$url.'"] input[id="fos_user_registration_form_plainPassword_first"]');
    $this->exist('form[action="'.$url.'"] input[id="fos_user_registration_form_plainPassword_second"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    $this->validate_registrate_user_form(
      $this->selectForm('form[action="'.$url.'"] input[type="submit"]'), 
      'raoula', 
      'raoul.45gf64z@gmail.com', 
      'toor',
      'toor'
    );
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();

    $user = $this->getUser();
    $this->assertEquals('raoula', $user->getUsername());
    
    // L'utilisateur est enregistré, il doit donc être en base
    $db_user = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('raoula')
    ;
    
    $this->assertTrue(!is_null($db_user));
    if ($db_user)
    {
      $this->assertEquals('raoula', $db_user->getUsername());
    }
  }
  
  public function testRegistrationFailure()
  {
    
    /**
     * Inscription d'un utilisateur
     */
    $this->client = self::createClient();

    // Mots de passe différents
    $this->procedure_registration_failure(
      'raoulb', 
      'raoulb.def4v65sds@gmail.com', 
      'toor', 
      'toorr'
    );

//    // Pseudo trop court
//    $this->procedure_registration_failure(
//      'ra', 
//      'raoulb.def4v65sds@gmail.com', 
//      'toor', 
//      'toor'
//    );
//    
//    // Pseudo trop long
//    $this->procedure_registration_failure(
//      'raouuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu'
//         .'uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuu'
//         .'uuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuuul', 
//      'raoulb.def4v65sds@gmail.com', 
//      'toor', 
//      'toor'
//    );

    // Email invalide
    $this->procedure_registration_failure(
      'raoulc', 
      'raoulb.def4v65sds@gmail', 
      'toor', 
      'toor'
    );
  }
  
}