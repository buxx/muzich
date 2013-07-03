<?php

namespace Muzich\CoreBundle\lib;

use Muzich\CoreBundle\Entity\Element;

/**
 *  Boite a outils pour les Tags. 
 */
class AutoplayManager
{
  
  /**
   *
   * @var array of Element
   */
  private $elements;
  
  /**
   *
   * @var Container 
   */
  protected $container;
  
  /**
   *
   * @param array $elements 
   */
  public function __construct($elements, $container)
  {
    $this->elements = $elements;
    $this->container = $container;
  }
  
  public function getList()
  {
    $list = array();
    
    foreach ($this->elements as $element)
    {
      if (
        // On doit connaitre l'id externe
        ($ref_id = $element->getRefId(true)) && 
        // Et le site doit être pris en charge pour le autoplay
        in_array(
          ($element_type = $element->getType()), 
          $this->container->getParameter('autoplay_sites_enabled')
        )
      )
      
      $list[] = array(
        'element_ref_id'   => $ref_id,
        'element_type'     => $element->getType(),
        'element_id'       => $element->getId(),
        'element_name'     => $element->getName(),
        'element_url'      => $element->getUrl(),
      );
    }
    
    return $list;
  }
  
  public function shuffle()
  {
    shuffle($this->elements);
  }
  
}