<?php

namespace Muzich\CoreBundle\lib\Collection;

use \Doctrine\Common\Collections\ArrayCollection;

abstract class CollectionManager
{
  
  protected $content;
  protected $schema = array();
  protected $object_reference_attribute;
  
  public function __construct($content)
  {
    if (!is_array($content))
      throw new \Exception('Content must be array type !');
    
    if (!count($this->schema))
      throw new \Exception('Schema must be defined in child class !');
    
    if (!count($this->object_reference_attribute))
      throw new \Exception('Object reference attribute must be defined in child class !');
    
    $this->content = $content;
  }
  
  public function add($object)
  {
    if (!$this->have($object))
    {
      $content_line = array();
      foreach ($this->schema as $attribute)
      {
        $method_name = 'get' . $attribute;
        $content_line[$attribute] = (string)$object->$method_name();
      }
      
      $this->content[] = $content_line;
    }
  }
  
  public function have($object)
  {
    $method_name = 'get' . $this->object_reference_attribute;
    foreach ($this->content as $content_line)
    {
      if ($object->$method_name() == $content_line[$this->object_reference_attribute])
      {
        return true;
      }
    }
    
    return false;
  }
  
  public function remove($object)
  {
    $new_content = array();
    $method_name = 'get' . $this->object_reference_attribute;
    foreach ($this->content as $content_line)
    {
      if ($object->$method_name() != $content_line[$this->object_reference_attribute])
      {
        $new_content[] = $content_line;
      }
    }
    
    $this->content = $new_content;
  }
  
  public function getAttributes($attribute)
  {
    if (!in_array($attribute, $this->schema))
      throw new \Exception('This attribute is unknow !');
    
    $attributes = array();
    foreach ($this->content as $content_line)
    {
      $attributes[] = $content_line[$attribute];
    }
    
    return $attributes;
  }
  
  public function getContent()
  {
    return $this->content;
  }
  
}