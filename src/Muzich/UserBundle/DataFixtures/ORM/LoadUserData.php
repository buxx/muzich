<?php

namespace Muzich\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Muzich\CoreBundle\Entity\User;

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
  }
  
  public function load($entity_manager)
  {
    $this->entity_manager = $entity_manager;
    $this->user_manager = $this->container->get('fos_user.user_manager');
    
    $this->createUser('admin', 'admin@root', 'toor');
    $this->createUser('bux', 'bux@root', 'toor');
    $this->createUser('jean', 'jean@root', 'toor');
    $this->createUser('paul', 'paul@root', 'toor');
    $this->createUser('bob', 'bob@root', 'toor');
    $this->createUser('joelle', 'joelle@root', 'toor');

    $this->entity_manager->flush();
  }
}