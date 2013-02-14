<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;

//use Symfony\Bundle\FrameworkBundle\Console\Application;
//use Muzich\CoreBundle\Command\RecalculateReputationCommand;
//use Symfony\Component\Console\Tester\CommandTester;
////use \AppKernel;
//use Symfony\Component\HttpFoundation\Request;

class ReputationTest extends FunctionalTest
{
  
//  public function testCommandLineCalculateReputation()
//  {
//    require_once __DIR__.'/../../../../../app/bootstrap.php.cache';
//    require_once __DIR__.'/../../../../../app/AppKernel.php';
//    
//    $kernel = new \AppKernel('test', true);
//    $kernel->loadClassCache();
//    $kernel->handle(Request::createFromGlobals())->send();
//    
//    $application = new Application($kernel);
//    $application->add(new RecalculateReputationCommand());
//
//    $command = $application->find('users:reputation:recalculate');
//    $commandTester = new CommandTester($command);
//    
//    $bux = $this->getUser('bux');
//    $bux->setReputation(0);
//    $this->getDoctrine()->getEntityManager()->persist($bux);
//    $this->getDoctrine()->getEntityManager()->flush();
//    
//    $bux = $this->getUser('bux');
//    $this->assertEquals($bux->getReputation(), 0);
//    
//    //$commandTester->execute(array('command' => $command->getName()));
//  }
  
  /**
   * Test de l'impact sur la reputation lorsque il y a vote sur element
   */
  public function testElementDelete()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    $paul = $this->getUser();
    
    // paul vote sur l'élément de bux
    // // Comme ça on a un point de vote (fixtures = 0 points)
    // // et une mise ne favoris
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneByName('Heretik System Popof - Resistance')
    ;
    
    $crawler = $this->client->request(
      'GET', 
      $this->generateUrl('ajax_element_add_vote_good', array(
        'element_id' => $element->getId(),
        'token' => $paul->getPersonalHash($element->getId())
      )), 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    // bux va supprimer un de ses éléments
    $this->disconnectUser();
    $this->connectUser('bux', 'toor');
    $bux = $this->getUser();
    
    // D'aprés les fixtures plus le vote de paul: 23
    $this->assertEquals(23, $bux->getReputation());
    
    // On effectue la demande ajax d'edition
    $crawler = $this->client->request(
      'GET', 
      $this->generateUrl('element_remove', array(
        'element_id' => $element->getId(),
        'token'      => $bux->getPersonalHash($element->getId())
      )), 
      array(), 
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    $this->isResponseSuccess();
    
    $response = json_decode($this->client->getResponse()->getContent(), true);
    $this->assertEquals($response['status'], 'success');
    
    $coef_element_fav = $this->getContainer()->getParameter('reputation_element_favorite_value');
    $coef_element_point = $this->getContainer()->getParameter('reputation_element_point_value');
    
    $bux = $this->getUser('bux');
    $this->assertEquals(23 - ($coef_element_fav * 1) - ($coef_element_point * 1), $bux->getReputation()); 
  }
  
}