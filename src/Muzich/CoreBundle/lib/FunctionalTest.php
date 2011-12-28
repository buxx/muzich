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
  
  protected function outputDebug()
  {
    unlink('/home/bux/.debug/out.html');
    $monfichier = fopen('/home/bux/.debug/out.html', 'a+');
    fwrite($monfichier, $this->client->getResponse()->getContent());
  }
  
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
    if ('anon.' != $user)
    {
      $this->assertEquals($login, $user->getUsername());
    }
    else
    {
      $this->assertTrue(false);
    }
  }
  
  protected function disconnectUser()
  {
    $this->crawler = $this->client->request('GET', $this->generateUrl('fos_user_security_logout'));
  }
  
  protected function validate_registrate_user_form($form, $username, $email, $pass1, $pass2)
  {
    $form['fos_user_registration_form[username]'] = $username;
    $form['fos_user_registration_form[email]'] = $email;
    $form['fos_user_registration_form[plainPassword][first]'] = $pass1;
    // Un des mots de passe est incorrect
    $form['fos_user_registration_form[plainPassword][second]'] = $pass2;
    $this->submit($form);
  }
  
  protected function procedure_registration_success($username, $email, $pass1, $pass2)
  {
    $this->crawler = $this->client->request('GET', $this->generateUrl('index'));
    $this->isResponseSuccess();
    $this->assertEquals('anon.', $this->getUser());
    
    $url = $this->generateUrl('register');
    // Les mots de passes sont différents
    $this->validate_registrate_user_form(
      $this->selectForm('form[action="'.$url.'"] input[type="submit"]'), 
      $username, 
      $email, 
      $pass1,
      $pass2
    );
    
    
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();

    if ('anon.' != ($user = $this->getUser()))
    {
      // Nous ne sommes pas identifiés
      $this->assertEquals($username, $user->getUsername());

      // L'utilisateur n'est pas enregistré, il ne doit donc pas être en base
      $db_user = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
        ->findOneByUsername($username)
      ;

      $this->assertTrue(!is_null($db_user));
    }
    else
    {
      $this->assertTrue(false);
    }
  }
  
  protected function procedure_registration_failure($username, $email, $pass1, $pass2)
  {
    $this->crawler = $this->client->request('GET', $this->generateUrl('index'));
    $this->isResponseSuccess();
    $this->assertEquals('anon.', $this->getUser());
    
    $url = $this->generateUrl('register');
    // Les mots de passes sont différents
    $this->validate_registrate_user_form(
      $this->selectForm('form[action="'.$url.'"] input[type="submit"]'), 
      $username, 
      $email, 
      $pass1,
      $pass2
    );
    
    $this->isResponseSuccess();

    if ('anon.' === ($user = $this->getUser()))
    {
      // Nous ne sommes pas identifiés
      $this->assertEquals('anon.', $user);

      // L'utilisateur n'est pas enregistré, il ne doit donc pas être en base
      $db_user = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
        ->findOneByUsername($username)
      ;

      $this->assertTrue(is_null($db_user));
    }
    else
    {
      $this->assertTrue(false);
    }
  }
  
  /**
   * Procédure d'ajout d'un élément
   * 
   * @param string $name
   * @param string $url
   * @param array $tags
   * @param int $group_id 
   */
  protected function procedure_add_element($name, $url, $tags, $group_slug = null)
  {
    if (!$group_slug)
    {
      $this->crawler = $this->client->request('GET', $this->generateUrl('home'));
      $form_url = $this->generateUrl('element_add');
    }
    else
    {
      $this->crawler = $this->client->request('GET', $this->generateUrl('show_group', array('slug' => $group_slug)));
      $form_url = $this->generateUrl('element_add', array('group_slug' => $group_slug));
    }
    $this->isResponseSuccess();
    
    $form = $this->selectForm('form[action="'.$form_url.'"] input[type="submit"]');
    $form['element_add[name]'] = $name;
    $form['element_add[url]'] = $url;
    foreach ($tags as $tag_id)
    {
      $form['element_add[tags]['.$tag_id.']'] = $tag_id;
    }
    
    $this->submit($form);
  }
  
  /**
   * Retourne un utilisateur en allant le chercher en base.
   * 
   * @param string $username
   * @return \Muzich\CoreBundle\Entity\User 
   */
  protected function findUserByUsername($username)
  {
    return $this->getDoctrine()->getEntityManager()->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername($username)
    ;
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
  
  protected function getCollector($name)
  {
    return$this->client->getProfile()->getCollector($name);
  }
  
  /**
   * Retourne le MessageDataCollector en cours
   * 
   * @return Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector
   */
  protected function getMailerMessageDataCollector()
  {
    return $this->getCollector('swiftmailer');
  }
  
  protected function clickOnLink($link)
  {
    $this->crawler = $this->client->click($link);
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
   * Test l'inexistance d'un element
   * 
   * @param string $filter 
   */
  protected function notExist($filter)
  {
    $this->assertFalse($this->crawler->filter($filter)->count() > 0);
  }
  
  /**
   * Retourne un objet lien
   * 
   * @param string $filter
   * @return \Symfony\Component\DomCrawler\Link
   */
  protected function selectLink($filter)
  {
    return $this->crawler->filter($filter)->link();
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
   * Retourne un formulaire, en filtrant le BOUTON SUBMIT !!
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
  protected function submit($form, $params = array())
  {
    $this->crawler = $this->client->submit($form, $params);
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
    $this->assertTrue($this->client->getResponse()->isRedirection());
  }
  
  /**
   * Contrôle que le CodeStatus de la Response correspond bien a celle d'un Ok
   */
  protected function isResponseSuccess()
  {
    $this->assertTrue($this->client->getResponse()->isSuccessful());
  }
  
  /**
   * Contrôle que le CodeStatus de la Response correspond bien a celle d'un Ok
   */
  protected function isResponseNotFound()
  {
    $this->assertTrue($this->client->getResponse()->isNotFound());
  }
}