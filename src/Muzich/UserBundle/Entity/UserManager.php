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

}