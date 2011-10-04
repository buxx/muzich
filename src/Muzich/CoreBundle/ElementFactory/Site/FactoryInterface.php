<?php

namespace Muzich\CoreBundle\ElementFactory\Site;

use Muzich\CoreBundle\Entity\Element;

interface FactoryInterface
{
  
  public function __construct(Element $element);
  
  public function getEmbedCode();
  
}

?>
