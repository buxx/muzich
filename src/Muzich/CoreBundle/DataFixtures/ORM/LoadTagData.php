<?php

namespace Muzich\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Muzich\CoreBundle\Entity\Tag;

class LoadTagData  extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
  
  private $container;
  private $entity_manager;
  
  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
  }
  
  public function getOrder()
  {
    return 2; // the order in which fixtures will be loaded
  }
  
  /**
   *  
   */
  protected function createTag($name)
  {
    $tag = new Tag();
    $tag->setName(ucfirst($name));
    $this->entity_manager->persist($tag);
    $this->addReference('tag_'.$name, $tag);
  }
  
  public function load($entity_manager)
  {
    $this->entity_manager = $entity_manager;

    foreach (array(
      'hardtek', 'tribe', 'electro', 'pop', 'poprock',
       'independent', 'indie', 'indiepop', 'indierock', 'industrial', 
      'instrumental', 'italiano', 'jam', 'jazz',  'jazzrock',  'jazzy', 'jungle',
      'keyboard', 'latin',  'latino',  'live', 'lofi', 'lounge', 'meditation', 
      'melancolique', 'mellow',  'melodique', 'metal','metalcore','minimal', 
      'minimalism', 'minimaltechno', 'mix', 'movie', 'medieval', 'psytrance', 'chanteuse'
      , 'dubstep', 'drum and bass', 'beatbox', 'hardcore'
      
      ) as $tag_name)
    {
      $this->createTag($tag_name);
    }

    $this->entity_manager->flush();
  }
}