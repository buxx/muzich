<?php

namespace Muzich\CoreBundle\Searcher;

class ElementSearcher extends Searcher implements SearcherInterface
{
  
  /**
   * Constante définissant si la recherche porte sur le réseau public
   * ou sur le réseau personel de l'utilisateur.
   */
  const NETWORK_PUBLIC = 'network_public';
  const NETWORK_PERSONAL = 'network_personal';
  
  /**
   * Réseau sur lequel porte la recherche
   * 
   * @var string
   */
  protected $network = self::NETWORK_PUBLIC;
  
  /**
   * Liste des tag_ids utilisés lors de la recherche.
   * 
   * @var array
   */
  protected $tags = Array();
  
  /**
   * Nombre limite de résultats retournés.
   * TODO: Placer cette info dans la config.
   * 
   * @var int
   */
  protected $count = 20;
  
  /**
   * @see SearcherInterface
   * @param array $params 
   */
  public function init($params)
  {
    // Control des parametres transmis.
//    $this->checkParams($params, array(
//      'tags' => "Muzich\CoreBundle\Searcher\ElementSearch::init(): \$params: Au moins un tag est nécéssaire"
//    ));
    
    // Mise a jour des attributs
    $this->setAttributes(array(
      'network', 'tags', 'count'
    ), $params);
    
  }
  
  /**
   * @see SearcherInterface
   * @param array $params 
   */
  public function update($params)
  {
    // Mise a jour des attributs
    $this->setAttributes(array(
      'network', 'tags', 'count'
    ), $params);
  }
  
  /**
   * @see SearcherInterface
   * 
   * @return array 
   */
  public function getParams()
  {
    return array(
      'network' => $this->getNetwork(),
      'tags' => $this->getTags(),
      'count' => $this->getCount()
    );
  }
  
  public function getNetwork()
  {
    return $this->network;
  }
  
  public function getTags()
  {
    return $this->tags;
  }
  
  public function getCount()
  {
    return $this->count;
  }
  
}