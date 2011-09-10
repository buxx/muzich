<?php

namespace Muzich\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Muzich\CoreBundle\Entity\ElementType;

class LoadElementTypeData  extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
  
  private $container;
  private $entity_manager;
  
  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
  }
  
  public function getOrder()
  {
    return 3; // the order in which fixtures will be loaded
  }
  
  /**
   *  
   */
  protected function createElementType($id, $name)
  {
    $elementType = new ElementType();
    $elementType->setId($id);
    $elementType->setName(ucfirst($name));
    $this->entity_manager->persist($elementType);
    $this->addReference('element_type_'.$id, $elementType);
  }
  
  public function load($entity_manager)
  {
    $this->entity_manager = $entity_manager;

    foreach (array(
      'youtube' => 'Youtube', 'soundclound' => 'SoundCloud', 
      'son2teuf' => 'Son2Teuf', 'jamendo' => 'jamendo'
      ) as $id => $name)
    {
      $this->createElementType($id, $name);
    }

    $this->entity_manager->flush();
  }
}