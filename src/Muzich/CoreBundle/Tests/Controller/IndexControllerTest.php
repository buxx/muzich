<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;

class IndexControllerTest extends FunctionalTest
{
  public function testIdentification()
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
  
  public function testRegistration()
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
    
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['fos_user_registration_form[username]'] = 'raoul';
    $form['fos_user_registration_form[email]'] = 'raoul.45gf64z@gmail.com';
    $form['fos_user_registration_form[plainPassword][first]'] = 'toor';
    $form['fos_user_registration_form[plainPassword][second]'] = 'toor';
    $this->submit($form);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();

    $user = $this->getUser();
    $this->assertEquals('raoul', $user->getUsername());
    
    /*
     * TODO: Vérifier les données en base
     */
  }
  
  /*
   * TODO: Vérifier le comportement des formulaires lorsque l'on rentre de mauvaises données
   */
  
}