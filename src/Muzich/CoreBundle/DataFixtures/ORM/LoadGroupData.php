<?php

namespace Muzich\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Muzich\CoreBundle\Entity\Group;
use Muzich\CoreBundle\Entity\FollowGroup;
use Muzich\CoreBundle\Entity\GroupsTagsFavorites;

class LoadGroupData  extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
  
  private $container;
  private $entity_manager;
  
  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
  }
  
  public function getOrder()
  {
    return 4;
  }
  
  protected function getArrayOfTag($names)
  {
    $tags = array();
    foreach ($names as $name)
    {
      $tags[] = $this->entity_manager->merge($this->getReference('tag_'.$name));
    }
    return $tags;
  }
  
  /**
   *  
   */
  protected function createGroup($reference_id, $name, $description, $open, $owner)
  {
    $group = new Group();
    $group->setName($name);
    $group->setDescription($description);
    $group->setOpen($open);
    $group->setOwner($owner);
    
    $this->entity_manager->persist($group);
    $this->addReference('group_'.$reference_id, $group);
    return $group;
  }
  
  protected function theyFollowGroup($followers, $group)
  {
    foreach ($followers as $follower)
    {
      $followGroup = new FollowGroup();
      $followGroup->setFollower($follower);
      $followGroup->setGroup($group);
      $this->entity_manager->persist($followGroup);
    }
  }
  
  protected function groupHasTags($tags, $group)
  {
    foreach ($tags as $pos => $tag)
    {
      $GroupsTagsFavorites = new GroupsTagsFavorites();
      $GroupsTagsFavorites->setTag($tag);
      $GroupsTagsFavorites->setGroup($group);
      $GroupsTagsFavorites->setPosition($pos);
      $this->entity_manager->persist($GroupsTagsFavorites);
    }
  }
  
  public function load($entity_manager)
  {
    $this->entity_manager = $entity_manager;

    // Slug stuff
    $evm = new \Doctrine\Common\EventManager();
    // ORM and ODM
    $sluggableListener = new \Gedmo\Sluggable\SluggableListener();
    $evm->addEventSubscriber($sluggableListener);
    // now this event manager should be passed to entity manager constructor
    $entity_manager->getEventManager()->addEventSubscriber($sluggableListener);
    
    // Création des groupes
    
    $group_dudeldrum = $this->createGroup('dudeldrum', 'DUDELDRUM', 
      nl2br("Un groupe de musique médievale."), 
    false, $this->entity_manager->merge($this->getReference('user_joelle')));
    
    $group_fan_de_psytrance = $this->createGroup('fan_de_psytrance', 'Fans de psytrance', 
      "La Trance psychédélique (souvent appelée psytrance) est une forme de trance (style de musique électronique) 
        apparue au début des années 1990 à Goa, Inde, d'où le nom de \"Goa\" ou \"trance-Goa\" donné au départ à ce 
        courant musical (ou encore Hippie Trance, 604 par analogie graphique avec GOA). La trance psychédélique est 
        caractérisée par un rythme rapide, dans la gamme des 125 à 160 battements par minute (bpm), contrairement à 
        l'ambient et autres formes d'house ou de techno. Ses basses sont fortes, sans interruption, sans changement 
        et recouvertes par beaucoup d'autres rythmes, souvent produits par le célèbre synthétiseur Roland TB-303.", 
    true, $this->entity_manager->merge($this->getReference('user_bob')));
    
    $group_joelle = $this->createGroup('joelle', 'Le groupe de joelle', 
      "Joelle, et ben elle aime bien la musique d'abord.", 
    true, $this->entity_manager->merge($this->getReference('user_joelle')));
    
    // Followers
    
    $this->theyFollowGroup(array(
      $this->entity_manager->merge($this->getReference('user_bux')),
      $this->entity_manager->merge($this->getReference('user_jean'))
    ), $group_dudeldrum);
    
    $this->theyFollowGroup(array(
      $this->entity_manager->merge($this->getReference('user_bux')),
      $this->entity_manager->merge($this->getReference('user_jean')),
      $this->entity_manager->merge($this->getReference('user_paul')),
      $this->entity_manager->merge($this->getReference('user_bob'))
    ), $group_fan_de_psytrance);
    
    // Tags
    $this->groupHasTags(array(
      $this->entity_manager->merge($this->getReference('tag_medieval'))
    ), $group_dudeldrum);
    
    $this->groupHasTags(array(
      $this->entity_manager->merge($this->getReference('tag_psytrance'))
    ), $group_fan_de_psytrance);
    
    $this->groupHasTags(array(
      $this->entity_manager->merge($this->getReference('tag_chanteuse'))
    ), $group_joelle);

    $this->entity_manager->flush();
  }
}