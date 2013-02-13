<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;
use Muzich\CoreBundle\Entity\RegistrationToken;

class UserControllerTest extends FunctionalTest
{
  
  public function testRegistrationToken()
  {
    $this->client = self::createClient();
    $token = new RegistrationToken();
    $token_name = 'token_test_3_max_'.time();
    $token->setToken($token_name);
    $token->setCountMax(3);
    $em = $this->getDoctrine()->getEntityManager();
    $em->persist($token);
    $em->flush();
    
    $this->procedure_registration_success(
      'user1', 
      'user1@mail.com', 
      'toor', 
      'toor',
      $token_name
    );
    
    $this->disconnectUser();
    
    $this->procedure_registration_success(
      'user2', 
      'user2@mail.com', 
      'toor', 
      'toor',
      $token_name
    );
    
    $this->disconnectUser();
    
    $this->procedure_registration_success(
      'user3', 
      'user3@mail.com', 
      'toor', 
      'toor',
      $token_name
    );
    
    $this->disconnectUser();
    
    $this->procedure_registration_failure(
      'user4', 
      'user4@mail.com', 
      'toor', 
      'toor',
      $token_name
    );
        
    $this->procedure_registration_failure(
      'user5', 
      'user5@mail.com', 
      'toor', 
      'toor',
      $token_name
    );
        
    $this->procedure_registration_failure(
      'user6', 
      'user6@mail.com', 
      'toor', 
      'toor',
      ''
    );
  }
  
}