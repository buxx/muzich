<?php

namespace Muzich\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Muzich\CoreBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
  
  private $container;
  private $user_manager;
  private $entity_manager;
  
  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
  }
  
  /**
   * 
   * 
   * @param string $username
   * @param string $email
   * @param string $password_raw
   * @param array $roles
   * @return User 
   */
  protected function createUser($username, $email, $password_raw, $superadmin = false, $enabled = true)
  {
    
    $user = $this->user_manager->createUser();
    $user = new User();
    $user->setUsername($username);
    $user->setEmail($email);
    $user->setSuperAdmin($superadmin);
    $user->setAlgorithm('sha512');
    
    $user->setPlainPassword($password_raw);
    $user->setEnabled($enabled);
    $this->user_manager->updatePassword($user);
    
    $this->user_manager->updateUser($user, false);
    $this->entity_manager->persist($user);
  }
  
  public function load($entity_manager)
  {
    $this->entity_manager = $entity_manager;
    $this->user_manager = $this->container->get('fos_user.user_manager');
    
    $this->createUser('admin', 'admin@root', 'toor');
    $this->createUser('bux', 'bux@root', 'toor');

    $this->entity_manager->flush();
  }
}