<?php

namespace Muzich\CoreBundle\Managers;

use Muzich\CoreBundle\Entity\Tag;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

/**
 * 
 *
 * @author bux
 */
class TagManager
{
  
  protected $em;
  
  public function __construct(EntityManager $em)
  {
    $this->em = $em;
    
    // Slug stuff
    $evm = new \Doctrine\Common\EventManager();
    // ORM and ODM
    $sluggableListener = new \Gedmo\Sluggable\SluggableListener();
    $evm->addEventSubscriber($sluggableListener);
    // now this event manager should be passed to entity manager constructor
    $this->em->getEventManager()->addEventSubscriber($sluggableListener);
  }
  
  public function flush()
  {
    $this->em->flush();
  }
  
  public function persist(Tag $tag)
  {
    $this->em->persist($tag);
  }
  
}