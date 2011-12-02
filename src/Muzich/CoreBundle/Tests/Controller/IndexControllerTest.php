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
}