<?php

namespace Muzich\CoreBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Muzich\CoreBundle\Managers\ElementManager;
use Muzich\CoreBundle\Propagator\EventElement;
use Muzich\CoreBundle\Entity\ElementTagsProposition;
use Symfony\Component\HttpFoundation\Request;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Entity\Event;
use Muzich\CoreBundle\Util\TagLike;
use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\lib\AutoplayManager;
use Muzich\CoreBundle\Searcher\ElementSearcher;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Muzich\CoreBundle\Security\Context as SecurityContext;

class ElementController extends Controller
{
  
  /**
   * Cette méthode est utilisé pour récupérer un objet Element tout en levant
   * une erreur si il n'existe pas ou si il n'appartient pas a l'utilisateur en 
   * cours.
   * 
   * @param int $element_id
   * @return Muzich\CoreBundle\Entity\Element 
   */
  protected function checkExistingAndOwned($element_id)
  {    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)))
    {
      throw $this->createNotFoundException('Not found');
    }
    
    if ($element->getOwner()->getId() != $this->getUserId())
    {
      throw $this->createNotFoundException('Not found');
    }
    
    return $element;
  }
  
  /**
   * Action d'ouverture du formulaire de modification d'un élément.
   * 
   * @param int $element_id
   * @return Response
   */
  public function editAction($element_id)
  {
    if (($response = $this->mustBeConnected()))
    {
      return $response;
    }
    
    $element = $this->checkExistingAndOwned($element_id);
    
    // On doit faire un chmilblik avec les tags pour
    // utiliser le javascript de tags (tagPrompt)
    // sur le formulaire
    $element_tags = $element->getTags();
    $element->setTags($element->getTagsIdsJson());
    $form = $this->getAddForm($element);
    
    $search_tags = array();
    foreach ($element_tags as $tag)
    {
      $search_tags[$tag->getId()] = $tag->getName();
    }
    
    $template = 'MuzichCoreBundle:Element:ajax.element.edit.html.twig'; 
    if (!$this->getRequest()->isXmlHttpRequest())
    {
      $template = 'MuzichCoreBundle:Element:element.edit.html.twig'; 
    }
    
    $response = $this->render($template, array(
      'form'        => $form->createView(),
      'form_name'   => 'element_'.$element->getId(),
      'element_id'  => $element->getId(),
      'search_tags' => $search_tags
    ));
    
    if ($this->getRequest()->isXmlHttpRequest())
    {
      return $this->jsonResponse(array(
        'status'    => 'success',
        'form_name' => 'element_'.$element->getId(),
        'tags'      => $search_tags,
        'html'      => $response->getContent()
      ));
    }
    
    return $response;
  }
  
  /**
   * Mise a jour des données d'un élément.
   * 
   * @param int $element_id
   * @param string $dom_id
   * @return Response 
   */
  public function updateAction($element_id, $dom_id)
  {
    if (($response = $this->mustBeConnected()))
    {
      return $response;
    }
    
    /**
     * Bug lors des tests: L'user n'est pas 'lié' a celui en base par doctrine.
     * Docrine le voit si on faire une requete directe.
     */
    $user = $this->getUser();
    
    $element = $this->checkExistingAndOwned($element_id);
    // Si il y a un groupe on le retire pour le bind
    $group = $element->getGroup();
    $element->setGroup(null);
    $form = $this->getAddForm($element);
    $form->bind($this->getRequest());
    
    $errors = array();
    $html = '';
    if ($form->isValid())
    {
      $status = 'success';
      $em = $this->getDoctrine()->getManager();
      // On utilise le manager d'élément
      $factory = new ElementManager($element, $em, $this->container);
      $factory->proceedFill($user);
      // Si il y avais un groupe on le remet
      $element->setGroup($group);
      
      // On signale que cet user a modifié ses diffusions
      $user->setData(User::DATA_DIFF_UPDATED, true);
      $em->persist($user);
      
      $em->persist($element);
      $em->flush();
      
      // Récupération du li
      $html = $this->render('MuzichCoreBundle:SearchElement:element.html.twig', array(
        'element'     => $element
      ))->getContent();
    }
    else
    {
      $status = 'error';
      // Récupération des erreurs
      $validator = $this->container->get('validator');
      $errorList = $validator->validate($form);
      
      foreach ($errorList as $error)
      {
        $errors[] = $this->trans($error->getMessage(), array(), 'validators');
      }
    }
    
    if ($this->getRequest()->isXmlHttpRequest())
    {
      return $this->jsonResponse(array(
        'status'  => $status,
        'dom_id'  => $dom_id,
        'html'    => $html,
        'errors'  => $errors
      ));
    }
    
    if ($status == 'success')
    {
      return $this->redirect($this->generateUrl('home'));
    }
    
    
    $element->setTagsWithIds(
      $this->getDoctrine()->getManager(), 
      json_decode($element->getTags())
    );
    
    return $this->render('MuzichCoreBundle:Element:element.edit.html.twig', array(
      'form'        => $form->createView(),
      'form_name'   => 'element_'.$element->getId(),
      'element_id'  => $element->getId(),
      'search_tags' => $element->getTagsIdsJson()
    ));
  }
  
  /**
   * Suppression d'un élément. 
   * 
   * @param int $element_id
   * @return Response 
   */
  public function removeAction($element_id, $token)
  {
    if (($response = $this->mustBeConnected()))
    {
      return $response;
    }
    
    if ($token != $this->getUser()->getPersonalHash($element_id))
    {
      return $this->jsonResponse(array('status' => 'error'));
    }
    
    try {
      $element = $this->checkExistingAndOwned($element_id);
      $em = $this->getDoctrine()->getManager();
      
      $event = new EventElement($this->container);
      $event->elementRemoved($element);
      
      $em->persist($element->getOwner());
      $em->remove($element);
      
      $user = $this->getUser();
      
      // On signale que cet user a modifié ses diffusions
      $user->setData(User::DATA_DIFF_UPDATED, true);
      $em->persist($user);
      
      $em->flush();
      
      if ($this->getRequest()->isXmlHttpRequest())
      {
        return $this->jsonResponse(array('status' => 'success'));
      }
      $this->getFlashBag()->add('success', 'element.remove.success');
      return $this->redirect($this->container->get('request')->headers->get('referer'));
    } 
    catch(Exception $e)
    {
      if ($this->getRequest()->isXmlHttpRequest())
      {
        return $this->jsonResponse(array('status' => 'error'));
      }
      $this->getFlashBag()->add('error', 'element.remove.error');
      return $this->redirect($this->container->get('request')->headers->get('referer'));
    }
  }
  
  /**
   * Cette procédure retourne le lien a afficher sur la page home permettant
   * d'afficher des élément apparus entre temps.
   * 
   * @param int $count
   * @return type 
   */
  protected function getcountNewMessage($count)
  {
    if ($count == 1)
    {
      $transid = 'tags.new.has_news_one';
      $transidlink = 'tags.new.has_news_link_one';
    }
    else if ($count == 0)
    {
      return '';
    }
    else 
    {
      $transid = 'tags.new.has_news';
      $transidlink = 'tags.new.has_news_link';
    }
    
    
    if ($count > ($limit = $this->container->getParameter('search_default_count')))
    {
      $link = $this->trans(
        'tags.new.has_news_link_more_x', 
        array(
          '%x%' => $limit
        ), 
        'userui'
      );
    }
    else
    {
      $link = $this->trans(
        $transidlink, 
        array(), 
        'userui'
      );
    }
    
    $link = '<a href="#" class="show_new_elements" >'.$link.'</a>';
    
    return $this->trans(
      $transid, 
      array(
        '%count%' => $count,
        '%link%'  => $link
      ), 
      'userui'
    );
  }
  
  /**
   * Retourne le nombre de nouveaux éléments possible
   *
   * @param int $refid 
   */
  public function countNewsAction($refid)
  {
    if (!$this->getRequest()->isXmlHttpRequest())
    { 
      return $this->redirect($this->generateUrl('home'));
    }
    
    if ($this->getRequest()->getMethod() != 'POST')
    {
      throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
    }
    
    /*
     * On met à jour l'ElementSearcher avec le form
     */
    $es = $this->getElementSearcher(null, true);
    $search_form = $this->getSearchForm($es);
    $search_form->bind($this->getRequest());
    
    if ($search_form->isValid())
    {
      $es->update($search_form->getData());
    }
    
    $es->update(array(
      // On veux de nouveaux éléments
      'searchnew' => true,
      // Notre id de référence
      'id_limit'  => $refid
    ));
    
    $count = $es->getElements($this->getDoctrine(), $this->getUserId(true), 'count');
    
    return $this->jsonResponse(array(
      'status'   => 'success',
      'count'    => $count,
      'message'  => $this->getcountNewMessage($count)
    ));
  }
  
  /**
   * Cette action, utilisé en ajax seulement, retourne les x nouveaux éléments
   * depuis le refid transmis. Tout en respectant le filtre en cours.
   * 
   * @param int $refid identifiant de l'élément de référence
   * 
   * @return jsonResponse
   */
  public function getNewsAction($refid)
  {
    if (!$this->getRequest()->isXmlHttpRequest())
    { 
      return $this->redirect($this->generateUrl('home'));
    }
    
    if ($this->getRequest()->getMethod() != 'POST')
    {
      throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
    }
    
    /*
     * On met à jour l'ElementSearcher avec le form
     */
    $es = $this->getElementSearcher(null, true);
    $search_form = $this->getSearchForm($es);
    $search_form->bind($this->getRequest());
    
    if ($search_form->isValid())
    {
      $es->update($search_form->getData());
    }
    
    $es->update(array(
      // On veux de nouveaux éléments
      'searchnew' => true,
      // Notre id de référence
      'id_limit'  => $refid,
      // On en veut qu'un certain nombres
      'count'     => $this->container->getParameter('search_default_count')
    ));
    
    // Récupération de ces nouveaux élméents
    $elements = $es->getElements($this->getDoctrine(), $this->getUserId(true));
    
    // On en fait un rendu graphique
    $html_elements = $this->render('MuzichCoreBundle:SearchElement:default.html.twig', array(
      'user'        => $this->getUser(),
      'elements'    => $elements,
      'display_autoplay' => true,
      'autoplay_context' => 'home'
    ))->getContent();
    
    // On calcule le nouveau compte de nouveaux
    $count = 0;
    if (count($elements))
    {      
      $es->update(array(
        // On veux de nouveaux éléments
        'searchnew' => true,
        // Notre id de référence
        'id_limit'  => $elements[0]->getId(),
        // On n'en récupère que x
        'count'     => $this->container->getParameter('search_default_count')
      ));
      $count = $es->getElements($this->getDoctrine(), $this->getUserId(true), 'count');
    }
    
    return $this->jsonResponse(array(
      'status'  => 'success',
      'html'    => $html_elements,
      'count'   => $count,
      'message' => $this->getcountNewMessage($count)
    ));
  }
  
  /**
   * Action (ajax) ajoutant son vote "good" sur un élément
   * 
   * @param int $element_id
   * @param string $token
   * @return Response 
   */
  public function addVoteGoodAction($element_id, $token)
  {
    if (($non_condition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_ELEMENT_NOTE)) !== false)
    {
      return $this->jsonResponseError($non_condition);
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)) || $this->getUser()->getPersonalHash($element_id) != $token)
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotFound')
      ));
    }
    
    if ($element->getOwner()->getId() == $this->getUserId())
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotAllowed')
      ));
    }
    
    // On ajoute un vote a l'élément
    $element->addVoteGood($this->getUser()->getId(), $this->container->getParameter('reputation_element_point_value'));
    // Puis on lance les actions propagés par ce vote
    $event = new EventElement($this->container);
    $event->onePointAdded($element);
    
    $this->getDoctrine()->getManager()->persist($element->getOwner());
    $this->getDoctrine()->getManager()->persist($element);
    $this->getDoctrine()->getManager()->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success',
      'data'   => array(
        'a' => array(
          'href' => $this->generateUrl('ajax_element_remove_vote_good', array(
            'element_id' => $element->getId(),
            'token'      => $this->getUser()->getPersonalHash($element->getId())
          ))
        ),
        'img' => array(
          'src'  => $this->getAssetUrl('/img/icon_thumb_red.png')
        ),
        'element' => array(
          'points' => $element->getPoints()
        )
      )
    ));
  }
  
  /**
   * Action (ajax) de retrait de son vote good
   * 
   * @param int $element_id
   * @param string $token
   * @return Response 
   */
  public function removeVoteGoodAction($element_id, $token)
  {
    if (($non_condition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_ELEMENT_NOTE)) !== false)
    {
      return $this->jsonResponseError($non_condition);
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)) || $this->getUser()->getPersonalHash($element_id) != $token)
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotFound')
      ));
    }
    
    if ($element->getOwner()->getId() == $this->getUserId())
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotAllowed')
      ));
    }
    
    // Retrait du vote good
    $element->removeVoteGood($this->getUser()->getId(), $this->container->getParameter('reputation_element_point_value'));
    // Puis on lance les actions propagés par retrait de vote
    $event = new EventElement($this->container);
    $event->onePointRemoved($element);
    
    $this->getDoctrine()->getManager()->persist($element->getOwner());
    $this->getDoctrine()->getManager()->persist($element);
    $this->getDoctrine()->getManager()->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success',
      'data'   => array(
        'a' => array(
          'href' => $this->generateUrl('ajax_element_add_vote_good', array(
            'element_id' => $element->getId(),
            'token'      => $this->getUser()->getPersonalHash($element->getId())
          ))
        ),
        'img' => array(
          'src'  => $this->getAssetUrl('/img/icon_thumb.png')
        ),
        'element' => array(
          'points' => $element->getPoints()
        )
      )
    ));
  }
    
  /**
   * Retourne un json avec le form permettant a l'utilisateur de proposer des
   * tags sur un élément.
   * 
   * @param int $element_id
   * @return Response 
   */
  public function proposeTagsOpenAction($element_id)
  {
    if (($non_condition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_ELEMENT_TAGS_PROPOSITION)) !== false)
    {
      return $this->jsonResponseError($non_condition);
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)))
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotFound')
      ));
    }
    
    $search_tags = array();
    foreach ($element->getTags() as $tag)
    {
      $search_tags[$tag->getId()] = $tag->getName();
    }
    
    $element->setTags($element->getTagsIdsJson());
    $form = $this->getAddForm($element, 'element_tag_proposition_'.$element->getId());
    $response = $this->render('MuzichCoreBundle:Element:tag.proposition.html.twig', array(
      'form'        => $form->createView(),
      'form_name'   => 'element_tag_proposition_'.$element->getId(),
      'element_id'  => $element->getId(),
      'search_tags' => $search_tags
    ));
    
    return $this->jsonResponse(array(
      'status'    => 'success',
      'form_name' => 'element_tag_proposition_'.$element->getId(),
      'tags'      => $search_tags,
      'html'      => $response->getContent()
    ));
  }
  
  public function proposeTagsProceedAction($element_id, $token)
  {
    if (($non_condition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_ELEMENT_TAGS_PROPOSITION)) !== false)
    {
      return $this->jsonResponseError($non_condition);
    }
    
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)) || $token != $this->getUser()->getPersonalHash())
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotFound')
      ));
    }
    
    // On ne doit pas pouvoir proposer de tags sur son propre élément
    if ($element->getOwner()->getId() == $this->getUserId())
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotAllowed')
      ));
    }
    
    $values   = $this->getRequest()->request->get('element_tag_proposition_'.$element->getId());
    $tags_ids = json_decode($values['tags'], true);
    
    $tags = array();
    if (count($tags_ids))
    {
      // On récupère les tags en base
      $tags = $this->getDoctrine()->getManager()->getRepository('MuzichCoreBundle:Tag')
        ->getTagsWithIds($tags_ids)
      ;
    }
    
    if (!count($tags))
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array($this->trans('element.tag_proposition.form.error.empty', array(), 'elements'))
      ));
    }
    
    /**
     * Bug lors des tests: L'user n'est pas 'lié' a celui en base par doctrine.
     * Docrine le voit si on faire une requete directe.
     */
    $user = $this->getUser();
    
    $proposition = new ElementTagsProposition();
    $proposition->setElement($element);
    $proposition->setUser($user);
    $date = new \DateTime(date('Y-m-d H:i:s'));
    $proposition->setCreated($date);
    
    foreach ($tags as $tag)
    {
      // Si le tag est a modérer, il faut que le propriétaire de l'élément
      // puisse voir ce tag, afin d'accepter en toute connaisance la proposition.
      if ($tag->getTomoderate())
      {
        if (!$tag->hasIdInPrivateIds($element->getOwner()->getId()))
        {
          // Si son id n'y est pas on la rajoute afin que le proprio puisse voir 
          // ces nouveau tags
          $private_ids = json_decode($tag->getPrivateids(), true);
          $private_ids[] = $element->getOwner()->getId();
          $tag->setPrivateids(json_encode($private_ids));
          $this->getDoctrine()->getManager()->persist($tag);
        }
      }
          
      $proposition->addTag($tag);
    }
    
    $element->setHasTagProposition(true);
    
    $this->getDoctrine()->getManager()->persist($element);
    $this->getDoctrine()->getManager()->persist($proposition);
    
    // Notifs etc 
    $event = new EventElement($this->container);
    $event->tagsProposed($element);
    
    $this->getDoctrine()->getManager()->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success',
      'dom_id' => 'element_'.$element->getId()
    ));
  }
  
  public function proposedTagsViewAction($element_id)
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)))
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotFound')
      ));
    }
    
    if ($element->getOwner()->getId() != $this->getUserId())
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotAllowed')
      ));
    }
    
    // On récupére toute les propsotions pour cet élément
    $propositions = $this->getDoctrine()->getManager()->getRepository('MuzichCoreBundle:ElementTagsProposition')
      ->findByElement($element->getId())
    ;
    
    $response = $this->render('MuzichCoreBundle:Element:tag.propositions.html.twig', array(
      'propositions' => $propositions,
      'element_id'   => $element->getId()
    ));
    
    return $this->jsonResponse(array(
      'status'    => 'success',
      'html'      => $response->getContent()
    ));
    
  }
  
  public function proposedTagsAcceptAction($proposition_id, $token)
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (!($proposition = $this->getDoctrine()->getRepository('MuzichCoreBundle:ElementTagsProposition')
      ->findOneById($proposition_id)) || $token != $this->getUser()->getPersonalHash($proposition_id))
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotFound')
      ));
    }
    
    // On commence par appliquer les nouveaux tags a l'élément
    $element = $proposition->getElement();
    $element->setTags(null);
    foreach ($proposition->getTags() as $tag)
    {
      $element->addTag($tag);
    }
    $element->setHasTagProposition(false);
    $element->setNeedTags(false);
    $this->getDoctrine()->getManager()->persist($element);
    
    $event = new EventElement($this->container);
    $event->tagsAccepteds($proposition);
    
    $propositions = $this->getDoctrine()->getManager()->getRepository('MuzichCoreBundle:ElementTagsProposition')
      ->findByElement($element->getId())
    ;
    
    // On supprime les proposition liés a cet élement
    foreach ($propositions as $proposition)
    {
      $this->getDoctrine()->getManager()->remove($proposition);
    }
    
    // Traitement de l'Event si il y a
    $this->removeElementFromEvent($element->getId(), Event::TYPE_TAGS_PROPOSED);
    
    $this->getDoctrine()->getManager()->flush();
    $element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element->getId())
    ;
    
    // On récupère l'html de l'élément
    $html = $this->render('MuzichCoreBundle:SearchElement:element.html.twig', array(
      'element'     => $element
    ))->getContent();
    
    return $this->jsonResponse(array(
      'status' => 'success',
      'html'   => $html
    ));
  }
  
  public function proposedTagsRefuseAction($element_id, $token)
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)) || $token != $this->getUser()->getPersonalHash($element_id))
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotFound')
      ));
    }
    
    // On supprime les proposition liés a cet élement
    $propositions = $this->getDoctrine()->getManager()->getRepository('MuzichCoreBundle:ElementTagsProposition')
      ->findByElement($element->getId())
    ;
    foreach ($propositions as $proposition)
    {
      $this->getDoctrine()->getManager()->remove($proposition);
    }
    
    // Traitement de l'Event si il y a
    $this->removeElementFromEvent($element->getId(), Event::TYPE_TAGS_PROPOSED);
    
    // On spécifie qu'il n'y as plus de proposition
    $element->setHasTagProposition(false);
    $this->getDoctrine()->getManager()->persist($element);
    $this->getDoctrine()->getManager()->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
  
  
  protected function removeElementFromEvent($element_id, $event_type)
  {
    if (($event = $this->getEntityManager()->getRepository('MuzichCoreBundle:Event')
          ->findUserEventWithElementId($this->getUserId(), $element_id, $event_type)))
    {
      $event->removeId($element_id);
      if (!$event->getCount())
      {
        $this->remove($event);
        $this->flush();
        return;
      }
      
      $this->persist($event);
      $this->flush();
    }
  }
  
  public function reshareAction(Request $request, $element_id, $token)
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if ($this->getUser()->getPersonalHash('reshare_'.$element_id) != $token)
    {
      throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException();
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)))
    {
      throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
    }
    
    if ($element->getOwner()->getId() == $this->getUserId())
    {
      throw new \Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException();
    }
    
    
    /**
      * Bug lors des tests: L'user n'est pas 'lié' a celui en base par doctrine.
      * Docrine le voit si on faire une requete directe.
      */
    $user = $this->getUser();
    
    // Pour le repartage on crée un nouvel élément
    $element_reshared = new Element();
    $element_reshared->setUrl($element->getUrl());
    $element_reshared->setName($element->getName());
    $element_reshared->addTags($element->getTags());
    $element_reshared->setParent($element);

    // On utilise le gestionnaire d'élément
    $factory = new ElementManager($element_reshared, $this->getEntityManager(), $this->container);
    $factory->proceedFill($user, false);
    
    // On se retrouve maintenant avec un nouvel element tout neuf
    $this->persist($element_reshared);
    $this->flush();
    
    $html_element = $this->render('MuzichCoreBundle:SearchElement:li.element.html.twig', array(
      'element'     => $element_reshared,
      'class_color' => 'odd' // TODO: n'est plus utilisé
    ))->getContent();

    return $this->jsonResponse(array(
      'status' => 'success',
      'html'   => $html_element,
      'groups' => $this->isAddedElementCanBeInGroup($element_reshared)
    ));
  }
  
  protected function findTagsWithProposeds($tags)
  {
    $tag_like = new TagLike($this->getDoctrine()->getManager());
    $tags_with_likes = array();
    foreach ($tags as $tag_name)
    {
      // On va determiner si on connais ces tags
      $tag_like_tag = $tag_like->getSimilarTags($tag_name, $this->getUserId(true));
      
      // Premièrement: Si on a trouvé des équivalents en base
      if (array_key_exists('tags', $tag_like_tag))
      {
        if (count($tag_like_tag['tags']))
        {
          // Deuxièmement: Si nos algorythmes on déterminés qu'on l'a en base
          if ($tag_like_tag['same_found'])
          {
            // A ce moment là on le considère comme "bon"
            // Et on prend le premier
            $tags_with_likes[] = array(
              'original_name' => $tag_name,
              'like_found'    => true,
              'like'          => $tag_like_tag['tags'][0]
            );
          }
          // On considère ce tag comme inconnu, l'utilisateur peut toute fois 
          // l'ajouté a notre base.
          else
          {
            $tags_with_likes[] = array(
              'original_name' => $tag_name,
              'like_found'    => false,
              'like'          => array()
            );
          }
        }
      }
    }
    
    return $tags_with_likes;
  }
  
  public function getDatasApiAction(Request $request)
  { 
    $url =  null;
    if (count(($element_add_values = $request->get('element_add'))))
    {
      $url = trim($element_add_values['url']);
    }
    
    // On vérifie la tête de l'url quand même
    if (filter_var($url, FILTER_VALIDATE_URL) === false)
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array(
          $this->trans('error.url.invalid', array(), 'validators')
        )
      ));
    }
    
    // On construit l'élèment qui va nous permettre de travailler avec l'api
    $element = new Element();
    $element->setUrl($url);
    
    $factory = new ElementManager($element, $this->getEntityManager(), $this->container);
    $factory->proceedFill((!$this->isVisitor())?$this->getUser():null);
    
    // On gère les tags proposés
    $tags_propositions = array();
    if (count($tags = $element->getProposedTags()))
    {
      $tags_propositions = $this->findTagsWithProposeds($tags);
    }
    
    return $this->jsonResponse(array(
      'status' => 'success',
      'name'   => $element->getProposedName(),
      'tags'   => $tags_propositions,
      'thumb'  => $element->getThumbnailUrl() 
    ));
  }
  
  /**
   * Retourne les données permettant de faire une playlist
   * 
   * @param Request $request 
   * @param "filter"|"show"|"favorites" $type
   * @param ~ $data
   */
  public function getDatasAutoplayAction(Request $request, $element_id, $type, $data, $show_type = null, $show_id = null)
  {
    $elements = array();
    $elements_json = array();
    
    if ($type == 'filter')
    {
      // Pour cette option on utilise le dernier filtre appliqué
      $search_object = $this->getElementSearcher();
      $search_object->update(array(
        'count' => $this->container->getParameter('autoplay_max_elements'),
        'id_limit' => $element_id+1
      ));
      $elements = $search_object->getElements($this->getDoctrine(), $this->getUserId(true));
    }
    elseif ($type == 'show')
    {
      if ($show_type != 'user' && $show_type != 'group')
      {
        throw $this->createNotFoundException('Not found');
      }
      
      $tags = null;
      $tag_ids = json_decode($data);
      $search_object = new ElementSearcher();
      
      $id_limit = $element_id+1;
      if ($element_id < 1) {
          $id_limit = Null;
      }
      
      if (count($tag_ids))
      {
        $tags = array();
        foreach ($tag_ids as $id)
        {
          $tags[$id] = $id;
        }
      }

      $search_object->init(array(
        'tags'           => $tags,
        $show_type.'_id' => $show_id,
        'count'          => $this->container->getParameter('autoplay_max_elements'),
        'id_limit' => $id_limit
      ));
      
      $elements = $search_object->getElements($this->getDoctrine(), $this->getUserId(true));
    }
    elseif ($type == 'favorite')
    {
      $tags = null;
      $tag_ids = json_decode($data);
      $search_object = new ElementSearcher();
      
      if (count($tag_ids))
      {
        $tags = array();
        foreach ($tag_ids as $id)
        {
          $tags[$id] = $id;
        }
      }
      
      $id_limit = $element_id+1;
      if ($element_id < 1) {
          $id_limit = Null;
      }

      $search_object->init(array(
        'tags'     => $tags,
        'user_id'  => $show_id,
        'favorite' => true,
        'count'    => $this->container->getParameter('autoplay_max_elements'),
        'id_limit' => $id_limit
      ));
      
      $elements = $search_object->getElements($this->getDoctrine(), $this->getUserId(true));
    }
    
    if (count($elements))
    {
      // On récupère les élements
      $autoplaym = new AutoplayManager($elements, $this->container);
      
      // Petit hack pour savoir qu'on est en suffle
      if ($element_id == -1) {
          $autoplaym->shuffle();
      }
      
      $elements_json = $autoplaym->getList();
    }
    
    return $this->jsonResponse(array(
      'status'    => 'success',
      'data'      => $elements_json
    ));
  }
  
  
  public function getOneAction($element_id)
  {
    $es = new ElementSearcher();
    $es->init(array(
      'ids'              => array($element_id),
      'display_privates' => true
    ));
    
    if (!($element = $es->getElements($this->getDoctrine(), $this->getUserId(true), 'single')))
    {
      return $this->jsonResponse(array(
        self::RESPONSE_STATUS_ID  => self::RESPONSE_STATUS_ERROR,
        self::RESPONSE_ERROR_ID   => self::ERROR_TYPE_NOTFOUND,
        self::RESPONSE_MESSAGE_ID => $this->trans('noelements.nofound_anymore', array(), 'elements')
      ));
    }
    
    $html = $this->render('MuzichCoreBundle:SearchElement:li.element.html.twig', array(
      'element'               => $element,
      'display_edit_actions'  => false,
      'display_player'        => true,
      'display_comments'      => true
    ))->getContent();
    
    return $this->jsonResponse(array(
      'status'  => 'success',
      'data'    => $html
    ));
  }
  
  public function getOneDomAction(Request $request, $element_id, $type)
  {
    if (!in_array($type, array('autoplay')) || !$element_id)
    {
      return $this->jsonResponse(array(
        'status' => 'error'
      ));
    }
    
    // variables pour le template
    $display_edit_actions  = true;
    $display_player        = true;
    $display_comments      = true;
    
    if ($type == 'autoplay')
    {
      $display_edit_actions  = false;
      $display_player        = false;
      $display_comments      = false;
    }
    
    // On prépare la récupèration de l'élèment
    $es = new ElementSearcher();
    $es->init(array(
      'ids'              => array($element_id),
      'display_privates' => true
    ));
    
    if (!($element = $es->getElements($this->getDoctrine(), $this->getUserId(true), 'single')))
    {
      throw $this->createNotFoundException('Not found');
    }
    
    $html = $this->render('MuzichCoreBundle:SearchElement:element.html.twig', array(
      'element'               => $element,
      'display_edit_actions'  => $display_edit_actions,
      'display_player'        => $display_player,
      'display_comments'      => $display_comments
    ))->getContent();
    
    return $this->jsonResponse(array(
      'status'  => 'success',
      'data'    => $html
    ));
  }
  
  public function geJamendotStreamDatasAction(Request $request, $element_id)
  {
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)))
    {
      throw $this->createNotFoundException('Not found');
    }
        
    $manager = new ElementManager($element, $this->getEntityManager(), $this->container);
    $stream_data = $manager->getFactory()->getStreamData();
    
    return $this->jsonResponse(array(
      'status' => 'success',
      'data'   => $stream_data,
    ));
  }
  
  public function getEmbedCodeAction($element_id)
  {
    if (!$element_id)
    {
      return $this->jsonNotFoundResponse();
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)))
    {
      return $this->jsonNotFoundResponse();
    }
    
    return $this->jsonResponse(array(
      'status' => 'success',
      'data'   => $element->getEmbed(),
    ));
  }
  
  public function removeFromGroupAction($group_id, $element_id, $token)
  {
    if (!($group = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')
      ->findOneById($group_id))
        || !($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)))
    {
      return $this->jsonNotFoundResponse();
    }
    
    if ($token != $this->getUser()->getPersonalHash('remove_from_group_'.$element->getId())
      || $group->getOwner()->getId() != $this->getUserId())
    {
      return $this->jsonNotFoundResponse();
    }
    
    $element->setGroup(null);
    $this->persist($element);
    $this->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
  
  public function shareFromAction(Request $request)
  {
    if (!$request->get('from_url'))
      throw $this->createNotFoundException();
    
    return $this->render('MuzichCoreBundle:Element:share_from.html.twig', array(
      'add_form' => $this->getAddForm()->createView(),
      'from_url' => $request->get('from_url')
    ));
  }
  
}