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
  public function testImpactElementVote()
  {
    $this->client = self::createClient();
    $this->connectUser('paul', 'toor');
    $paul = $this->getUser();
    
    //
    
  }
  
}