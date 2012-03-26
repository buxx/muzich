<?php

namespace Muzich\CoreBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Muzich\CoreBundle\ElementFactory\ElementManager;
use Muzich\CoreBundle\Propagator\EventElement;

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
    if ($this->container->getParameter('env') == 'test')
    {
      $user = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneById(
        $this->container->get('security.context')->getToken()->getUser()->getId(),
        array()
      )->getSingleResult();
    }
    
    $element = $this->checkExistingAndOwned($element_id);
    // Si il y a un groupe on le retire pour le bind
    $group = $element->getGroup();
    $element->setGroup(null);
    $form = $this->getAddForm($element);
    $form->bindRequest($this->getRequest());
    
    $errors = array();
    $html = '';
    if ($form->isValid())
    {
      $status = 'success';
      $em = $this->getDoctrine()->getEntityManager();
      // On utilise le manager d'élément
      $factory = new ElementManager($element, $em, $this->container);
      $factory->proceedFill($user);
      // Si il y avais un groupe on le remet
      $element->setGroup($group);
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
      $this->getDoctrine()->getEntityManager(), 
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
  public function removeAction($element_id)
  {
    if (($response = $this->mustBeConnected()))
    {
      return $response;
    }
    
    try {
      $element = $this->checkExistingAndOwned($element_id);
      $em = $this->getDoctrine()->getEntityManager();
      $em->remove($element);
      $em->flush();
      
      if ($this->getRequest()->isXmlHttpRequest())
      {
        return $this->jsonResponse(array('status' => 'success'));
      }
      $this->setFlash('success', 'element.remove.success');
      return $this->redirect($this->container->get('request')->headers->get('referer'));
    } 
    catch(Exception $e)
    {
      if ($this->getRequest()->isXmlHttpRequest())
      {
        return $this->jsonResponse(array('status' => 'error'));
      }
      $this->setFlash('error', 'element.remove.error');
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
      return $this->redirect($this->generateUrl('index'));
    }
    
    if (($response = $this->mustBeConnected()))
    {
      return $response;
    }
    
    $es = $this->getElementSearcher();
    $es->update(array(
      // On veux de nouveaux éléments
      'searchnew' => true,
      // Notre id de référence
      'id_limit'  => $refid
    ));
    
    $count = $es->getElements($this->getDoctrine(), $this->getUserId(), 'count');
    
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
      return $this->redirect($this->generateUrl('index'));
    }
    
    if (($response = $this->mustBeConnected()))
    {
      return $response;
    }
    
    $es = $this->getElementSearcher();
    $es->update(array(
      // On veux de nouveaux éléments
      'searchnew' => true,
      // Notre id de référence
      'id_limit'  => $refid,
      // On en veut qu'un certain nombres
      'count'     => $this->container->getParameter('search_default_count')
    ));
    
    // Récupération de ces nouveaux élméents
    $elements = $es->getElements($this->getDoctrine(), $this->getUserId());
    
    // On en fait un rendu graphique
    $html_elements = $this->render('MuzichCoreBundle:SearchElement:default.html.twig', array(
      'user'        => $this->getUser(),
      'elements'    => $elements,
      'invertcolor' => false
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
      $count = $es->getElements($this->getDoctrine(), $this->getUserId(), 'count');
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
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)) || $this->getUser()->getPersonalHash() != $token)
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
    $element->addVoteGood($this->getUser()->getId());
    // Puis on lance les actions propagés par ce vote
    $event = new EventElement($this->container);
    $event->onePointAdded($element);
    
    $this->getDoctrine()->getEntityManager()->persist($element);
    $this->getDoctrine()->getEntityManager()->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success',
      'data'   => array(
        'a' => array(
          'href' => $this->generateUrl('ajax_element_remove_vote_good', array(
            'element_id' => $element->getId(),
            'token'      => $this->getUser()->getPersonalHash()
          ))
        ),
        'img' => array(
          'src'  => $this->getAssetUrl('bundles/muzichcore/img/up_b.png')
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
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)) || $this->getUser()->getPersonalHash() != $token)
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
    $element->removeVoteGood($this->getUser()->getId());
    // Puis on lance les actions propagés par retrait de vote
    $event = new EventElement($this->container);
    $event->onePointRemoved($element);
    
    $this->getDoctrine()->getEntityManager()->persist($element);
    $this->getDoctrine()->getEntityManager()->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success',
      'data'   => array(
        'a' => array(
          'href' => $this->generateUrl('ajax_element_add_vote_good', array(
            'element_id' => $element->getId(),
            'token'      => $this->getUser()->getPersonalHash()
          ))
        ),
        'img' => array(
          'src'  => $this->getAssetUrl('bundles/muzichcore/img/up_bw.png')
        ),
        'element' => array(
          'points' => $element->getPoints()
        )
      )
    ));
  }
  
}