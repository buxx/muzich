<?php

namespace Muzich\CoreBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Muzich\CoreBundle\Entity\FollowUser;
use Muzich\CoreBundle\Entity\FollowGroup;
//use Doctrine\ORM\Query;
use Muzich\CoreBundle\Form\Element\ElementAddForm;
use Muzich\CoreBundle\Managers\ElementManager;
use Muzich\CoreBundle\Entity\Element;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Muzich\CoreBundle\Form\Search\ElementSearchForm;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Muzich\CoreBundle\Entity\Tag;
use Muzich\CoreBundle\Managers\TagManager;
use Muzich\CoreBundle\Entity\UsersTagsFavorites;
use Muzich\CoreBundle\Managers\ElementReportManager;
use Muzich\CoreBundle\Propagator\EventUser;
use Muzich\CoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class CoreController extends Controller
{
  
  /**
   * Action permettant de changer le language
   *
   * @param string $language
   * @return RedirectResponse
   */
  public function changeLanguageAction($language)
  {
    if($language != null)
    {
      $old = $this->get('session')->getLocale();
      $this->get('session')->setLocale($language);
    }
    
    $url_referer = $this->container->get('request')->headers->get('referer');
    $url_referer = str_replace(
      $siteurl = $this->container->getParameter('siteurl'), 
      '', 
      $url_referer
    );
    
    try {
      $params = $this->get('router')->match($url_referer);
    } catch (ResourceNotFoundException $exc) {
      return $this->redirect($this->generateUrl('home', array('_locale' => $language)));
    }

    $params['_locale'] = $language;
    $route = $params['_route'];
    unset($params['_route'], $params['_controller']);
    $new_url = $this->generateUrl($route, $params);
    
    return new RedirectResponse($new_url);
  }
  
  /**
   *  Determiner la locale automatiquement
   * @return string 
   */
  protected function determineLocale()
  {
    $lang = $this->container->get('request')
      ->getPreferredLanguage($this->container->getParameter('supported_langs')); 
    
    // Si on a une lang en sortie, 
    if (is_null($lang))
    {
      // TODO: Récupérer ce paramètre dans la config
      $lang = 'fr';
    }
    
    return $lang;
  }
  
  /**
   * 
   * Cette action est écrite pour les utilisateur redirigé du a l'absence de 
   * lague dans leur route.
   * Cette redirection n'est pas interne au code, elle est actuellement effectué
   * par le .htaccess lorsque il n'y as pas d'url (en plus de muzi.ch/
   */
  public function automaticLanguageAction()
  {
    $lang = $this->determineLocale();
    if ($this->getUser() != 'anon.')
    {
      return $this->redirect($this->generateUrl('home', array('_locale' => $lang)));
    }
    else
    {
      return $this->redirect($this->generateUrl('index', array('_locale' => $lang)));
    }
  }
  
  /**
   * Cette action permet a un utilisateur de suivre ou de ne plus suivre
   * un utilisateur ou un groupe.
   * 
   * @param string $type
   * @param int $id
   * @param string $salt 
   */
  public function followAction($type, $id, $token)
  {
    if (($response = $this->mustBeConnected()))
    {
      return $response;
    }
    
    $user = $this->getUser();
    
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
    }
    
    // Vérifications préléminaires
    if ($user->getPersonalHash() != $token 
        || !in_array($type, array('user', 'group')) 
        || !is_numeric($id)
        || ($user->getId() == $id && $type == 'user')
    )
    {
      throw $this->createNotFoundException();
    }

    // On tente de récupérer l'enregistrement FollowUser / FollowGroup
    $em = $this->getDoctrine()->getEntityManager();
    $Follow = $em
      ->getRepository('MuzichCoreBundle:Follow' . ucfirst($type))
      ->findOneBy(
        array(
          'follower' => $user->getId(),
          ($type == 'user') ? 'followed' : 'group' => $id
        )
      )
    ;

    // Si il existe déjà c'est qu'il ne veut plus suivre
    if ($Follow)
    {
      if ($type == 'user')
      {
        // L'utilisateur suis déjà, on doit détruire l'entité
        $event = new EventUser($this->container);
        $event->removeFromFollow($Follow->getFollowed());
        $em->persist($Follow->getFollowed());
      }
      
      $em->remove($Follow);
      $em->flush();
      $following = false;
    }
    // Sinon, c'est qu'il veut le suivre
    else
    {
      // On récupére l'entité a suivre
      $followed = $em->getRepository('MuzichCoreBundle:'.ucfirst($type))->find($id);

      if (!$followed) {
          throw $this->createNotFoundException('No '.$type.' found for id '.$id);
      }
      
      // On instancie te renseigne l'objet Follow****
      if ($type == 'user') { $Follow = new FollowUser(); }
      else { $Follow = new FollowGroup(); }
      $Follow->setFollower($user);
      if ($type == 'user')
      { 
        $Follow->setFollowed($followed); 
        
        $event = new EventUser($this->container);
        $event->addToFollow($followed, $this->getUser());
        $em->persist($followed);
      }
      else { $Follow->setGroup($followed); }
      
      $em->persist($Follow);
      $em->flush();
      $following = true;
    }
    
    if ($this->getRequest()->isXmlHttpRequest())
    {
      return $this->jsonResponse(array(
        'status'    => 'success',
        'following' => $following
      ));
    }
    else
    {
      return $this->redirect($this->container->get('request')->headers->get('referer'));
    }
  }

  /**
   *  Procédure d'ajout d'un element
   */
  public function elementAddAction($group_slug)
  {
    if (($response = $this->mustBeConnected()))
    {
      return $response;
    }
    
    if ($this->getRequest()->getMethod() != 'POST')
    {
      throw $this->createNotFoundException('Cette ressource n\'est pas accessible');
    }
    
    $user = $this->getUser(true, array('join' => array('groups_owned_groups_tags')));
    $em = $this->getDoctrine()->getEntityManager();
    
    /*
     * Contrôle préléminaire si groupe précisé
     */
    $group = null;
    if ($group_slug)
    {
      $group = $this->findGroupWithSlug($group_slug);
      if (!$group->userCanAddElement($this->getUserId()))
      {
        $group = null;
        throw  $this->createNotFoundException('Vous ne pouvez pas ajouter d\'éléments a ce groupe');
      }
    }
        
    $element = new Element();
    $element->setType('none');
    $form = $this->getAddForm($element);
    $form->bindRequest($this->getRequest());
        
    if ($form->isValid())
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
      }

      // On utilise le gestionnaire d'élément
      $factory = new ElementManager($element, $em, $this->container);
      $factory->proceedFill($user);

      // Si on a précisé un groupe dans lequel mettre l'element
      if ($group)
      {
        $element->setGroup($group);
        $redirect_url = $this->generateUrl('show_group', array('slug' => $group_slug));
      }
      else
      {
        $redirect_url = $this->generateUrl('home');
      }
      
      // Un bug fait que le champ 'need_tags' n'est pas automatiquement renseigné pour l'élement,
      // Alors on le fait en manuel ici ... zarb
      $form_values = $this->getRequest()->get($form->getName());
      
      if (array_key_exists('need_tags', $form_values))
      {
        
        if ($form_values['need_tags'])
        {
          $element->setNeedTags(true);
        }
      }

      // On signale que cet user a modifié ses diffusions
      $user->setData(User::DATA_DIFF_UPDATED, true);
      $em->persist($user);
      
      $em->persist($element);
      $em->flush();

      if ($this->getRequest()->isXmlHttpRequest())
      {
        // Récupération du li
        if (!$group)
        {
          $html = $this->render('MuzichCoreBundle:SearchElement:li.element.html.twig', array(
            'element'     => $element,
            'class_color' => 'odd'  // TODO: n'est plus utilisé
          ))->getContent();
        }
         else 
        {
          $html = $this->render('MuzichCoreBundle:SearchElement:li.element.html.twig', array(
            'element'     => $element,
            'class_color' => 'odd',  // TODO: n'est plus utilisé
            'no_group_name' => true
          ))->getContent();
        }
        
        return $this->jsonResponse(array(
          'status' => 'success',
          'html'   => $html,
          'groups' => (!$group)?$this->isAddedElementCanBeInGroup($element):array()
        ));
      }
      else
      {
        return $this->redirect($redirect_url);
      }

    }
    else
    {
      if ($this->getRequest()->isXmlHttpRequest())
      {
        // Récupération des erreurs
        $validator = $this->container->get('validator');
        $errorList = $validator->validate($form);

        $errors = array();
        foreach ($errorList as $error)
        {
          $errors[] = $this->trans($error->getMessage(), array(), 'validators');
        }
        foreach ($form->getErrors() as $error)
        {
          if (!in_array($err = $this->trans($error->getMessageTemplate(), array(), 'validators'), $errors))
          {
            $errors[] = $err;
          }
        }
        
        return $this->jsonResponse(array(
          'status' => 'error',
          'errors' => $errors
        ));
      }
      else
      {

        if (!$group_slug)
        {
          $search_object = $this->getElementSearcher();
          $search_form = $this->getSearchForm($search_object);
          $add_form = $form;

          return $this->render('MuzichHomeBundle:Home:index.html.twig', array(
            'search_tags_id'   => $search_object->getTags(),
            'user'             => $this->getUser(),
            'add_form'         => $add_form->createView(),
            'add_form_name'    => 'add',
            'search_form'      => $search_form->createView(),
            'search_form_name' => 'search',
            'network_public'   => $search_object->isNetworkPublic(),
            'elements'         => $search_object->getElements($this->getDoctrine(), $this->getUserId()),
            'more_count'       => $this->container->getParameter('search_default_count')*2,
            'ids_display'      => $search_object->getIdsDisplay()
          ));
        }
        else
        {
          $group = $this->findGroupWithSlug($group_slug);
        
          $search_object = $this->createSearchObject(array(
            'group_id'  => $group->getId()
          ));

          ($group->getOwner()->getId() == $this->getUserId()) ? $his = true : $his = false;
          if ($his || $group->getOpen())
          {      
            $add_form = $form;
          }

          return $this->render('MuzichHomeBundle:Show:showGroup.html.twig', array(
            'group'         => $group,
            'his_group'     => ($group->getOwner()->getId() == $this->getUserId()) ? true : false,
            'elements'      => $search_object->getElements($this->getDoctrine(), $this->getUserId()),
            'following'     => $this->getUser()->isFollowingGroupByQuery($this->getDoctrine(), $group->getId()),
            'user'          => $this->getUser(),
            'add_form'      => (isset($add_form)) ? $add_form->createView() : null,
            'add_form_name' => (isset($add_form)) ? 'add' : null,
            'more_count'    => null,
            'more_route'    => 'show_group_more',
            'ids_display'   => $search_object->getIdsDisplay()
          ));
        }

      }

    }
      
    
    
  }
  
  /**
   * Action non ajax nettoyant la liste de tags du chercheur d'éléments
   * 
   * @return RedirectResponse 
   */
  public function filterClearAction()
  {
    $es = $this->getElementSearcher();
    $es->update(array('tags' => array()));
    $this->setElementSearcherParams($es->getParams());
    return $this->redirect($this->container->get('request')->headers->get('referer'));
  }
  
  /**
   * Action non ajax de selection de ses tags favoris pour le chercheur d'élément
   * 
   * @return RedirectResponse 
   */
  public function filterMytagsAction()
  {
    $this->getElementSearcher(null, true);
    return $this->redirect($this->container->get('request')->headers->get('referer'));
  }
  
  /**
   * Action de récupération ajax de l'id des tags favoris de son profil
   * 
   * @return Response 
   */
  public function getFavoriteTagsAction()
  {
    if (($response = $this->mustBeConnected()))
    {
      return $response;
    }
    
    // On construit l'element searcher avec les tags favoris
    $es = $this->getElementSearcher(null, true);
    // Et on retourne les tags
    return $this->jsonResponse(array(
      'response' => 'success',
      'tags'     => $es->getTags()
    ));
  }
  
  /**
   * Ajout d'un tag en base.
   */
  public function addTagAction()
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (strlen((($tag_name = $this->getRequest()->request->get('tag_name')))) 
      < $this->container->getParameter('tag_add_min_length'))
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array($this->trans(
          'tags.add.errors.min', 
          array(
            '%limit%' => $this->container->getParameter('tag_add_min_length')
          ), 
          'userui'
        )
      )));
    }
    
    if (strlen($tag_name) > $this->container->getParameter('tag_add_max_length'))
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array($this->trans(
          'tags.add.errors.max', 
          array(
            '%limit%' => $this->container->getParameter('tag_add_max_length')
          ), 
          'userui'
        )
      )));
    }
    
    $tagManager = new TagManager();
    $tag = $tagManager->addTag(
      $this->getDoctrine(), 
      $tag_name, 
      $this->getUser(), 
      $this->getRequest()->request->get('argument')
    );
    
    return $this->jsonResponse(array(
      'status'   => 'success',
      'tag_id'   => $tag->getId(),
      'tag_name' => $tag->getName()
    ));
  }
  
  /**
   * Action ajax qui ajoute le tags précisé en paramétre aux tags favoris de
   * l'utilisateur.
   * 
   * @param int $tag_id
   * @param string $token
   * @return Response 
   */
  public function addTagToFavoritesAction($tag_id, $token)
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (!($tag = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->findOneById($tag_id)) || $this->getUser()->getPersonalHash() != $token)
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotFound')
      ));
    }
    
    $user = $this->getUser();
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
    }
    
    // On contrôle au préalable que le tag ne fait pas déjà partie des favoris de 
    // l'utilisateur
    if (!$this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findOneBy(array(
        'user' => $this->getUserId(),
        'tag'  => $tag->getId()
      )))
    {
      // Si il ne l'est pas, on créer ce nouvel objet de relation
      $fav = new UsersTagsFavorites();
      $fav->setTag($tag);
      $fav->setUser($user);
      $fav->setPosition(0);
      $this->getDoctrine()->getEntityManager()->persist($fav);
      $this->getDoctrine()->getEntityManager()->flush();
    }
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
  
  /**
   * Cette action (ajax) configure l'appartenance d'un élément a un groupe.
   * Le groupe et l'élément doivent appartenir a l'utilisateur en cours.
   * 
   * @param int $element_id
   * @param int $group_id
   * @param string $token
   * @return Response 
   */
  public function setElementGroupAction($element_id, $group_id, $token)
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)) 
      || !($group = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')
      ->findOneById($group_id)) 
      || $this->getUser()->getPersonalHash() != $token)
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotFound')
      ));
    }
    
    if ($element->getOwner()->getId() != $this->getUserId()
      || $group->getOwner()->getId() != $this->getUserId()
    )
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotAllowed')
      ));
    }
    
    // a partir d'ici on a tout ce qu'il faut
    $element->setGroup($group);
    $this->getDoctrine()->getEntityManager()->persist($element);
    $this->getDoctrine()->getEntityManager()->flush();
    
    // On récupère le nouveau dom de l'élément
    $html = $this->render('MuzichCoreBundle:SearchElement:element.html.twig', array(
      'element'     => $element
    ))->getContent();
    
    return $this->jsonResponse(array(
      'status' => 'success',
      'html'   => $html,
      'dom_id' => 'element_'.$element->getId()
    ));
  }
  
  /**
   * Action (ajax) permettant de signaler un élément comme contenu non approprié.
   * 
   * @param int $element_id
   * @param string $token
   * @return Response 
   */
  public function reportElementAction($element_id, $token)
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)) 
      || $this->getUser()->getPersonalHash() != $token)
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotFound')
      ));
    }
    
    // On utilise le manager de rapport
    $erm = new ElementReportManager($element);
    $erm->add($this->getUser());
    
    $this->getDoctrine()->getEntityManager()->persist($element);
    $this->getDoctrine()->getEntityManager()->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
    
  }
  
  /**
   * Il arrive que l'on configure le chercheur d'élément de façon a ce qu'il
   * affiche une liste d'élément précis (collection d'id). Cette action
   * supprime cette configuration de façon a ce que le chercheur fonctionne 
   * normalement.
   * 
   * @return \Symfony\Component\HttpFoundation\Response 
   */
  public function filterRemoveIdsAction()
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    $es = $this->getElementSearcher();
    $es->setIds(null);
    $es->setIdsDisplay(null);
    $this->setElementSearcherParams($es->getParams());
    
    $html = $this->render('MuzichCoreBundle:SearchElement:default.html.twig', array(
      'user'        => $this->getUser(),
      'elements'    => $es->getElements($this->getDoctrine(), $this->getUserId()),
      'invertcolor' => false
    ))->getContent();

    return $this->jsonResponse(array(
      'status'  => 'success',
      'html'    => $html
    ));
  }
  
  /**
   * Url de récupération des plugins/application qui vienne partager une url
   * @param Request $request 
   */
  public function shareFromAction(Request $request)
  {
    return $this->redirect($this->generateUrl('home', array(
      'from_url' => $request->get('from_url'),
      // On ne se préoccupe pas de la locale coté plugins/applications
      '_locale'  => $this->determineLocale()
    )));
  }
  
}
