<?php

namespace Muzich\CoreBundle\Tests\Context;

use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Security\Context as SecurityContext;

class ContextTest extends \PHPUnit_Framework_TestCase
{
  
  public function testActionsWithNotConfirmedEmailUser()
  {
    $secutiry_context = new SecurityContext(new User());
    
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_ADD));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_NOTE));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_COMMENT_ALERT));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_ALERT));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_TAG_ADD));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_TAGS_PROPOSITION));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_GROUP_ADD));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_USER_FOLLOW));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_ADD_TO_FAVORITES));
    
    $this->assertTrue($secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_ELEMENT_NOTE));
    $this->assertTrue($secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_ELEMENT_TAGS_PROPOSITION));
    $this->assertTrue($secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_USER_FOLLOW));
    $this->assertTrue($secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_ELEMENT_ADD_TO_FAVORITES));
  }
  
  public function testActionsWithConfirmedEmailUser()
  {
    $user_email_confirmed = new User();
    $user_email_confirmed->setEmailConfirmed(true);
    $secutiry_context = new SecurityContext($user_email_confirmed);
    
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_ADD));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_NOTE));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_COMMENT_ALERT));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_ALERT));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_TAG_ADD));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_TAGS_PROPOSITION));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_GROUP_ADD));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_USER_FOLLOW));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_ADD_TO_FAVORITES));
    
    $this->assertFalse($secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_ELEMENT_NOTE));
    $this->assertFalse($secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_ELEMENT_TAGS_PROPOSITION));
    $this->assertFalse($secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_USER_FOLLOW));
    $this->assertFalse($secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_ELEMENT_ADD_TO_FAVORITES));
  }
  
}