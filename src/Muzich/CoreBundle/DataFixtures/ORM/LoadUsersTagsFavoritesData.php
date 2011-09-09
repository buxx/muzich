<?php

namespace Muzich\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Muzich\CoreBundle\Entity\UsersTagsFavorites;

class LoadUsersTagsFavoritesData  extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
  
  private $container;
  private $entity_manager;
  
  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
  }
  
  public function getOrder()
  {
    return 101; // the order in which fixtures will be loaded
  }
  
  /**
   *  
   */
  protected function createRecord($user, $tag, $position)
  {
    $userTag = new UsersTagsFavorites();
    $userTag->setUser($user);
    $userTag->setTag($tag);
    $userTag->setPosition($position);
    $this->entity_manager->persist($userTag);
    //$this->addReference('user_tag_'.$user->getId().'_'.$tag->getId(), $userTag);
  }
  
  public function load($entity_manager)
  {
    $this->entity_manager = $entity_manager;

    // tags de bux
    $bux = $this->entity_manager->merge($this->getReference('user_bux'));
    $pos = 0;
    foreach (array('hardtek', 'tribe', 'electro', 'metal', 'minimal', 'jungle') as $tag_name)
    {
      $this->createRecord(
        $bux, 
        $this->entity_manager->merge($this->getReference('tag_'.$tag_name)),
        $pos
      );
      $pos++;
    }

    // tags de jean
    $bux = $this->entity_manager->merge($this->getReference('user_jean'));
    $pos = 0;
    foreach (array('melancolique', 'mellow',  'melodique', 'metal','metalcore','minimal') as $tag_name)
    {
      $this->createRecord(
        $bux, 
        $this->entity_manager->merge($this->getReference('tag_'.$tag_name)),
        $pos
      );
      $pos++;
    }

    $this->entity_manager->flush();
  }
}