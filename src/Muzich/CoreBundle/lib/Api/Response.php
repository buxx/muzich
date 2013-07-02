<?php

namespace Muzich\CoreBundle\lib\Api;

class Response
{
  
  protected $content = array();
  
  public function __construct($content = array())
  {
    $this->content = $content;
  }
  
  public function haveNot($searched)
  {
    return !$this->have($searched);
  }
  
  public function have($searched, $not_empty = true, $content = array())
  {
    if (!$content || !count($content))
      $content = $this->content;
    
    if (is_array($searched))
    {
      foreach ($searched as $searched_key => $searched_subvalue)
      {
        return $this->have($searched_subvalue, $not_empty, $this->get($searched_key, false, $content));
      }
    }
    
    if ($content)
    {
      if (array_key_exists($searched, $content))
      {
        if ($not_empty)
        {
          if ((is_null($content[$searched]) || !count($content[$searched]) || !$content[$searched]) && ($content[$searched] !== 0 && $content[$searched] !== '0'))
          {
            return false;
          }
          if (is_string($content[$searched]))
          {
            if (trim($content[$searched]) == '')
            {
              return false;
            }
          }
        }

        return true;
      }
    }
    
    return false;
  }
  
  public function get($searched, $return_null_if_empty = true, $content = null)
  {
    if (!$content)
      $content = $this->content;
    
    if (is_array($searched))
    {
      foreach ($searched as $searched_key => $searched_subvalue)
      {
        if ($this->have($searched_key, true, $content))
        {
          return $this->get($searched_subvalue, $return_null_if_empty, $this->get($searched_key, $return_null_if_empty, $content));
        }
        else
        {
          
          return null;
        }
      }
    }
    
    if ($this->have($searched, $return_null_if_empty, $content))
    {
      return $content[$searched];
    }
    
    return null;
  }
  
  public function getContent()
  {
    return $this->content;
  }
  
}