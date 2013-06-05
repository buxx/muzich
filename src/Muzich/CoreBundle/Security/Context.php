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
  const ACTION_GET_FAVORITES_TAGS = 10;
  
  const ACTION_PLAYLIST_SHOW = 11;
  const ACTION_PLAYLIST_DATA_AUTOPLAY = 12;
  const ACTION_PLAYLIST_ADD_ELEMENT = 13;
  const ACTION_PLAYLIST_UPDATE_ORDER = 14;
  const ACTION_PLAYLIST_REMOVE_ELEMENT = 15;
  const ACTION_PLAYLIST_ADD_PROMPT = 16;
  const ACTION_PLAYLIST_CREATE = 17;
  const ACTION_PLAYLIST_COPY = 18;
  const ACTION_PLAYLIST_DELETE = 19;
  const ACTION_PLAYLIST_UNPICK = 20;
  const ACTION_PLAYLIST_PICK = 21;
  
  const AFFECT_CANT_MAKE = 0;
  const AFFECT_NO_SCORING = 1;
  
  const CONDITION_USER_EMAIL_NOT_CONFIRMED = 'UserEmailNotConfirmed';
  const CONDITION_USER_NOT_CONNECTED = 'UserNotConnected';
  
  static $affecteds_actions = array(
    self::AFFECT_CANT_MAKE => array(
      self::ACTION_ELEMENT_ADD => array(
        self::CONDITION_USER_NOT_CONNECTED,
        self::CONDITION_USER_EMAIL_NOT_CONFIRMED
      ),
      self::ACTION_ELEMENT_NOTE => array(
        self::CONDITION_USER_NOT_CONNECTED,
        self::CONDITION_USER_EMAIL_NOT_CONFIRMED
      ),
      self::ACTION_COMMENT_ALERT => array(
        self::CONDITION_USER_NOT_CONNECTED,
        self::CONDITION_USER_EMAIL_NOT_CONFIRMED
      ),
      self::ACTION_ELEMENT_ALERT => array(
        self::CONDITION_USER_NOT_CONNECTED,
        self::CONDITION_USER_EMAIL_NOT_CONFIRMED
      ),
      self::ACTION_TAG_ADD => array(
        self::CONDITION_USER_NOT_CONNECTED,
        self::CONDITION_USER_EMAIL_NOT_CONFIRMED
      ),
      self::ACTION_ELEMENT_TAGS_PROPOSITION => array(
        self::CONDITION_USER_NOT_CONNECTED,
        self::CONDITION_USER_EMAIL_NOT_CONFIRMED
      ),
      self::ACTION_GROUP_ADD => array(
        self::CONDITION_USER_NOT_CONNECTED,
        self::CONDITION_USER_EMAIL_NOT_CONFIRMED
      ),
      self::ACTION_ELEMENT_ADD_TO_FAVORITES => array(
        self::CONDITION_USER_NOT_CONNECTED
      ),
      self::ACTION_COMMENT_ADD => array(
        self::CONDITION_USER_NOT_CONNECTED
      ),
      self::ACTION_USER_FOLLOW => array(
        self::CONDITION_USER_NOT_CONNECTED
      ),
      self::ACTION_GET_FAVORITES_TAGS => array(
        self::CONDITION_USER_NOT_CONNECTED
      ),
      self::ACTION_PLAYLIST_ADD_ELEMENT => array(
        self::CONDITION_USER_NOT_CONNECTED
      ),
      self::ACTION_PLAYLIST_UPDATE_ORDER => array(
        self::CONDITION_USER_NOT_CONNECTED  
      ),
      self::ACTION_PLAYLIST_REMOVE_ELEMENT => array(
        self::CONDITION_USER_NOT_CONNECTED  
      ),
      self::ACTION_PLAYLIST_CREATE => array(
        self::CONDITION_USER_NOT_CONNECTED  
      ),
      self::ACTION_PLAYLIST_COPY => array(
        self::CONDITION_USER_NOT_CONNECTED  
      ),
      self::ACTION_PLAYLIST_DELETE => array(
        self::CONDITION_USER_NOT_CONNECTED  
      ),
      self::ACTION_PLAYLIST_UNPICK => array(
        self::CONDITION_USER_NOT_CONNECTED  
      ),
      self::ACTION_PLAYLIST_PICK => array(
        self::CONDITION_USER_NOT_CONNECTED  
      )
    ),
    self::AFFECT_NO_SCORING => array(
      self::ACTION_ELEMENT_NOTE => array(
        self::CONDITION_USER_EMAIL_NOT_CONFIRMED
      ),
      self::ACTION_ELEMENT_ADD_TO_FAVORITES => array(
        self::CONDITION_USER_EMAIL_NOT_CONFIRMED
      ),
      self::ACTION_ELEMENT_TAGS_PROPOSITION => array(
        self::CONDITION_USER_EMAIL_NOT_CONFIRMED
      ),
      self::ACTION_USER_FOLLOW => array(
        self::CONDITION_USER_EMAIL_NOT_CONFIRMED
      )
    )
  );
  
  private $user;
  private $anonymous = false;
  
  public function __construct($user)
  {
    if ($user instanceof User)
    {
      $this->user = $user;
    }
    else if ($user == 'anon.')
    {
      $this->user = new User();
      $this->anonymous = true;
    }
    else
    {
      throw new \Exception('Unable to determine type of user');
    }
  }
  
  public function canMakeAction($action)
  {
    if ($this->actionIsAffectedBy(self::AFFECT_CANT_MAKE, $action) !== false)
      return false;
    return true;
  }
  
  protected function actionCanBeAffectedBy($affect, $action)
  {
    if (!array_key_exists($affect, self::$affecteds_actions))
      throw new \Exception("Unknow action $action");
    
    if (array_key_exists($action, self::$affecteds_actions[$affect]))
      return true;
    return false;
  }
  
  public function actionIsAffectedBy($affect, $action)
  {
    if ($this->actionCanBeAffectedBy($affect, $action))
    {
      foreach (self::$affecteds_actions[$affect][$action] as $affected_condition)
      {
        if ($this->userIsInThisCondition($affected_condition))
        {
          return $affected_condition;
        }
      }
    }
    
    return false;
  }
  
  public function userIsInThisCondition($condition)
  {
    $affected_condition_method = 'is'.$condition;
    if ($this->$affected_condition_method())
    {
      return true;
    }
    return false;
  }
  
  protected function isUserNotConnected()
  {
    if ($this->anonymous)
    {
      return true;
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
  
  public function getConditionForAffectedAction($action)
  {
    
  }
  
}