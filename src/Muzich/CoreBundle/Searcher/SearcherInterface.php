<?php

namespace Muzich\CoreBundle\Searcher;

/**
 * Interface pour les classes de recherche.
 * 
 */
interface SearcherInterface
{
  
  /**
   * Initialisation de l'objet recherche.
   */
  public function init($params);
  
  /**
   * Mise a jour des composant de la recherche.
   */
  public function update($params);
  
  /**
   * Récupération des paramètres
   */
  public function getParams();
  
}
