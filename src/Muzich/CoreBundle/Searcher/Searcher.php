<?php

namespace Muzich\CoreBundle\Searcher;

use Doctrine\ORM\Query;
use Symfony\Bundle\DoctrineBundle\Registry;

/**
 * Objet utiliser pour cadrer la recherche.
 * 
 */
abstract class Searcher
{
  /**
   * Query est l'objet requete correspondant a la recherche
   *
   * @var Query
   */
  protected $query = null;

  protected function checkParams($params, $neededs)
  {
    foreach ($neededs as $config_id => $message)
    {
      if (!array_key_exists($config_id, $params))
      {
        throw new \Exception($message);
      }
      elseif (empty($params[$config_id]))
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

  protected function setQuery(Query $query)
  {
    $this->query = $query;
  }

  public function getQuery(Registry $doctrine, $user_id)
  {
    return $this->query;
  }

}
