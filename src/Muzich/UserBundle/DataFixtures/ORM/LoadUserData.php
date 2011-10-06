<?php

namespace Muzich\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Entity\FollowUser;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
  
  private $container;
  private $user_manager;
  private $entity_manager;
  
  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
  }
  
  public function getOrder()
  {
    return 1; // the order in which fixtures will be loaded
  }
  
  /**
   * 
   * 
   * @param string $username
   * @param string $email
   * @param string $password_raw
   * @param array $roles
   * 
   * @return User
   */
  protected function createUser($username, $email, $password_raw, $superadmin = false, $enabled = true)
  {
    
    $user = $this->user_manager->createUser();
    $user->setUsername($username);
    $user->setEmail($email);
    $user->setSuperAdmin($superadmin);
    $user->setAlgorithm('sha512');
    
    $user->setPlainPassword($password_raw);
    $user->setEnabled($enabled);
    $this->user_manager->updatePassword($user);
    
    $this->user_manager->updateUser($user, false);
    $this->entity_manager->persist($user);
    $this->addReference('user_'.$username, $user);
    
    return $user;
  }
  
  protected function heFollowHim($follower, $followed)
  {
    $heFollowHim = new FollowUser();
    $heFollowHim->setFollower($follower);
    $heFollowHim->setFollowed($followed);
    $follower->addFollowUser($heFollowHim);
    $this->entity_manager->persist($heFollowHim);
  }
  
  public function load($entity_manager)
  {
    
    $this->entity_manager = $entity_manager;
    $this->user_manager = $this->container->get('fos_user.user_manager');
    
    // Slug stuff
    $evm = new \Doctrine\Common\EventManager();
    // ORM and ODM
    $sluggableListener = new \Gedmo\Sluggable\SluggableListener();
    $evm->addEventSubscriber($sluggableListener);
    // now this event manager should be passed to entity manager constructor
    $entity_manager->getEventManager()->addEventSubscriber($sluggableListener);
    
    
    // Création des Users
    $admin  = $this->createUser('admin', 'admin@root', 'toor');
    $bux    = $this->createUser('bux', 'sevajol.bastien@gmail.com', 'toor');
    $jean   = $this->createUser('jean', 'jean@root', 'toor');
    $paul   = $this->createUser('paul', 'paul@root', 'toor');
    $bob    = $this->createUser('bob', 'bob@root', 'toor');
    $joelle = $this->createUser('joelle', 'joelle@root', 'toor');

    // Relations
    $this->heFollowHim($bux, $jean);
    $this->heFollowHim($bux, $paul);
    $this->heFollowHim($joelle, $bux);
    
    $this->entity_manager->flush();
  }
}