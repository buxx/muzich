<?php

namespace Muzich\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Muzich\CoreBundle\Entity\UsersElementsFavorites;

class LoadUsersElementsFavoritesData  extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
  
  private $container;
  private $entity_manager;
  
  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
  }
  
  public function getOrder()
  {
    return 102; // the order in which fixtures will be loaded
  }
  
  /**
   *  
   */
  protected function createRecord($user, $element)
  {
    $favorite = new UsersElementsFavorites();
    $favorite->setUser($user);
    $favorite->setElement($element);
    $this->entity_manager->persist($favorite);
    //$this->addReference('user_tag_'.$user->getId().'_'.$tag->getId(), $userTag);
  }
  
  public function load(ObjectManager $entity_manager)
  {
    $this->entity_manager = $entity_manager;

    // favoris de bux
    $bux = $this->entity_manager->merge($this->getReference('user_bux'));
    $paul = $this->entity_manager->merge($this->getReference('user_paul'));
    
    $youtube_heretik_1 = $this->getReference('element_youtube_heretik_1');
    $youtube_djfab_1 = $this->getReference('element_youtube_djfab_1');
    $jamendo_caio_1 = $this->getReference('element_jamendo_caio_1');
    
    $this->createRecord($bux, $youtube_heretik_1);
    $this->createRecord($bux, $youtube_djfab_1);
    $this->createRecord($paul, $youtube_heretik_1);
    $this->createRecord($paul, $jamendo_caio_1);
    
    $this->entity_manager->flush();
  }
}