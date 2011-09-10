<?php

namespace Muzich\CoreBundle\Searcher;

/**
 * Objet utiliser pour cadrer la recherche.
 * 
 */
class Searcher
{
  protected function checkParams($params, $neededs)
  {
    foreach ($neededs as $config_id => $message)
    {
      if (!array_key_exists($config_id, $params))
      {
        throw new \Exception($message);
      }
    }
  }
  
  protected function setAttributes($params_ids, $params)
  {
    foreach ($params_ids as $param_id)
    {
      if (array_key_exists($param_id, $params))
      {
        $this->$param_id = $params[$param_id];
      }
    }
  }
}
