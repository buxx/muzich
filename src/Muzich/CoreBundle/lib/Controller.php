<?php

namespace Muzich\CoreBundle\lib;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Muzich\CoreBundle\Searcher\ElementSearcher;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Muzich\CoreBundle\Form\Search\ElementSearchForm;
use Muzich\CoreBundle\Form\Element\ElementAddForm;
use Symfony\Component\HttpFoundation\Response;
use Muzich\CoreBundle\Searcher\GlobalSearcher;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Entity\Presubscription;
use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Security\Context as SecurityContext;

class Controller extends BaseController
{
  
  protected static $user = null;
  protected static $user_personal_query = null;
  protected static $tags = array();
  /** @var SecurityContext */
  protected $security_context;
  
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
  protected function setElementSearcherParams($params, $session_id = '')
  {
    if ($session_id != '')
    {
      $session_id = '.'.$session_id;
    }
    
    if (!count($params['tags']))
    {
      if (count($this->getElementSearcher()->getTags()))
      {
        $this->get("session")->set('user.element_search.last_tags', $this->getElementSearcher()->getTags());
      }
    }
    
    $this->get("session")->set('user.element_search.params'.$session_id, $params);
  }
  
  protected function isVisitor()
  {
    $user = $this->getUser();
    if ($user === 'anon.')
    {
      return true;
    }
    elseif ($user instanceof User)
    {
      return false;
    }
    
    throw new \Exception('Unable to determine user type');
  }
  
  /**
   * Retourn l'objet ElementSearcher en cours.
   * 
   * @param int $count Si renseigné impact le nombre d'éléments qui seront 
   * récupérés
   * @param boolean $force_new Si a vrai la méthode procéède comme si on 
   * demandé un nouveau objet de recherche (basé sur les tags favoris donc).
   * 
   * @return  ElementSearcher
   */
  protected function getElementSearcher($count = null, $force_new = false, $session_id = '')
  {
    $session = $this->get("session");
    if ($session_id != '')
    {
      $session_id = '.'.$session_id;
    }
    // Si l'objet n'existe pas encore, a t-on déjà des paramètres de recherche
    if (!$session->has('user.element_search.params'.$session_id) || $force_new)
    {
      // Il nous faut instancier notre premier objet recherche
      // Premièrement on récupère les tags favoris de l'utilisateur
      $this->ElementSearcher = new ElementSearcher();
      $this->ElementSearcher->init(array(
        'tags' => $this->getUserFavoriteTags(),
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
      $this->ElementSearcher->init($session->get('user.element_search.params'.$session_id));
      if ($count)
      {
        $this->ElementSearcher->update(array('count' => $count));
      }
    }
    
    // on le retourne
    return $this->ElementSearcher;
  }
  
  protected function getUserFavoriteTags()
  {
    if (!$this->isVisitor())
    {
      return $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
        ->getTagsFavorites(
          $this->getUserId(),
          $this->container->getParameter('search_default_favorites_tags_count')
        )
      ;
    }
    
    return array();
  }
  
  protected function getNewElementSearcher()
  {
    return $this->getElementSearcher(null, true);
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
   * @return \Muzich\CoreBundle\Entity\User 
   */
  public function getUser($personal_query = false, $params = array(), $force_refresh = false)
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
        $user = $this->container->get('security.context')->getToken()->getUser();
        if ($user !== 'anon.')
        {
          self::$user_personal_query = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneById(
            $this->container->get('security.context')->getToken()->getUser()->getId(),
            array_key_exists('join', $params) ? $params['join'] : array()
          )->getSingleResult();
          return self::$user_personal_query;
        }
        else
        {
          return 'anon.';
        }
      }
      return self::$user_personal_query;
    }
  }
  
  /**
   *  Retourne l'id de l'utilisateur en cours
   */
  protected function getUserId($return_null_if_visitor = false)
  {
    
    /**
     * Bug lors des tests: L'user n'est pas 'lié' a celui en base par doctrine.
     * Docrine le voit si on faire une requete directe.
     */
    if ($this->container->getParameter('env') == 'test')
    {
      $user_context = $this->container->get('security.context')->getToken()->getUser();
      
      if ($user_context !== 'anon.')
      {
        $user = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneById(
          $user_context,
          array()
        )->getSingleResult();
      }
    }
    else
    {
      $user = $this->getUser();
    }
    
    if ($user !== 'anon.')
    {
      return $user->getId();
    }
    
    if ($return_null_if_visitor)
    {
      return null;
    }
    
    throw new \Exception('User not connected');
  }
  
  protected function getUserRefreshed()
  {
    return $this->getUser(false, array(), true);
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
   * Retourne l'objet Form du formulaire de recherche global.
   * 
   * @return \Symfony\Component\Form\Form 
   */
  protected function getGlobalSearchForm($searcher = null)
  {
    if ($searcher === null)
    {
      $searcher = new GlobalSearcher();
    }
    
    return $this->createFormBuilder($searcher)
      ->add('string', 'text')
    ->getForm();
  }
  
  /**
   * Retourne le formulaire d'ajout d'élément
   * 
   * @param \Muzich\CoreBundle\Searcher\Searcher $search_object
   * @return \Symfony\Component\Form\Form
   */
  protected function getAddForm($element = array(), $name = null)
  {
    //$form = new ElementAddForm();
    //$form->setName($name);
    //return $this->createForm(
    //  $form,
    //  $element,
    //  array()
    //);
  
    $form = new ElementAddForm();
    $form->setName($name);
    return $this->createForm($form, $element);
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
  
  protected function jsonResponseError($error_type, $error_content = array())
  {
    return $this->jsonResponse(array(
      'status' => 'error',
      'error'  => $error_type,
      'data'   => $error_content
    ));
  }
  
  protected function jsonNotFoundResponse()
  {
    $response = new Response(json_encode(array(
      'status' => 'error',
      'errors' => array('NotFound')
    )));
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
      $this->setFlash('error', 'user.session_expired');
      
      if ($this->getRequest()->isXmlHttpRequest())
      {
        return $this->jsonResponse(array(
          'status' => 'mustbeconnected'
        ));
      }
      else
      {
        return $this->redirect($this->generateUrl('home'));
      }
    }
  }
  
  /**
   *
   * @return \Doctrine\ORM\EntityManager 
   */
  public function getEntityManager()
  {
    return $this->getDoctrine()->getEntityManager();
  }
  
  /**
   *
   * @param object $entity 
   */
  public function persist($entity)
  {
    $this->getEntityManager()->persist($entity);
  }
  
  /**
   *
   * @param object $entity 
   */
  public function remove($entity)
  {
    $this->getEntityManager()->remove($entity);
  }
  
  /**
   * 
   */
  public function flush()
  {
    $this->getEntityManager()->flush();
  }
  
  
  
  /**
   * Cette méthode vérifie si l'élément qui vient d'être envoyé pourrais être
   * associé a un groupe de l'utilisateur.
   * 
   * @param Element $element
   * @return array
   */
  protected function isAddedElementCanBeInGroup(Element $element)
  {
    $element_tags = $element->getTags();
    $groups = array();
    
    if ($element_tags)
    {
      foreach ($this->getUser()->getGroupsOwned() as $group)
      {
        foreach ($element_tags as $element_tag)
        {
          if ($group->hasThisTag($element_tag->getId()))
          {
            $groups[] = array(
              'name' => $group->getName(),
              'id'   => $group->getId(),
              'url'  => $this->generateUrl('ajax_set_element_group', array(
                'token'      => $this->getUser()->getPersonalHash($element->getId()),
                'element_id' => $element->getId(),
                'group_id'   => $group->getId()
              ))
            );
          }

        }
      }
    }
    
    return $groups;
  }
  
  protected function getPreSubscriptionForm()
  {
    return $this->createFormBuilder(new Presubscription())
      ->add('email', 'email')
      ->getForm()
    ;
  }
  
  protected function getDisplayAutoplayBooleanForContext($context)
  {
    if (in_array($context, 
      $this->container->getParameter('autoplay_contexts')
    ))
    {
      return true;
    }
    return false;
  }
  
  protected function sendEmailconfirmationEmail($set_send_time = true)
  {
    $user = $this->getUserRefreshed();
    
    $tokenGenerator = $this->container->get('fos_user.util.token_generator');
    $user->setConfirmationToken($tokenGenerator->generateToken());
    if ($set_send_time)
      $user->setEmailConfirmationSentTimestamp(time());
    
    $token = hash('sha256', $user->getConfirmationToken().$user->getEmail());
    $url = $this->get('router')->generate('email_confirm', array('token' => $token), true);
    $rendered = $this->get('templating')->render('MuzichUserBundle:User:confirm_email_email.txt.twig', array(
      'confirmation_url' => $url
    ));
    
    //$this->sendEmailMessage($rendered, $this->parameters['from_email']['resetting'], $user->getEmail());
    
    // Render the email, use the first line as the subject, and the rest as the body
    $renderedLines = explode("\n", trim($rendered));
    $subject = $renderedLines[0];
    $body = implode("\n", array_slice($renderedLines, 1));

    $message = \Swift_Message::newInstance()
      ->setSubject($subject)
      ->setFrom('contact@muzi.ch')
      ->setTo($user->getEmail())
      ->setBody($body);
    $message->getHeaders()->addTextHeader('List-Unsubscribe', 'unsubscribe@muzi.ch');

    $mailer = $this->get('mailer');
    $mailer->send($message);
    
    $this->persist($user);
    $this->flush();
  }
  
  protected function getParameter($key)
  {
    return $this->container->getParameter($key);
  }
  
  protected function userHaveNonConditionToMakeAction($action)
  {
    $secutity_context = $this->getSecurityContext();
    if (($condition = $secutity_context->actionIsAffectedBy(SecurityContext::AFFECT_CANT_MAKE, $action)) !== false)
    {
      return $condition;
    }
    
    return false;
  }
  
  /** @return SecurityContext */
  protected function getSecurityContext()
  {
    if ($this->security_context == null)
      $this->security_context = new SecurityContext($this->getUser());
    
    return $this->security_context;
  }
  
}
