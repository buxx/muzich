<?php

namespace Muzich\CoreBundle\lib\Element;

use Muzich\CoreBundle\Entity\Element;

class UrlAnalyzer
{
  
  protected $url;
  protected $match_rules;
  
  // TODO: Ce serait bien que le match_rules soit dans un Element fille
  public function __construct(Element $element, $match_rules = array())
  {
    $this->url = $element->getCleanedUrl();
    $this->match_rules = $match_rules;
  }
  
  protected function getMatchRules()
  {
    foreach ($this->match_rules as $type => $rules)
    {
      foreach ($rules as $expression => $ref_id_position)
      {
        if (preg_match($expression, $this->url, $preg_result))
        {
          if (array_key_exists($ref_id_position, $preg_result))
          {
            return array(
              Element::DATA_TYPE => $type,
              Element::DATA_REF_ID => $preg_result[$ref_id_position]
            );
          }
        }
      }
    }
    
    return false;
  }
  
  public function haveMatch()
  {
    if ($this->getMatchRules() !== false)
    {
      return true;
    }
    
    return false;
  }
  
  public function getRefId()
  {
    if (($match_rules = $this->getMatchRules()))
    {
      return $match_rules[Element::DATA_REF_ID];
    }
    
    return null;
  }
  
  public function getType()
  {
    if (($match_rules = $this->getMatchRules()))
    {
      return $match_rules[Element::DATA_TYPE];
    }
    
    return null;
  }
  
}