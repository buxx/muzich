<?php

namespace Muzich\CoreBundle\ElementFactory\Site\base;

use Muzich\CoreBundle\Entity\Element;
use Symfony\Component\DependencyInjection\Container;
use \Exception;

/**
 *
 * @author bux
 */
class BaseFactory implements FactoryInterface
{
  
  protected $element;
  protected $container;
  
  /**
   *
   * @param Element $element
   * @param Container $container 
   */
  public function __construct(Element $element, Container $container)
  {
    $this->element   = $element;
    $this->container = $container;
  }
  
  public function getEmbedCode()
  {
    return null;
  }
  
  /**
   * Retourne l'url relative dans le site
   * 
   * @return string
   */
  protected function getCleanedUrl()
  {
    $url = str_replace('www.', '', $this->element->getUrl());
    return str_replace('http://'.$this->element->getType(), '', $url);
  }
  
  public function getThumbnailUrl()
  {
    return null;
  }
  
}

?>
