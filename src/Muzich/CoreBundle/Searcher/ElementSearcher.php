<?php

namespace Muzich\CoreBundle\Searcher;

use Symfony\Bundle\DoctrineBundle\Registry;

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
   * 
   * @var int
   */
  protected $count = 20;
  
  /**
   * Id de l'user si on limite la recherche a un utilisateur
   * 
   * @var int
   */
  protected $user_id = null;
  
  /**
   * Id du groupe si on limite la recherche a un groupe
   * 
   * @var int
   */
  protected $group_id = null;
  
  /**
   * Est-ce les favoris qui sont recherché
   * 
   * @var boolean 
   */
  protected $favorite = false;
  
  /**
   * Si id_limit est renseigné c'est que l'on veut trouver les elements
   * plus vieux (ont utilise l'id comme référence) que l'id_limi passé.
   * 
   * @var type int
   */
  protected $id_limit = null;
  
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
      'network', 'tags', 'count', 'user_id', 'group_id', 'favorite', 'id_limit'
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
      'network', 'tags', 'count', 'user_id', 'group_id', 'favorite', 'id_limit'
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
      'network'  => $this->getNetwork(),
      'tags'     => $this->getTags(),
      'count'    => $this->getCount(),
      'user_id'  => $this->getUserId(),
      'group_id' => $this->getGroupId(),
      'favorite' => $this->isFavorite()
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
  
  public function getUserId()
  {
    return $this->user_id;
  }
  
  public function getGroupId()
  {
    return $this->group_id;
  }
  
  public function isFavorite()
  {
    return $this->favorite;
  }

  /**
   * Construction de l'objet Query
   *
   * @param Registry $doctrine
   * @return collection
   */
  protected function constructQueryObject(Registry $doctrine, $user_id)
  {
    $this->setQuery($doctrine
      ->getRepository('MuzichCoreBundle:Element')
      ->findBySearch($this, $user_id))
    ;
  }
  
  /**
   * Retourne l'objet Query
   * 
   * @param Registry $doctrine
   * @return collection
   */
  public function getQuery(Registry $doctrine, $user_id)
  {
    if (!$this->query)
    {
      $this->constructQueryObject($doctrine, $user_id);
    }
    return $this->query;
  }

  /**
   * Retourne les elements correspondant a la recherche
   * user_id: Identifiant de celui qui navigue
   * 
   * @param Registry $doctrine
   * @param int $user_id
   * @return collection
   */
  public function getElements(Registry $doctrine, $user_id)
  {
    return $this->getQuery($doctrine, $user_id)->execute();
  }
  
}