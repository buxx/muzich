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
  
  public function testConfirmationEmail()
  {
    $this->init();
    $this->registerUser('francky@mail.com');
    $this->checkUserEmailIsNotConfirmed();
    $this->checkUserCantMakeProhibedActionsForEmailNotConfirmed();
    $this->confirmEmail();
    $this->checkUserEmailIsConfirmed();
    $this->checkUserisNotProhibedForActionsBlockedByEmailNotConfirmed();
  }
  
  protected function registerUser($email)
  {
    $this->procedure_registration_success($email);
  }
  
  protected function checkUserEmailIsNotConfirmed()
  {
    $this->security_context_test->userIsInConditionEmailNotConfirmed($this->getUser());
  }
  
  protected function checkUserCantMakeProhibedActionsForEmailNotConfirmed()
  {
    $this->checkUserProhibedActionStatus(true);
  }
  
  protected function checkUserProhibedActionStatus($match)
  {
    foreach (array(
      SecurityContext::ACTION_ELEMENT_ADD, 
      SecurityContext::ACTION_ELEMENT_NOTE,
      SecurityContext::ACTION_COMMENT_ALERT,
      SecurityContext::ACTION_ELEMENT_ALERT,
      SecurityContext::ACTION_TAG_ADD,
      SecurityContext::ACTION_ELEMENT_TAGS_PROPOSITION,
      SecurityContext::ACTION_GROUP_ADD
    ) as $action)
    {
      $this->security_context_test->testUserCantMakeActionStatus( 
        $action, 
        SecurityContext::CONDITION_USER_EMAIL_NOT_CONFIRMED,
        $match
      );
    }
  }
  
  protected function confirmEmail()
  {
    $token = hash('sha256', $this->getUser()->getConfirmationToken().$this->getUser()->getEmail());
    $this->goToPage($this->generateUrl('email_confirm', array('token' => $token)));
    $this->isResponseRedirection();
  }
  
  protected function checkUserEmailIsConfirmed()
  {
    $this->security_context_test->userIsNotInConditionEmailNotConfirmed($this->getUser());
  }
  
  protected function checkUserisNotProhibedForActionsBlockedByEmailNotConfirmed()
  {
    $this->checkUserProhibedActionStatus(false);
  }
  
  public function testSetPassword()
  {
    
  }
  
  public function testSetUsername()
  {
    
  }
  
}