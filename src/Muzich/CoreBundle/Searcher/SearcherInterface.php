<?php

namespace Muzich\CoreBundle\Searcher;

/**
 * Interface pour les classes de recherche.
 * 
 */
interface SearcherInterface
{
  
  public function setString($string);
  public function getString();
    
  /**
   * Récupération des paramètres
   */
  public function getParams();
  
}
