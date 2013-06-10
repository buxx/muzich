<?php

namespace Muzich\CoreBundle\Managers;

use Muzich\CoreBundle\Entity\User;

class UserPrivacy
{
  
  const CONF_FAVORITES_PUBLIC = 'favorites_publics';
  
  static public $configurations = array(
    self::CONF_FAVORITES_PUBLIC => true
  );
  
  protected $user;
  
  public function __construct(User $user)
  {
    $this->user = $user;
  }

  public function get($configuration)
  {
    $this->configurationKnew($configuration);
    return $this->getConfigurationValue($configuration);
  }
  
  public function configurationKnew($configuration)
  {
    if (!array_key_exists($configuration, self::$configurations))
      throw new \Exception('Configuration is unknow');
  }
  
  protected function getConfigurationValue($configuration)
  {
    $user_privacy = $this->getUserPrivacy();
    if (!array_key_exists($configuration, $user_privacy))
      return self::$configurations[$configuration];
    
    return $user_privacy[$configuration];
  }
  
  protected function getUserPrivacy()
  {
    $privacy = $this->user->getPrivacy();
    if (!is_array($privacy))
      return array();
    
    return $privacy;
  }


  public function set($configuration, $value)
  {
    $this->configurationKnew($configuration);
    $user_privacy = $this->getUserPrivacy();
    $user_privacy[$configuration] = ($value)?true:false;
    $this->user->setPrivacy($user_privacy);
  }
  
}