<?php

namespace Muzich\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Muzich\CoreBundle\Entity\Tag;

class LoadTagData implements FixtureInterface, ContainerAwareInterface
{
  
  private $container;
  private $entity_manager;
  
  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
  }
  
  /**
   *  
   */
  protected function createTag($name)
  {
    $tag = new Tag();
    $tag->setName($name);
    $this->entity_manager->persist($tag);
  }
  
  public function load($entity_manager)
  {
    $this->entity_manager = $entity_manager;

    foreach (array(
      'hardtek', 'tribe', 'electro', 'pop', 'poprock', 'indie',
       'independent', 'indie', 'indiepop', 'indierock', 'industrial', 
      'instrumental', 'italiano', 'jam', 'jazz',  'jazzrock',  'jazzy', 'jungle',
      'keyboard', 'latin',  'latino',  'live', 'lofi', 'lounge', 'meditation', 
      'melancolique', 'mellow',  'melodique', 'metal','metalcore','minimal', 
      'minimalism', 'minimaltechno', 'mix', 'movie' 
      
      ) as $tag_name)
    {
      $this->createTag($tag_name);
    }

    $this->entity_manager->flush();
  }
}