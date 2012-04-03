<?php

namespace Muzich\CoreBundle\lib;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Muzich\CoreBundle\Searcher\ElementSearcher;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Muzich\CoreBundle\Form\Search\ElementSearchForm;
use Muzich\CoreBundle\Form\Element\ElementAddForm;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
  
  protected static $user = null;
  protected static $user_personal_query = null;
  protected static $tags = array();
  
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
   * @desc Retourn l'objet ElementSearcher en cours.
   * 
   * @param int $count Si renseigné impact le nombre d'éléments qui seront 
   * récupérés
   * @param boolean $force_new Si a vrai la méthode procéède comme si on 
   * demandé un nouveau objet de recherche (basé sur les tags favoris donc).
   * 
   * @return  ElementSearcher
   */
  protected function getElementSearcher($count = null, $force_new = false)
  {
    $session = $this->get("session");
    // Si l'objet n'existe pas encore, a t-on déjà des paramètres de recherche
    if (!$session->has('user.element_search.params') || $force_new)
    {
      // Il nous faut instancier notre premier objet recherche
      // Premièrement on récupère les tags favoris de l'utilisateur
      $this->ElementSearcher = new ElementSearcher();
      $this->ElementSearcher->init(array(
        'tags' => $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
          ->getTagsFavorites(
            $this->getUserId(),
            $this->container->getParameter('search_default_favorites_tags_count')
          ),
        'count' => ($count)?$count:$this->container->getParameter('search_default_count')
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
      if ($count)
      {
        $this->ElementSearcher->update(array('count' => $count));
      }
    }
    
    // on le retourne
    return $this->ElementSearcher;
  }
  
  /**
   * Retourne l'objet User. Il est possible de préciser de quel manière récupérer 
   * l'utilisateur:
   * 
   * $user = $this->getUser(true, array('join' => array(
   *   'groups_owned'
   * )));
   * 
   * ou de forcer sa (re)récupération en base (sinon c'est l'objet static qui est renvoyé)
   * 
   * @param boolean $personal_query
   * @param array $params
   * @param boolean $force_refresh
   * @return type 
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
    /**
     * Bug lors des tests: L'user n'est pas 'lié' a celui en base par doctrine.
     * Docrine le voit si on faire une requete directe.
     */
    if ($this->container->getParameter('env') == 'test')
    {
      $user = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneById(
        $this->container->get('security.context')->getToken()->getUser()->getId(),
        array()
      )->getSingleResult();
      return $user->getId();
    }
    
    if (($user = $this->getUser()) != 'anon.')
    {
      return $user->getId();
    }
    throw new \Exception('User not connected');
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
  protected function getTagsArray($force_refresh = false)
  {
    throw new \Exception("Cette méthode ne doit plus être utilisé.");
    
    if (!count(self::$tags) || $force_refresh)
    {
      return self::$tags = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->getTagsArray();
    }
    
    return self::$tags;
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
  
  /**
   * Retourne un Group en fonction du id passé
   * 
   * @param string $slug
   * @return Group 
   */
  protected function findGroupWithId($id)
  {
    try {
      return $this->getDoctrine()
        ->getRepository('MuzichCoreBundle:Group')
        ->findOneById($id)
      ;      
    } catch (\Doctrine\ORM\NoResultException $e) {
        throw $this->createNotFoundException('Groupe introuvable.');
    }
  }
  
  /**
   * Retourne le formulaire de recherche
   * 
   * @param \Muzich\CoreBundle\Searcher\Searcher $search_object
   * @return \Symfony\Component\Form\Form
   */
  protected function getSearchForm($search_object)
  {
    return $this->createForm(
      new ElementSearchForm(), 
      $search_object->getParams(true),
      array()
    );
  }
  
  /**
   * Retourne le formulaire d'ajout d'élément
   * 
   * @param \Muzich\CoreBundle\Searcher\Searcher $search_object
   * @return \Symfony\Component\Form\Form
   */
  protected function getAddForm($element = array(), $name = null)
  {
    $form = new ElementAddForm();
    $form->setName($name);
    return $this->createForm(
      $form,
      $element,
      array()
    );
  }
  
  /**
   * Retourne une réponse contenant et de type json
   * 
   * @param array $content
   * @return Response 
   */
  protected function jsonResponse($content)
  {
    $response = new Response(json_encode($content));
    $response->headers->set('Content-Type', 'application/json; charset=utf-8');
    return $response;
  }
  
  /**
   * Permet d'utiliser la méthode Assert que l'on utilise dans les templates
   * afin d'avoir une url correcte vers une ressource web (img, js, ...)
   * 
   * @param string $path
   * @param string $packageName
   * @return string 
   */
  protected function getAssetUrl($path, $packageName = null)
  {
    return $this->container->get('templating.helper.assets')->getUrl($path, $packageName);
  }
  
  /**
   * Retourne une traduction effectué par le translator
   * 
   * @param string $string
   * @param array $params
   * @param string $package
   * @return string 
   */
  protected function trans($string, $params = array(), $package = null)
  {
    return $this->get('translator')->trans($string, $params, $package);
  }
  
  /**
   * Permet de récupérer un objet réponse si l'utilisateur doit être connecté
   * pour accéder a cette ressource. On peux préciser $and_ajax pour que
   * la requete de type ajax soit une nécéssité.
   * 
   * @return Response
   */
  protected function mustBeConnected($and_ajax = false)
  {
    if ($and_ajax && !$this->getRequest()->isXmlHttpRequest())
    {
      throw $this->createNotFoundException('Ressource ajax uniquement.');
    }
    
    if ($this->getUser() == 'anon.')
    {
      $this->setFlash('info', 'user.session_expired');
      
      if ($this->getRequest()->isXmlHttpRequest())
      {
        return $this->jsonResponse(array(
          'status' => 'mustbeconnected'
        ));
      }
      else
      {
        return $this->redirect($this->generateUrl('index'));
      }
    }
  }
  
}
