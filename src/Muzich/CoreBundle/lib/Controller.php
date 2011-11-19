<?php

namespace Muzich\CoreBundle\lib;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Muzich\CoreBundle\Searcher\ElementSearcher;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class Controller extends BaseController
{
  
  protected static $user = null;
  protected static $user_personal_query = null;
  
  /**
   * Authenticate a user with Symfony Security
   *
   */
  protected function authenticateUser($user)
  {
    $providerKey = $this->container->getParameter('fos_user.firewall_name');
    $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

    $this->container->get('security.context')->setToken($token);
  }
    
  /**
   * Met a jour les parametres de ElementSearcher pour la "mémoire" de la
   * recherche
   * 
   * @param array $params
   */
  protected function setElementSearcherParams($params)
  {
    $this->get("session")->set('user.element_search.params', $params);
  }
  
  /**
   * Retourn l'objet ElementSearcher en cours.
   * 
   * @return  ElementSearcher
   */
  protected function getElementSearcher()
  {
    $session = $this->get("session");
    // Si l'objet n'existe pas encore, a t-on déjà des paramètres de recherche
    if (!$session->has('user.element_search.params'))
    {
      // Il nous faut instancier notre premier objet recherche
      // Premièrement on récupère les tags favoris de l'utilisateur
      $this->ElementSearcher = new ElementSearcher();
      $this->ElementSearcher->init(array(
        'tags' => $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
        // TODO: 3: CONFIG !!
        ->getTagIdsFavorites($this->getUserId(), 3)
      ));

      // Et on met en session les paramètres
      $session->set('user.element_search.params', $this->ElementSearcher->getParams());
    }
    else
    {
      // Des paramètres existes, on fabrique l'objet recherche
      $this->ElementSearcher = new ElementSearcher();
      // et on l'initatialise avec ces paramétres connus
      $this->ElementSearcher->init($session->get('user.element_search.params'));
    }
    
    // on le retourne
    return $this->ElementSearcher;
  }
  
  /**
   * Retourne l'objet User.
   * 
   * @param array $params
   * @return User
   */
  protected function getUser($personal_query = false, $params = array(), $force_refresh = false)
  {
    if (!$personal_query)
    {
      // Si on demande l'utilisateur sans forcer la réactualisation et que l'utilisateur
      // a déjà été demandé mais avec un requête personelle, on retourne cet utilisateur
      if (!$force_refresh && self::$user_personal_query)
      {
        return self::$user_personal_query;
      }
      // Si on demande une actualisation ou que l'utilisateur n'a pas encore été demandé
      // on va le récupérer
      else if ($force_refresh || !self::$user)
      {
        self::$user = $this->container->get('security.context')->getToken()->getUser();
        return self::$user;
      }
      return self::$user;
    }
    else
    {
      // Si l'on demande une réactualisation ou si l'user n'a pas encore été demandé
      // on va le récupérer en base.
      if ($force_refresh || !self::$user_personal_query)
      {
        self::$user_personal_query = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneById(
          $this->container->get('security.context')->getToken()->getUser()->getId(),
          array_key_exists('join', $params) ? $params['join'] : array()
        )->getSingleResult();
        return self::$user_personal_query;
      }
      return self::$user_personal_query;
    }
  }
  
  /**
   * @desc Retourne l'id de l'utilisateur en cours
   */
  protected function getUserId()
  {
    return $this->getUser()->getId();
  }
  
  /**
   * Retourne un tabeau avec les tags connus.
   * TODO: Voir pour que cette info soit stocké (par exemple) dans un champs
   * texte en base. (json array)
   * TODO2: Voir si la question d'opt. "Formulaire d'ajout d'un élément" ne résoue pas
   * le problème du TODO ci-dessus.
   * 
   * @return array
   */
  protected function getTagsArray()
  {
    return $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->getTagsArray();
  }
  
  /**
   * Retourne un tabeau avec les groupes accessible pour un ajout d'element.
   * 
   * @return array
   */
  protected function getGroupsArray()
  {
    return $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')
      ->getPublicAndOwnedArray($this->getUserId());
  }
  
  /**
   * Met en place un message de type flash.
   * 
   * @param string $type
   * @param string $value 
   */
  protected function setFlash($type, $value)
  {
    $this->container->get('session')->setFlash($type, $value);
  }
  
  /**
   * Instancie et retourne un objet ElementSearch
   * 
   * @param array $params
   * @return ElementSearcher 
   */
  protected function createSearchObject($params)
  {
    $search_object = new ElementSearcher();
    $search_object->init($params);
    return $search_object;
  }
  
  /**
   * Retourne un User en fonction du slug passé
   * 
   * @param string $slug
   * @return User 
   */
  protected function findUserWithSlug($slug)
  {
    try {
      return $this->getDoctrine()
        ->getRepository('MuzichCoreBundle:User')
        ->findOneBySlug($slug)
        ->getSingleResult()
      ;      
    } catch (\Doctrine\ORM\NoResultException $e) {
        throw $this->createNotFoundException('Utilisateur introuvable.');
    }
  }
  
  /**
   * Retourne un Group en fonction du slug passé
   * 
   * @param string $slug
   * @return Group 
   */
  protected function findGroupWithSlug($slug)
  {
    try {
      return $this->getDoctrine()
        ->getRepository('MuzichCoreBundle:Group')
        ->findOneBySlug($slug)
        ->getSingleResult()
      ;      
    } catch (\Doctrine\ORM\NoResultException $e) {
        throw $this->createNotFoundException('Groupe introuvable.');
    }
  }
  
}
