<?php

namespace Muzich\CommentBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Muzich\CoreBundle\Managers\CommentsManager;
use Muzich\CoreBundle\Propagator\EventElement;
use Muzich\CoreBundle\Security\Context as SecurityContext;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{
  
  /**
   * Action d'ajouter un commentaire.
   * 
   * @param int $element_id
   * @param string $token
   * @return \Symfony\Component\HttpFoundation\Response 
   */
  public function addAction($element_id, $token)
  {
    if (($non_condition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_COMMENT_ADD)) !== false)
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
    
    $length = strlen((($comment = $this->getRequest()->request->get('comment'))));
        
    // TODO: Faire un objet pour le formulaire ajout de commentaire
    if ($length  < $this->container->getParameter('comment_add_min_length'))
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array($this->trans(
          'element.comments.errors.min', 
          array(
            '%limit%' => $this->container->getParameter('comment_add_min_length')
          ), 
          'elements'
        )
      )));
    }
    if ($length > $this->container->getParameter('comment_add_max_length'))
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array($this->trans(
          'element.comments.errors.max', 
          array(
            '%limit%' => $this->container->getParameter('comment_add_max_length')
          ), 
          'elements'
        )
      )));
    }
    
    $follow = false;
    if ($this->getRequest()->request->get('follow') == true)
    {
      $follow = true;
    }
    
    // On met a jour les commentaires
    $cm = new CommentsManager($element->getComments());
    $cm->add($this->getUser(), $comment, $follow);
    $element->setComments($cm->get());
    $event = new EventElement($this->container);
    
    // Event pour user d'un nouveau comment
    $event->commentAdded($element, $this->getUser());

    $this->getDoctrine()->getEntityManager()->persist($element);
    $this->getDoctrine()->getEntityManager()->flush();

    // On récupère le html du li avec le comment pour la réponse
    $html = $this->render('MuzichCommentBundle:Comment:li.comment.html.twig', array(
      'comment'     => $cm->getLast(),
      'element_id'  => $element->getId()
    ))->getContent();

    return $this->jsonResponse(array(
      'status' => 'success',
      'html'   => $html
    ));
         
  }
  
  /**
   * Suppression d'un commentaire
   *
   * @param type $element_id
   * @param type $date
   * @param type $token
   * @return \Symfony\Component\HttpFoundation\Response 
   */
  public function deleteAction($element_id, $date, $token)
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)) || $this->getUser()->getPersonalHash($element_id) != $token)
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotFound')
      ));
    }
    
    // On met a jour les commentaires
    $cm = new CommentsManager($element->getComments());
    
    // On utilise le comment manager pour supprimer le commentaire de la liste
    if (!$cm->delete($this->getUserId(), $date))
    {
      // Si il n'a pas été trouvé on répond une erreur
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array($this->trans(
          'element.comments.errors.unknow', 
          array(), 
          'elements'
        )
      )));
    }
    
    // Si tout c'est bien passé on met a jour l'attribut de l'élément
    $element->setComments($cm->get());
      
    $this->getDoctrine()->getEntityManager()->persist($element);
    $this->getDoctrine()->getEntityManager()->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
  
  /**
   * Modification d'un commentaire, ouverture du formulaire 
   * 
   * @param int $element_id
   * @param string $date (Y-m-d H:i:s u)
   * @param string $token
   * @return Response 
   */
  public function editAction($element_id, $date, $token)
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)) || $this->getUser()->getPersonalHash($element_id) != $token)
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotFound')
      ));
    }
    
    // On utilise le gestionnaire de commentaire
    $cm = new CommentsManager($element->getComments());
    // On récupére le commentaire visé
    $comment = $cm->get($cm->getIndex($this->getUserId(), $date));
    // On rpépare la réponse html (formulaire)
    $html = $this->render('MuzichCommentBundle:Comment:edit.html.twig', array(
      'comment'     => $comment,
      'element_id'  => $element->getId(),
      'date'        => $date,
      'following'   => $element->userFollowComments($this->getUserId()),
      'own'         => ($this->getUserId() == $element->getOwner()->getId())
    ))->getContent();
    // On retourne le tout
    return $this->jsonResponse(array(
      'status' => 'success',
      'html'   => $html
    ));
  }
  
  /**
   * Mise a jour du commentaire. On précise dom_id pour retrouver facilement le 
   * commentaire dans le dom lorsque js récupére la réponse.
   * 
   * @param int $element_id
   * @param string $date (Y-m-d H:i:s u)
   * @param string $token
   * @param string $dom_id
   * @return type 
   */
  public function updateAction($element_id, $date, $token, $dom_id)
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)) || $this->getUser()->getPersonalHash($element_id) != $token)
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotFound')
      ));
    }
        
    // TODO: Faire un objet pour le formulaire ajout de commentaire
    $length = strlen((($comment = $this->getRequest()->request->get('comment'))));
        
    // TODO: Faire un objet pour le formulaire ajout de commentaire
    if ($length  < $this->container->getParameter('comment_add_min_length'))
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'dom_id' => $dom_id,
        'errors' => array($this->trans(
          'element.comments.errors.min', 
          array(
            '%limit%' => $this->container->getParameter('comment_add_min_length')
          ), 
          'elements'
        )
      )));
    }
    if ($length > $this->container->getParameter('comment_add_max_length'))
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'dom_id' => $dom_id,
        'errors' => array($this->trans(
          'element.comments.errors.max', 
          array(
            '%limit%' => $this->container->getParameter('comment_add_max_length')
          ), 
          'elements'
        )
      )));
    }
    
    $follow = false;
    if ($this->getRequest()->request->get('follow') == true)
    {
      $follow = true;
    }
      
    // On met a jour les commentaires
    $cm = new CommentsManager($element->getComments());
    $cm->update($this->getUser(), $date, $comment, $follow);
    $element->setComments($cm->get());

    $this->getDoctrine()->getEntityManager()->persist($element);
    $this->getDoctrine()->getEntityManager()->flush();
        
    if (null === ($comment_index = $cm->getIndex($this->getUserId(), $date)))
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'dom_id' => $dom_id,
        'errors' => array($this->trans(
          'element.comments.errors.unknow', 
          array(), 
          'elements'
        )
      )));
    }
    
    // On récupère le html du li avec le comment pour la réponse
    $html = $this->render('MuzichCommentBundle:Comment:comment.html.twig', array(
      'comment'     => $cm->get($comment_index),
      'element_id'  => $element->getId()
    ))->getContent();
    
    return $this->jsonResponse(array(
      'status' => 'success',
      'dom_id' => $dom_id,
      'html'   => $html
    ));
    
  }
  
  /**
   * Signalement d'un commentaire
   * 
   * @param int $element_id
   * @param date $date
   * @param string $token
   * @param string $dom_id
   * @return Response 
   */
  public function alertAction($element_id, $date, $token)
  {
if (($non_condition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_COMMENT_ALERT)) !== false)
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
    
    // Création de l'objet de gestion des commentaires
    $cm = new CommentsManager($element->getComments());
    $cm->alertComment($this->getUserId(), $date);
    $element->setComments($cm->get());
    $element->setCountCommentReport($cm->countCommentAlert());
    
    $this->getDoctrine()->getEntityManager()->persist($element);
    $this->getDoctrine()->getEntityManager()->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
  
  /**
   * Signalement d'un commentaire
   * 
   * @param int $element_id
   * @param date $date
   * @param string $token
   * @param string $dom_id
   * @return Response 
   */
  public function unAlertAction($element_id, $date, $token)
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)) || $this->getUser()->getPersonalHash($element_id) != $token)
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotFound')
      ));
    }
    
    // Création de l'objet de gestion des commentaires
    $cm = new CommentsManager($element->getComments());
    $cm->unAlertComment($this->getUserId(), $date);
    $element->setComments($cm->get());
    $element->setCountCommentReport($cm->countCommentAlert());
    
    $this->getDoctrine()->getEntityManager()->persist($element);
    $this->getDoctrine()->getEntityManager()->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
  
}