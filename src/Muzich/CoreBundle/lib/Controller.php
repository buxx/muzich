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
   * Met a jour l'objet ElementSearcher (en réallité on met a jour les
   * paramètres en sessions).
   * 
   * @param ElementSearcher $es 
   */
  protected function setElementSearcher(ElementSearcher $es)
  {
    $this->get("session")->set('user.element_search.params', $es->getParams());
  }
  
  /**
   * Retourn l'objet ElementSearcher en cours.
   * 
   * @param int $user_id
   * @return  ElementSearcher
   */
  protected function getElementSearcher($user_id)
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
        ->getTagIdsFavorites($user_id, 3)
      ));

      // Et on met en session les paramètres
      $session->set('user.element_search.params', $this->ElementSearcher->getParams());
    }
    else
    {
      // Des paramètres existes, on fabrique l'objet recherche
      $this->ElementSearcher = new ElementSearcher();
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
      if ($force_refresh || !self::$user)
      {
        self::$user = $this->container->get('security.context')->getToken()->getUser();
        return self::$user;
      }
      return self::$user;
    }
    else
    {
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
    $this->getUser()->getId();
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
  
  protected function setFlash($type, $value)
  {
    $this->container->get('session')->setFlash($type, $value);
  }
  
}
