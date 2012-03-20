<?php

namespace Muzich\CoreBundle\Propagator;

use Symfony\Component\DependencyInjection\Container;

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
}

?>
