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
  
  protected function validate_registrate_user_form($form, $username, $email, $pass1, $pass2)
  {
    $form['fos_user_registration_form[username]'] = $username;
    $form['fos_user_registration_form[email]'] = $email;
    $form['fos_user_registration_form[plainPassword][first]'] = $pass1;
    // Un des mots de passe est incorrect
    $form['fos_user_registration_form[plainPassword][second]'] = $pass2;
    $this->submit($form);
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
   * Procédure d'ajout d'un élément a partir de la page home
   * 
   * @param string $name
   * @param string $url
   * @param array $tags
   * @param int $group_id 
   */
  protected function procedure_add_element($name, $url, $tags, $group_id = '')
  {
    $this->crawler = $this->client->request('GET', $this->generateUrl('home'));
    $this->isResponseSuccess();
    
    $form = $this->selectForm('form[action="'.$this->generateUrl('element_add').'"] input[type="submit"]');
    $form['element_add[name]'] = $name;
    $form['element_add[url]'] = $url;
    foreach ($tags as $tag_id)
    {
      $form['element_add[tags]['.$tag_id.']'] = $tag_id;
    }
    $form['element_add[group]'] = $group_id;
    $this->submit($form);
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