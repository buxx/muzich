<?php

namespace Muzich\CoreBundle\Security;

use Muzich\CoreBundle\Entity\User;

class Context
{
  
  const ACTION_ELEMENT_ADD = 0;
  const ACTION_ELEMENT_NOTE = 1;
  const ACTION_ELEMENT_ALERT = 2;
  const ACTION_ELEMENT_ADD_TO_FAVORITES = 3;
  const ACTION_ELEMENT_TAGS_PROPOSITION = 4;
  const ACTION_GROUP_ADD = 5;
  const ACTION_COMMENT_ADD = 6;
  const ACTION_COMMENT_ALERT = 7;
  const ACTION_USER_FOLLOW = 8;
  const ACTION_TAG_ADD = 9;
  
  const AFFECT_CANT_MAKE = 0;
  const AFFECT_NO_SCORING = 1;
  
  const CONDITION_USER_EMAIL_NOT_CONFIRMED = 'UserEmailNotConfirmed';
  
  static $affecteds_actions = array(
    self::AFFECT_CANT_MAKE => array(
      self::ACTION_ELEMENT_ADD,
      self::ACTION_ELEMENT_NOTE,
      self::ACTION_COMMENT_ALERT,
      self::ACTION_ELEMENT_ALERT,
      self::ACTION_TAG_ADD,
      self::ACTION_ELEMENT_TAGS_PROPOSITION,
      self::ACTION_GROUP_ADD
    ),
    self::AFFECT_NO_SCORING => array(
      self::ACTION_ELEMENT_NOTE,
      self::ACTION_ELEMENT_ADD_TO_FAVORITES,
      self::ACTION_ELEMENT_TAGS_PROPOSITION,
      self::ACTION_USER_FOLLOW
    )
  );
  
  static $affecteds_conditions = array(
    self::AFFECT_CANT_MAKE => array(
      self::CONDITION_USER_EMAIL_NOT_CONFIRMED
    ),
    self::AFFECT_NO_SCORING => array(
      self::CONDITION_USER_EMAIL_NOT_CONFIRMED
    )
  );
  
  private $user;
  
  public function __construct(User $user)
  {
    $this->user = $user;
  }
  
  public function canMakeAction($action)
  {
    if ($this->actionIsAffectedBy(self::AFFECT_CANT_MAKE, $action))
      return false;
    return true;
  }
  
  protected function actionCanBeAffectedBy($affect, $action)
  {
    if (!array_key_exists($affect, self::$affecteds_actions))
      throw new \Exception("Unknow action $action");
    
    if (in_array($action, self::$affecteds_actions[$affect]))
      return true;
    return false;
  }
  
  public function actionIsAffectedBy($affect, $action)
  {
    if ($this->actionCanBeAffectedBy($affect, $action))
    {
      foreach (self::$affecteds_conditions[$affect] as $affected_condition)
      {
        $affected_condition_method = 'is'.$affected_condition;
        if ($this->$affected_condition_method())
        {
          return true;
        }
      }
    }
    
    return false;
  }
  
  protected function isUserEmailNotConfirmed()
  {
    if ($this->user->isEmailConfirmed())
    {
      return false;
    }
    return true;
  }
  
}