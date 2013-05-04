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
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_ELEMENT_ADD, 
      SecurityContext::CONDITION_USER_EMAIL_NOT_CONFIRMED,
      $match
    );
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_ELEMENT_NOTE, 
      SecurityContext::CONDITION_USER_EMAIL_NOT_CONFIRMED,
      $match
    );
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_COMMENT_ALERT, 
      SecurityContext::CONDITION_USER_EMAIL_NOT_CONFIRMED,
      $match
    );
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_ELEMENT_ALERT, 
      SecurityContext::CONDITION_USER_EMAIL_NOT_CONFIRMED,
      $match
    );
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_TAG_ADD, 
      SecurityContext::CONDITION_USER_EMAIL_NOT_CONFIRMED,
      $match
    );
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_ELEMENT_TAGS_PROPOSITION, 
      SecurityContext::CONDITION_USER_EMAIL_NOT_CONFIRMED,
      $match
    );
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_GROUP_ADD, 
      SecurityContext::CONDITION_USER_EMAIL_NOT_CONFIRMED,
      $match
    );
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
    $this->init();
    $this->registerUser('trolita@mail.com');
    $this->checkUserPasswordHasNotBeenSet();
    $this->updatePasswordMessageExist();
    $this->updatePassword();
    $this->checkUserPasswordHasBeenSet();
    $this->updatePasswordMessageNotExist();
  }
  
  protected function checkUserPasswordHasNotBeenSet()
  {
    $this->assertFalse($this->getUser()->isPasswordSet());
  }
  
  protected function updatePasswordMessageExist()
  {
    $this->goToPage($this->generateUrl('home'));
    $this->exist('div.choose_password');
  }
  
  protected function updatePassword()
  {
    $this->goToPage($this->generateUrl('my_account'));
    
    $this->exist('form[action="'.($url = $this->generateUrl(
      'change_password', array('open' => 'change_password')
    )).'"]');
    $this->exist('form[action="'.$url.'"] input[id="user_password_plain_password_first"]');
    $this->exist('form[action="'.$url.'"] input[id="user_password_plain_password_second"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['user_password[plain_password][first]'] = 'trololo';
    $form['user_password[plain_password][second]'] = 'trololo';
    $this->submit($form);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // On se déconnecte
    $this->disconnectUser();
    
    // Et on se connecte avec le nouveau mot de passe
    $this->connectUser('trolita@mail.com', 'trololo');
  }
  
  protected function checkUserPasswordHasBeenSet()
  {
    $this->assertTrue($this->getUser()->isPasswordSet());
  }
  
  protected function updatePasswordMessageNotExist()
  {
    $this->goToPage($this->generateUrl('home'));
    $this->notExist('div.choose_password');
  }
  
  public function testSetUsername()
  {
    $this->init();
    $this->registerUser('boulouduf@mail.com');
    $this->userHasNotDefinedUsername();
    $this->updateUserNameLinkExist();
    $this->updateUsername('boulouduf');
    $this->userHasDefinedUsername('boulouduf');
    $this->updateUserNameLinkNotExist();
  }
  
  protected function userHasNotDefinedUsername()
  {
    $this->assertTrue($this->getUser()->isUsernameUpdatable());
  }
  
  protected function updateUserNameLinkExist()
  {
    $this->goToPage($this->generateUrl('my_account'));
    $this->exist('a.username_update');
  }
  
  protected function updateUsername($username)
  {
    $this->goToPage($this->generateUrl('change_username'));
    $extract = $this->crawler->filter('input[name="form[_token]"]')
      ->extract(array('value'));
    $csrf = $extract[0];
    $this->crawler = $this->client->request(
      'POST', 
      $this->generateUrl('change_username'), 
      array(
        'form' => array(
          'username' => $username,
          '_token' => $csrf
        )
      ), 
      array(), 
      array()
    );
    
    $this->isResponseRedirection();
  }
  
  protected function userHasDefinedUsername($username)
  {
    $this->assertEquals($username, $this->getUser()->getUsername());
    $this->assertFalse($this->getUser()->isUsernameUpdatable());
  }
  
  protected function updateUserNameLinkNotExist()
  {
    $this->goToPage($this->generateUrl('my_account'));
    $this->notExist('a.username_update');
  }
  
  public function testNoScoringAffections()
  {
    $this->init();
    $this->registerUser('giboulet@mail.com');
    $bux_score = $this->getBuxScore();
    $this->addBuxElementToFavorite();
    $this->followBux();
    $this->assertEquals($bux_score, $this->getBuxScore());
  }
  
  protected function getBuxScore()
  {
    return $this->getUser('bux')->getReputation();
  }
  
  protected function addBuxElementToFavorite()
  {
    $bux = $this->getUser('bux');
    $element = $this->findOneBy('Element', 'Ed Cox - La fanfare des teuffeurs (Hardcordian)');
    $url = $this->generateUrl('favorite_add', array(
      'id'    => $element->getId(),
      'token' => $bux->getPersonalHash($element->getId())
    ));
    
    $this->client->request(
      'GET', 
      $this->generateUrl('favorite_add', array(
        'id'    => $element->getId(),
        'token' => $this->getUser()->getPersonalHash($element->getId())
      )), array(), array(), array(
        'HTTP_X-Requested-With' => 'XMLHttpRequest',
    ));
    $fav = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->findOneBy(array(
        'user'    => $this->getUser()->getId(),
        'element' => $element->getId()
      ));
    $this->assertTrue(!is_null($fav));
  }
  
  protected function followBux()
  {
    $bux = $this->getUser('bux');
    $this->goToPage($this->generateUrl('follow', array(
      'type' => 'user', 
      'id' => $bux->getId(),
      'token' => $this->getUser()->getPersonalHash($bux->getId())
    )));
    
    $FollowUser = $this->getDoctrine()->getRepository('MuzichCoreBundle:FollowUser')
      ->findOneBy(array(
        'follower' => $this->getUser()->getId(),
        'followed' => $bux->getId()
      ))
    ;
    $this->assertTrue(!is_null($FollowUser));
  }
  
}