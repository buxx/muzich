<?php

namespace Muzich\CoreBundle\Tests\lib\Security;

use Muzich\CoreBundle\lib\Test\Client;
use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Security\Context as SecurityContext;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Muzich\CoreBundle\Tests\lib\Security\ContextTestCases as SecurityContextTestCases;

class Context
{
  
  protected $test;
  protected $security_context_tests;
  
  public function __construct(Client $client, WebTestCase $test)
  {
    $this->test = $test;
    $this->security_context_tests = new SecurityContextTestCases($client, $test);
  }
  
  public function userIsInConditionEmailNotConfirmed(User $user)
  {
    return $this->userIsInCondition($user, SecurityContext::CONDITION_USER_EMAIL_NOT_CONFIRMED);
  }
  
  public function userIsNotInConditionEmailNotConfirmed(User $user)
  {
    return !$this->userIsInCondition($user, SecurityContext::CONDITION_USER_EMAIL_NOT_CONFIRMED);
  }
  
  protected function userIsInCondition(User $user, $condition)
  {
    $security_context = new SecurityContext($user);
    return $security_context->userIsInThisCondition($condition);
  }
  
  public function testUserCantMakeActionStatus($action, $condition, $match)
  {
    $this->test->assertEquals($match, $this->testActionResponseInPratice($action, $condition, false));
  }
  
  private function testActionResponseInPratice($action, $condition, $success)
  {
    switch ($action)
    {
      case SecurityContext::ACTION_ELEMENT_ADD:
        return $this->security_context_tests->addElementResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_ELEMENT_NOTE:
        return $this->security_context_tests->noteElementResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_COMMENT_ALERT:
        return $this->security_context_tests->alertCommentResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_ELEMENT_ALERT:
        return $this->security_context_tests->alertElementResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_TAG_ADD:
        return $this->security_context_tests->addTagResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_ELEMENT_TAGS_PROPOSITION:
        return $this->security_context_tests->proposeElementTagsResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_GROUP_ADD:
        return $this->security_context_tests->addGroupResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_COMMENT_ADD:
        return $this->security_context_tests->addCommentResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_ELEMENT_ADD_TO_FAVORITES:
        return $this->security_context_tests->addElementToFavoriteResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_USER_FOLLOW:
        return $this->security_context_tests->followUserResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_GET_FAVORITES_TAGS:
        return $this->security_context_tests->getFavoritesTagsResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_PLAYLIST_ADD_ELEMENT:
        return $this->security_context_tests->playlistAddElementResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_PLAYLIST_UPDATE_ORDER:
        return $this->security_context_tests->playlistUpdateOrderResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_PLAYLIST_REMOVE_ELEMENT:
        return $this->security_context_tests->playlistRemoveElementResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_PLAYLIST_CREATE:
        return $this->security_context_tests->playlistCreateResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_PLAYLIST_COPY:
        return $this->security_context_tests->playlistCopyResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_PLAYLIST_DELETE:
        return $this->security_context_tests->playlistDeleteResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_PLAYLIST_UNPICK:
        return $this->security_context_tests->playlistUnpickResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_PLAYLIST_PICK:
        return $this->security_context_tests->playlistPickResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_PLAYLIST_SHOW:
        return $this->security_context_tests->playlistShowResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_PLAYLIST_DATA_AUTOPLAY:
        return $this->security_context_tests->playlistAutoplayResponseIs($success, $condition);
      break;
      case SecurityContext::ACTION_PLAYLIST_ADD_PROMPT:
        return $this->security_context_tests->playlistPromptResponseIs($success, $condition);
      break;
      default:
        throw new \Exception('Action unknow');
    }
  }
  
}