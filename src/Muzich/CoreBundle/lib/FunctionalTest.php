<?php

namespace Muzich\CoreBundle\lib;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DomCrawler\Crawler;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;
use Muzich\CoreBundle\Entity\Element;

class FunctionalTest extends WebTestCase
{
  /**
   *
   * @var Client 
   */
  public $client;
  
  /**
   *
   * @var Crawler 
   */
  public $crawler;
  
  public function getClient()
  {
    return $this->client;
  }
  
  public function getCrawler()
  {
    return $this->crawler;
  }
  
  public function outputDebug($content = null)
  {
    $time = time();
    //unlink('.debug/out'.$time.'.html');
    if(@mkdir("./debug",0777,true))
    {
      
    }
    $monfichier = fopen('.debug/out'.$time.'.html', 'a+');
    if (!$content)
    {
      fwrite($monfichier, $this->client->getResponse()->getContent());
    }
    else
    {
      fwrite($monfichier, $content);
    }
  }
  
  /**
   * Retourne l'objet User
   * 
   * @return \Muzich\CoreBundle\Entity\User 
   */
  public function getUser($username = null)
  {
    if (!$username)
    {
      $token = $this->client->getContainer()->get('security.context')->getToken();
      if ($token)
      {
        return $token->getUser();
      }
      
      return 'anon.';
    }
    else
    {
      return $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
        ->findOneByUsername($username)
      ;
    }
  }
  
  /**
   * @return \Muzich\CoreBundle\Entity\Group
   */
  protected function getGroup($slug)
  {
    return $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')
      ->findOneBySlug($slug)->getSingleResult()
    ;
  }
  
  protected function connectUser($login, $password = 'toor', $client = null, $success = true)
  {
    if (!$client)
    {
      $client = $this->client;
    }
    
    $this->crawler = $client->request('GET', $this->generateUrl('index'));
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
    if ($success)
    {
      if ('anon.' != $user)
      {
        if (strpos($login, '@') === false)
        {
          $this->assertEquals($login, $user->getUsername());
        }
        else
        {
          $this->assertEquals($login, $user->getEmail());
        }
      }
      else
      {
        $this->assertTrue(false);
      }
    }
    else
    {
      $this->assertEquals('anon.', $user);
    }
  }
  
  protected function disconnectUser()
  {
    $this->crawler = $this->client->request('GET', $this->generateUrl('fos_user_security_logout'));
  }
  
  protected function validate_registrate_user_form($email)
  {
    $extract = $this->crawler->filter('input[name="muzich_user_registration[_token]"]')
      ->extract(array('value'));
    $csrf = $extract[0];
    $this->crawler = $this->client->request(
      'POST', 
      $this->generateUrl('register'),
      array(
        'muzich_user_registration' => array(
          'email' => $email,
          '_token' => $csrf
        )
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
  }
  
  protected function procedure_registration_success($email)
  {
    $this->crawler = $this->client->request('GET', $this->generateUrl('index'));
    $this->isResponseSuccess();
    $this->assertEquals('anon.', $this->getUser());
    
    // Les mots de passes sont différents
    $this->validate_registrate_user_form(
      $email
    );
    
    if ('anon.' != ($user = $this->getUser()))
    {
      $this->assertEquals($email, $user->getEmail());
      $db_user = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
        ->findOneByEmail($email)
      ;

      $this->assertTrue(!is_null($db_user));
    }
    else
    {
      $this->assertTrue(false);
    }
  }
  
  protected function procedure_registration_failure($email)
  {
    $this->crawler = $this->client->request('GET', $this->generateUrl('index'));
    $this->isResponseSuccess();
    $this->assertEquals('anon.', $this->getUser());
    
    // Les mots de passes sont différents
    $this->validate_registrate_user_form(
      $email
    );
    
    $this->isResponseSuccess();

    if ('anon.' === ($user = $this->getUser()))
    {
      // Nous ne sommes pas identifiés
      $this->assertEquals('anon.', $user);

      // L'utilisateur n'est pas enregistré, il ne doit donc pas être en base
      $db_user = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
        ->findOneByEmail($email)
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
   * @param string $group_slug 
   * @param boolean $need_tags 
   */
  protected function procedure_add_element($name, $url, $tags, $group_slug = null, $need_tags = false)
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
    
    if (count($tags))
    {
      $form['element_add[tags]'] = json_encode($tags);
    }
    
    if ($need_tags)
    {
      $form['element_add[need_tags]'] = true;
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
    return $this->getDoctrine()->getManager()->getRepository('MuzichCoreBundle:User')
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
  public function generateUrl($route, $parameters = array(), $absolute = false)
  {
    
    /**
     * Petit hack pour que les locales ne manque pas 
     */
    
    if ($route == 'index')
    {
      if (!array_key_exists('_locale', $parameters))
      {
        $parameters['_locale'] = 'fr';
      }
    }
    
    if ($route == 'home')
    {
      if (!array_key_exists('_locale', $parameters))
      {
        $parameters['_locale'] = 'fr';
      }
    }
    
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
   * @return \Doctrine\Bundle\DoctrineBundle\Registry
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
  public function isResponseSuccess()
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
  
  
  
  protected function addElementAjax($name, $url, $tags = '', $group_slug = null)
  {
    $this->crawler = $this->client->request('GET', $this->generateUrl('home'));
    
    $extract = $this->crawler->filter('input[name="element_add[_token]"]')
      ->extract(array('value'));
    $csrf = $extract[0];
    
    $url_ajax = $this->generateUrl('element_add');
    if ($group_slug)
    {
      $url_ajax = $this->generateUrl('element_add', array('group_slug' => $group_slug));
    }
    
    $this->crawler = $this->client->request(
      'POST', 
      $url_ajax, 
      array(
          'element_add' => array(
              '_token' => $csrf,
              'name'   => $name,
              'url'    => $url,
              'tags'   => $tags
          )
        
      ), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName($name)
    ;
    $this->assertTrue(!is_null($element));
  }
  
  /**
   * Runs a command and returns it output
   * 
   * @author Alexandre Salomé <alexandre.salome@gmail.com>
   */
  public function runCommand(Client $client, $command)
  {
      $application = new Application($client->getKernel());
      $application->setAutoExit(false);

      $fp = tmpfile();
      $input = new StringInput($command);
      $output = new StreamOutput($fp);

      $application->run($input, $output);

      fseek($fp, 0);
      $output = '';
      while (!feof($fp)) {
          $output = fread($fp, 4096);
      }
      fclose($fp);

      return $output;
  }
  
  /**
   *
   * @return \Doctrine\ORM\EntityManager 
   */
  protected function getEntityManager()
  {
    return $this->getDoctrine()->getManager();
  }
  
  /**
   * Raccourcis de findOneBy
   * 
   * @param string $entityName
   * @param array $params
   * @return object 
   */
  public function findOneBy($entityName, $params)
  {
    if (!is_array($params))
    {
      $params = array('name' => $params);
    }
    return $this->getEntityManager()->getRepository('MuzichCoreBundle:'.$entityName)
      ->findOneBy($params);
  }
  
  public function goToPage($url)
  {
    $this->crawler = $this->client->request('GET', $url);
  }
  
  public function jsonResponseIsSuccess($json_response)
  {
    $response = json_decode($json_response, true);
    $this->assertFalse(is_null($response));
    $this->assertTrue(array_key_exists('status', $response));
    $this->assertEquals('success', $response['status']);
  }
  
  public function jsonResponseIsError($json_response)
  {
    $response = json_decode($json_response, true);
    $this->assertTrue(array_key_exists('status', $response));
    $this->assertEquals('error', $response['status']);
  }
  
  public function setCrawlerWithJsonResponseData($json_response)
  {
    $response = json_decode($json_response, true);
    $this->crawler = new Crawler($response['data']);
  }
  
  public function getToken($intention = 'unknown')
  {
    return $this->getContainer()->get('form.csrf_provider')->generateCsrfToken($intention);
  }
  
  public function getLastTagsProposition(Element $element)
  {
    $propositions = $this->getDoctrine()->getManager()
      ->createQuery('SELECT p FROM MuzichCoreBundle:ElementTagsProposition p'
        .' WHERE p.element = :eid ORDER BY p.id DESC')
      ->setMaxResults(1)
      ->setParameters(array(
        'eid' => $element->getId()
      ))
      ->getResult()
    ;
    
    $this->assertEquals(1, count($propositions));
    return $propositions[0];
  }
  
  protected function stringResponseIsSuccess($response_string)
  {
    $response_array = json_decode($response_string, true);
    $this->assertEquals('success', $response_array['status']);
  }
  
}