<?php

namespace Muzich\CoreBundle\Factory;

use Muzich\CoreBundle\Entity\Element;
use Symfony\Component\DependencyInjection\Container;
use \Exception;

/**
 *
 * @author bux
 */
abstract class ElementFactory
{
  
  /**
   *
   * @var Element 
   */
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
  
  /**
   * Retourne l'url relative dans le site
   * 
   * @return string
   */
  protected function getCleanedUrl($decode = false)
  {
    // Procèdures de nettoyages après constat d'erreurs
    $url = $this->element->getUrl();
    if ($decode)
    {
      $url = urldecode($url);  
    }
    
    $url = str_replace('www.', '', $url);
    $url = str_replace('http://'.$this->element->getType(), '', $url);
    $url = str_replace('https://'.$this->element->getType(), '', $url);
    return $url;
  }
  
  public function retrieveDatas()
  {
    $this->element->setDatas(array());
  }
  
  public function proceedEmbedCode()
  {
    $this->element->setEmbed(null);
  }
  
  public function proceedThumbnailUrl()
  {
    $this->element->setThumbnailUrl(null);
  }
  
}

?>
