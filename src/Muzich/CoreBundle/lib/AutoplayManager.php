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
   * @param array $elements 
   */
  public function __construct($elements)
  {
    $this->elements = $elements;
  }
  
  public function getListJSON()
  {
    $list = array();
    
    foreach ($this->elements as $element)
    {
      if (
        // On doit connaitre l'id externe
        ($ref_id = $element->getData(Element::DATA_REF_ID)) && 
        // Et le site doit être pris en charge pour le autoplay
        in_array(
          ($element_type = $element->getType()), 
          $this->container->getParameter('dailymotion_player_width')
        )
      )
      
      $list[] = array(
        'element_ref_id'   => $ref_id,
        'element_type'     => $element->getType(),
        'element_id'       => $element->getId(),
        'element_name'     => $element->getName()
      );
    }
    
    return json_encode($list);
  }
  
}