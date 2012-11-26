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
   * EDIT: Ou les éléments plus récents si $searchnew est a vrai
   * 
   * @var type int
   */
  protected $id_limit = null;
  
  /**
   * Si searchnew est a vrai, c'est que l'on recherche les nouveau éléments 
   * depuis id_limit.
   * 
   * @var type boolean
   */
  protected $searchnew = false;
  
  /**
   * Ce tableaux peut conteni des ids. Si ces ids sont renseignés tout les 
   * autres paramétres ne sont plus pris en compte.
   * Ces ids servent a filtrer directement la liste d'élément a afficher.
   * 
   * @var array
   */
  protected $ids;
  
  /**
   * On stocke la dedans le bouton a afficher dans le gestionnaire de filtres
   * correspondant aux ids filtrés. La valeur doit correspondre a une constante
   * de l'Entité metier Event.
   * 
   * @var string 
   */
  protected $ids_display;
  
  /**
   * Ce booléen permet de savoir si la recherche de tag est stricte.
   * Si elle est stricte chaque tag choisis devrons être attaché au partage
   * pour qu'il soit pris en compte.
   *  
   * @var type boolean
   */
  protected $tag_strict = false;
  
  /**
   * A renseigné pour une recherche portant sur les nom
   * 
   * @var string 
   */
  protected $string = null;
  
  /**
   * Pour la recherche de partage qui demande des tags.
   * 
   * @var boolean 
   */
  protected $need_tags = false;
  
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
    $this->setAttributes($params);
    
  }
  
  /**
   * @see SearcherInterface
   * @param array $params 
   */
  public function update($params)
  {
    // Mise a jour des attributs
    $this->setAttributes($params);
  }
  
  /**
   * @see SearcherInterface
   * 
   * @return array 
   */
  public function getParams($tags_string = false)
  {
    return array(
      'network'     => $this->getNetwork(),
      'tags'        => $this->getTags($tags_string),
      'count'       => $this->getCount(),
      'user_id'     => $this->getUserId(),
      'group_id'    => $this->getGroupId(),
      'favorite'    => $this->isFavorite(),
      'ids'         => $this->getIds(),
      'ids_display' => $this->getIdsDisplay(),
      'tag_strict'  => $this->getTagStrict(),
      'string'      => $this->getString()
    );
  }
  
  public function getNetwork()
  {
    return $this->network;
  }
  
  public function isNetworkPublic()
  {
    if ($this->network == self::NETWORK_PUBLIC)
    {
      return true;
    }
    return false;
  }
  
  public function isNetworkPersonal()
  {
    if ($this->network == self::NETWORK_PERSONAL)
    {
      return true;
    }
    return false;
  }
  
  public function getTags($tags_string = false)
  {
    if (!$tags_string)
    {
      return $this->tags;
    }
    
    $ids = array();
    foreach ($this->tags as $id => $name)
    {
      $ids[] = $id;
    }
    return json_encode($ids);
  }
  
  public function getCount()
  {
    return $this->count;
  }
  
  public function getUserId()
  {
    return $this->user_id;
  }
  
  public function getIdLimit()
  {
    return $this->id_limit;
  }
  
  public function getGroupId()
  {
    return $this->group_id;
  }
  
  public function isFavorite()
  {
    return $this->favorite;
  }
  
  public function setIds($ids)
  {
    $this->ids = $ids;
  }
  
  public function getIds()
  {
    return $this->ids;
  }
  
  public function hasIds()
  {
    if (count($this->ids))
    {
      return true;
    }
    return false;
  }
  
  public function setIdsDisplay($display)
  {
    $this->ids_display = $display;
  }
  
  public function getIdsDisplay()
  {
    return $this->ids_display;
  }
  
  public function setTagStrict($strict)
  {
    $this->tag_strict = $strict;
  }
  
  public function getTagStrict()
  {
    return $this->tag_strict;
  }
  
  public function setString($string)
  {
    $this->string = $string;
  }
  
  public function getString()
  {
    return $this->string;
  }

  /**
   * Construction de l'objet Query
   *
   * @param Registry $doctrine
   * @param int $user_id
   * @param string $exec_type
   * 
   * @return collection
   */
  protected function constructQueryObject(Registry $doctrine, $user_id, $exec_type = 'execute', $params = array())
  {
    $this->setQuery($doctrine
      ->getRepository('MuzichCoreBundle:Element')
      ->findBySearch($this, $user_id, $exec_type, $params))
    ;
  }
  
  /**
   * Retourne l'objet Query
   * 
   * @param Registry $doctrine
   * @param int $user_id
   * @param string $exec_type
   * 
   * @return collection
   */
  public function getQuery(Registry $doctrine, $user_id, $exec_type = 'execute', $params = array())
  {
    $this->constructQueryObject($doctrine, $user_id, $exec_type, $params);
    return $this->query;
  }

  /**
   * Retourne les elements correspondant a la recherche
   * user_id: Identifiant de celui qui navigue
   * 
   * @param Registry $doctrine
   * @param int $user_id
   * @param string $exec_type Type d'execution
   * 
   * @return collection
   */
  public function getElements(Registry $doctrine, $user_id, $exec_type = 'execute', $params = array())
  {
    $query = $this->getQuery($doctrine, $user_id, $exec_type, $params);
    
    switch ($exec_type)
    {
      case 'execute':
        return $query->execute();
      break;
    
      case 'count':
        return count($query->getArrayResult());
      break;
    
      case 'single':
        return $query->getSingleResult();
      break;
    
      default :
        throw new \Exception('Mode de récupération des Elements non supporté.');
      break;
    }
  }
  
  public function isSearchingNew()
  {
    return $this->searchnew;
  }
  
  public function isNeedTags()
  {
    if ($this->need_tags)
    {
      return true;
    }
    return false;
  }
  
}