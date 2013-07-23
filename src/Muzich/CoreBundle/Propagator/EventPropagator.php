<?php

namespace Muzich\CoreBundle\Propagator;

use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;

/**
 * Description of EventPropagator
 *
 * @author bux
 */
class EventPropagator
{
  protected $container;  
  
  public function __construct(Container $container)
  {
    $this->container = $container;
  }
  
  /** @return EntityManager */
  protected function getEntityManager()
  {
    return $this->container->get('doctrine')->getEntityManager();
  }
  
}