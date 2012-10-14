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
  
  protected function setAttributes($params)
  {
    foreach ($params as $param_id => $param_value)
    {
      // TODO: check existance attribut
      if (property_exists($this, $param_id))
      {
        $this->$param_id = $param_value;
      }
      else
      {
        throw new \Exception("You're trying access unknow attribute '$param_id'");
      }
    }
  }

  protected function setQuery(Query $query)
  {
    $this->query = $query;
  }

  public function getQuery(Registry $doctrine, $user_id, $exec_type)
  {
    return $this->query;
  }

}
