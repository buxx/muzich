<?php

namespace Muzich\CoreBundle\Factory;

use Muzich\CoreBundle\Entity\Element;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;
use Muzich\CoreBundle\lib\Api\Connector as ApiConnector;

/**
 *
 * @author bux
 */
abstract class ElementFactory
{
  
  protected $element;
  protected $container;
  protected $entity_manager;
  protected $api_connector;
  
  /**
   *
   * @param Element $element
   * @param Container $container 
   */
  public function __construct(Element $element, Container $container, EntityManager $entity_manager)
  {
    $this->element   = $element;
    $this->container = $container;
    $this->entity_manager = $entity_manager;
    $this->api_connector = new ApiConnector($element);
  }
  
  protected function getApiConnector()
  {
    return $this->api_connector;
  }
  
  /**
   * Retourne l'url relative dans le site
   * 
   * @return string
   */
  protected function getCleanedUrl($decode = false, $force_base_url = null)
  {
    // Procèdures de nettoyages après constat d'erreurs
    $url = $this->element->getUrl();
    if ($decode)
    {
      $url = urldecode($url);  
    }
    
    $base_url = $this->element->getType();
    if ($force_base_url)
    {
      $base_url = $force_base_url;
    }
    
    $url = str_replace('www.', '', $url);
    $url = str_replace('http://'.$base_url, '', $url);
    $url = str_replace('https://'.$base_url, '', $url);
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
    if (($thumb_url = $this->element->getData(Element::DATA_THUMB_URL)))
    {
      $this->element->setThumbnailUrl($thumb_url);
    }
  }
  
  protected function getJsonDataFromApiWithUrl($url)
  {
    $api_url = curl_init($url);
    
    $options = array(
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => array('Content-type: application/json')
    );
      
    curl_setopt_array($api_url, $options);
    return json_decode(curl_exec($api_url), true);
  }
  
  protected function configureApiConnector()
  {
    
  }
  
}

?>
