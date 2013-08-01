<?php
namespace Muzich\CoreBundle\Mining\Tag;

use Doctrine\ORM\EntityManager;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry as MongoManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class Tag
{
  
  protected $doctrine_entity_manager;
  protected $mongo_manager_registry;
  
  public function __construct(EntityManager $doctrine_entity_manager, MongoManagerRegistry $mongo_manager_registry)
  {
    $this->doctrine_entity_manager = $doctrine_entity_manager;
    $this->mongo_manager_registry = $mongo_manager_registry;
  }
  
  /** @return EntityManager */
  protected function getDoctrineEntityManager()
  {
    return $this->doctrine_entity_manager;
  }
  
  /** @return DocumentRepository */
  protected function getMongoRepository($repository)
  {
    return $this->mongo_manager_registry->getRepository($repository);
  }
  
  /** @return DocumentManager */
  protected function getMongoManager()
  {
    return $this->mongo_manager_registry->getManager();
  }
  
}