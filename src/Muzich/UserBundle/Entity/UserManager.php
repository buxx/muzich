<?php

/*
 * 
 */

namespace Muzich\UserBundle\Entity;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Doctrine\ORM\EntityManager;
//use FOS\UserBundle\Entity\UserManager as UserManagerBase; UPGRADE FOSUserBundle 1.3
use FOS\UserBundle\Doctrine\UserManager as UserManagerBase;

/**
 */
class UserManager extends UserManagerBase
{
  /**
   * Constructor.
   */
  public function __construct(EncoderFactoryInterface $encoderFactory, CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer, EntityManager $em, $class)
  {
    parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $em, $class);
    
    // Slug stuff
    $evm = new \Doctrine\Common\EventManager();
    // ORM and ODM
    $sluggableListener = new \Gedmo\Sluggable\SluggableListener();
    $evm->addEventSubscriber($sluggableListener);
    // now this event manager should be passed to entity manager constructor

    $this->objectManager->getEventManager()->addEventSubscriber($sluggableListener);
  }
  
  public function getNewReadyUser()
  {
    $user = $this->createUser();
    $user->setUsername($this->generateUsername());
    $user->setPlainPassword($this->generatePassword(32));
    $user->setEnabled(true);
    $user->setCguAccepted(true);
    $user->setEmailConfirmed(false);
    $user->setUsernameUpdatable(true);
    $user->setPasswordSet(false);
    return $user;
  }
  
  protected function generateUsername()
  {
    $count = $this->repository->countUsers();
    while ($this->usernameExist($count))
    {
      $count++;
    }
    
    return 'User'.$count;
  }
  
  protected function usernameExist($count)
  {
    $username = 'User'.$count;
    if ($this->repository->findOneByUsername($username))
    {
      return true;
    }
    
    return false;
  }
  
  protected function generatePassword($length = 8)
  {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $count = mb_strlen($chars);
    
    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }
    
    return $result;
  }

}