<?php

namespace Muzich\CoreBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Muzich\CoreBundle\Entity\FollowUser;
use Muzich\CoreBundle\Entity\FollowGroup;
//use Doctrine\ORM\Query;
use Muzich\CoreBundle\Form\Element\ElementAddForm;
use Muzich\CoreBundle\ElementFactory\ElementManager;
use Muzich\CoreBundle\Entity\Element;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Muzich\CoreBundle\Form\Search\ElementSearchForm;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

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
   * Cette action permet a un utilisateur de suivre ou de ne plus suivre
   * un utilisateur ou un groupe.
   * 
   * @param string $type
   * @param int $id
   * @param string $salt 
   */
  public function followAction($type, $id, $token)
  {
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
    if ($user->getPersonalHash() != $token || !in_array($type, array('user', 'group')) || !is_numeric($id))
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
      // L'utilisateur suis déjà, on doit détruire l'entité
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
      if ($type == 'user') { $Follow->setFollowed($followed); }
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
    if ($this->getRequest()->getMethod() != 'POST')
    {
      throw $this->createNotFoundException('Cette ressource n\'est pas accessible');
    }
    
    $user = $this->getUser();
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

      $em->persist($element);
      $em->flush();

      if ($this->getRequest()->isXmlHttpRequest())
      {
        // Récupération du li
        $html = $this->render('MuzichCoreBundle:SearchElement:li.element.html.twig', array(
          'element'     => $element,
          'class_color' => 'odd'
        ))->getContent();
        
        return $this->jsonResponse(array(
          'status' => 'success',
          'html'   => $html
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

        foreach ($errorList as $error)
        {
          $errors[] = $error->getMessage();
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
            'more_count'       => $this->container->getParameter('search_default_count')*2
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
            'more_route'    => 'show_group_more'
          ));
        }

      }

    }
      
    
    
  }
  
  public function filterClearAction()
  {
    $es = $this->getElementSearcher();
    $es->update(array('tags' => array()));
    $this->setElementSearcherParams($es->getParams());
    return $this->redirect($this->container->get('request')->headers->get('referer'));
  }
  
  public function filterMytagsAction()
  {
    $this->getElementSearcher(null, true);
    return $this->redirect($this->container->get('request')->headers->get('referer'));
  }
  
}
