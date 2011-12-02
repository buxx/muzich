<?php

namespace Muzich\CoreBundle\lib;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;

class FunctionalTest extends WebTestCase
{
  /**
   *
   * @var Client 
   */
  protected $client;
  
  /**
   *
   * @var Crawler 
   */
  protected $crawler;
  
  /**
   * Retourne l'objet User
   * 
   * @return \Muzich\CoreBundle\Entity\User 
   */
  protected function getUser()
  {
    return $this->client->getContainer()->get('security.context')->getToken()->getUser();
  }
  
  protected function connectUser($login, $password)
  {
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
    $form['_username'] = $login;
    $form['_password'] = $password;
    $form['_remember_me'] = true;
    $this->submit($form);

    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();

    $user = $this->getUser();
    $this->assertEquals($login, $user->getUsername());
  }
  
  /**
   * Generates a URL from the given parameters.
   *
   * @param string $route
   * @param array $parameters
   * @param boolean $absolute 
   * 
   * @return string (url generated)
   */
  protected function generateUrl($route, $parameters = array(), $absolute = false)
  {
    return $this->client->getContainer()->get('router')->generate($route, $parameters, $absolute);
  }
  
  protected function getContainer()
  {
    return $this->client->getContainer();
  }
  
  protected function getSession()
  {
    return $this->getContainer()->get('session');
  }
  
  /**
   *
   * @return \Symfony\Bundle\DoctrineBundle\Registry
   */
  protected function getDoctrine()
  {
    return $this->client->getContainer()->get('doctrine');
  }
  
  /**
   * Test l'existance d'un element
   * 
   * @param string $filter 
   */
  protected function exist($filter)
  {
    $this->assertTrue($this->crawler->filter($filter)->count() > 0);
  }
  
  /**
   * Retourne un objet lien
   * 
   * @param string $filter
   * @return \Symfony\Component\DomCrawler\Link
   */
  protected function selectLink($filter)
  {
    return $this->crawler->filter($filter)->eq(1)->link();
  }
  
//  /**
//   * Clique sur un link
//   *  
//   * @param type $link 
//   */
//  protected function click($link)
//  {
//    $this->crawler = $this->client->click($link);
//  }
  
  /**
   * Retourne un formulaire
   * 
   * @param string $filter
   * @return \Symfony\Component\DomCrawler\Form 
   */
  protected function selectForm($filter)
  {
    return $this->crawler->filter($filter)->form();
  }
  
  /**
   * Soumet un formulaire
   * 
   * @param type $form 
   */
  protected function submit($form)
  {
    $this->crawler = $this->client->submit($form);
  }
  
  /**
   * Ordonne au client de suivre la redirection
   */
  protected function followRedirection()
  {
    $this->crawler = $this->client->followRedirect();
  }
  
  /**
   * Contrôle le Codestatus de la réponse
   * 
   * @param int $code 
   */
  protected function isStatusCode($code)
  {
    $this->assertEquals($code, $this->client->getResponse()->getStatusCode());
  }
  
  /**
   * Contrôle que le CodeStatus de la Response correspond bien a celle d'une
   *  redirection
   */
  protected function isResponseRedirection()
  {
    $this->client->getResponse()->isRedirection();
  }
  
  /**
   * Contrôle que le CodeStatus de la Response correspond bien a celle d'un Ok
   */
  protected function isResponseSuccess()
  {
    $this->client->getResponse()->isSuccessful();
  }
}