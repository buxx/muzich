<?php

namespace Muzich\CoreBundle\ElementFactory\Site;

use Muzich\CoreBundle\Entity\Element;

/**
 *
 * @author bux
 */
class BaseFactory implements FactoryInterface
{
  
  protected $element;
  
  public function __construct(Element $element)
  {
    $this->element = $element;
  }
  
  public function getEmbedCode()
  {
    return null;
  }
  
}

?>
