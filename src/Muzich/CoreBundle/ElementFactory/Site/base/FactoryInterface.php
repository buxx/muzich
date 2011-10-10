<?php

namespace Muzich\CoreBundle\ElementFactory\Site\base;

use Muzich\CoreBundle\Entity\Element;
use Symfony\Component\DependencyInjection\Container;

interface FactoryInterface
{
  
  public function __construct(Element $element, Container $container);
  
  public function getEmbedCode();
  
}

?>
