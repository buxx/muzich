<?php

namespace Muzich\CoreBundle\Tests\Context;

use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Security\Context as SecurityContext;

class ContextTest extends \PHPUnit_Framework_TestCase
{
  
  public function testActionsWithNotConfirmedEmailUser()
  {
    $user_not_confirmed_email = new User();
    $user_not_confirmed_email->setEmailConfirmed(false);
    $secutiry_context = new SecurityContext($user_not_confirmed_email);
    
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_ADD));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_NOTE));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_COMMENT_ALERT));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_ALERT));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_TAG_ADD));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_TAGS_PROPOSITION));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_GROUP_ADD));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_COMMENT_ADD));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_USER_FOLLOW));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_ADD_TO_FAVORITES));
    
    $this->assertEquals('UserEmailNotConfirmed', $secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_ELEMENT_NOTE));
    $this->assertEquals('UserEmailNotConfirmed', $secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_ELEMENT_TAGS_PROPOSITION));
    $this->assertEquals('UserEmailNotConfirmed', $secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_USER_FOLLOW));
    $this->assertEquals('UserEmailNotConfirmed', $secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_ELEMENT_ADD_TO_FAVORITES));
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
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_COMMENT_ADD));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_TAGS_PROPOSITION));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_GROUP_ADD));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_USER_FOLLOW));
    $this->assertTrue($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_ADD_TO_FAVORITES));
    
    $this->assertFalse($secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_ELEMENT_NOTE));
    $this->assertFalse($secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_ELEMENT_TAGS_PROPOSITION));
    $this->assertFalse($secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_USER_FOLLOW));
    $this->assertFalse($secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_NO_SCORING, SecurityContext::ACTION_ELEMENT_ADD_TO_FAVORITES));
  }
  
  public function testCanMakeActionsWithNotConnectedUser()
  {
    $secutiry_context = new SecurityContext('anon.');
    
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_ADD));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_NOTE));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_COMMENT_ALERT));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_ALERT));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_TAG_ADD));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_COMMENT_ADD));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_TAGS_PROPOSITION));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_GROUP_ADD));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_USER_FOLLOW));
    $this->assertFalse($secutiry_context->canMakeAction(SecurityContext::ACTION_ELEMENT_ADD_TO_FAVORITES));
    
    $this->assertEquals('UserNotConnected', $secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_CANT_MAKE, SecurityContext::ACTION_ELEMENT_ADD));
    $this->assertEquals('UserNotConnected', $secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_CANT_MAKE, SecurityContext::ACTION_ELEMENT_NOTE));
    $this->assertEquals('UserNotConnected', $secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_CANT_MAKE, SecurityContext::ACTION_COMMENT_ALERT));
    $this->assertEquals('UserNotConnected', $secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_CANT_MAKE, SecurityContext::ACTION_ELEMENT_ALERT));
    $this->assertEquals('UserNotConnected', $secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_CANT_MAKE, SecurityContext::ACTION_TAG_ADD));
    $this->assertEquals('UserNotConnected', $secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_CANT_MAKE, SecurityContext::ACTION_COMMENT_ADD));
    $this->assertEquals('UserNotConnected', $secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_CANT_MAKE, SecurityContext::ACTION_COMMENT_ADD));
    $this->assertEquals('UserNotConnected', $secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_CANT_MAKE, SecurityContext::ACTION_ELEMENT_TAGS_PROPOSITION));
    $this->assertEquals('UserNotConnected', $secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_CANT_MAKE, SecurityContext::ACTION_GROUP_ADD));
    $this->assertEquals('UserNotConnected', $secutiry_context->actionIsAffectedBy(SecurityContext::AFFECT_CANT_MAKE, SecurityContext::ACTION_ELEMENT_ADD_TO_FAVORITES));
  }
  
}