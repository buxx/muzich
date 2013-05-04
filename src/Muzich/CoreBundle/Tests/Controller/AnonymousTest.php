<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;
use Muzich\CoreBundle\Tests\lib\Security\Context as SecurityContextTest;
use Muzich\CoreBundle\Security\Context as SecurityContext;

class NoPassTest extends FunctionalTest
{
  
  protected $security_context_test;
  
  protected function init()
  {
    $this->client = self::createClient();
    $this->security_context_test = new SecurityContextTest($this->client, $this);
  }
  
  public function testLimitedActionsForAnonymous()
  {
    $this->init();
    $this->checkUserIsAnonymous();
    $this->checkUserCantMakeProhibedActionsForAnonymous();
    $this->registerUser('dijarr@mail.com');
    $this->checkUserIsNotProhibedForAnonymousActions();
  }
  
  protected function checkUserIsAnonymous()
  {
    $this->assertEquals('anon.', $this->getUser());
  }
  
  protected function checkUserCantMakeProhibedActionsForAnonymous()
  {
    $this->checkUserProhibedActionStatus(true);
  }
  
  protected function checkUserProhibedActionStatus($match)
  {
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_ELEMENT_ADD, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      $match
    );
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_ELEMENT_NOTE, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      $match
    );
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_COMMENT_ALERT, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      $match
    );
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_ELEMENT_ALERT, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      $match
    );
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_TAG_ADD, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      $match
    );
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_ELEMENT_TAGS_PROPOSITION, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      $match
    );
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_GROUP_ADD, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      $match
    );
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_ELEMENT_ADD_TO_FAVORITES, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      $match
    );
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_COMMENT_ADD, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      $match
    );
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_USER_FOLLOW, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      $match
    );
  }
  
  protected function registerUser($email)
  {
    $this->procedure_registration_success($email);
  }
  
  protected function checkUserIsNotProhibedForAnonymousActions()
  {
    $this->checkUserProhibedActionStatus(false);
  }
  
}