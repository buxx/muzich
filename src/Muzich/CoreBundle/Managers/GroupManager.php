<?php

namespace Muzich\CoreBundle\Managers;

use Muzich\CoreBundle\Entity\Group;
use Muzich\CoreBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

/**
 * 
 *
 * @author bux
 */
class GroupManager
{
  
  protected $em;
  protected $group;
  protected $container;
  
  public function __construct(Group $group, EntityManager $em, Container $container)
  {
    $this->group = $group;
    $this->em = $em;
    $this->container = $container;
    
    // Slug stuff
    $evm = new \Doctrine\Common\EventManager();
    // ORM and ODM
    $sluggableListener = new \Gedmo\Sluggable\SluggableListener();
    $evm->addEventSubscriber($sluggableListener);
    // now this event manager should be passed to entity manager constructor
    $this->em->getEventManager()->addEventSubscriber($sluggableListener);
  }
  
  /**
   * Procédure chargé de construire le contenu tags.
   * 
   * @param array $tags_ids
   */
  public function proceedTags($tags_ids)
  {
    // La procédure se charge pour le moment des tags
    $this->group->setTags(null);
    // Il faut supprimer tous les liens vers les tags
    foreach ($this->em->getRepository('MuzichCoreBundle:GroupsTagsFavorites')
      ->findBy(array(
        'group' => $this->group->getId()
      )) as $group_tag)     
    {
      $this->em->remove($group_tag);
    }
    // Pour etablir les nouveaux liens
    $this->group->setTagsWithIds($this->em, $tags_ids);
  }
  
}