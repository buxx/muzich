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
  
//  /**
//   * Procédure qui construit la requete.
//   */
//  public function constructQueryObject();
//  
//  /**
//   * Récupération de l'objet requete, pour ajouter des JOIN 
//   * par exemple.
//   */
//  public function getQueryObject();
//  
//  /**
//   * Récupération des résultats.
//   */
//  public function getResults();
  
}
