<?php

namespace Muzich\CommentBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Muzich\CoreBundle\Managers\CommentsManager;

class CommentController extends Controller
{
  
  public function addAction($element_id, $token)
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
    
    
    // On met a jour les commentaires
    $cm = new CommentsManager($element->getComments());
    $cm->add($this->getUser(), $comment);
    $element->setComments($cm->get());

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
  
  public function deleteAction($element_id, $date, $token)
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
    
    // On met a jour les commentaires
    $cm = new CommentsManager($element->getComments());
    
    
    if (!$cm->delete($this->getUserId(), $date))
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array($this->trans(
          'element.comments.errors.unknow', 
          array(), 
          'elements'
        )
      )));
    }
    
    $element->setComments($cm->get());
      
    $this->getDoctrine()->getEntityManager()->persist($element);
    $this->getDoctrine()->getEntityManager()->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
  
  public function editAction($element_id, $date, $token)
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
    
    $cm = new CommentsManager($element->getComments());
    $comment = $cm->get($cm->getIndex($this->getUserId(), $date));
    
    $html = $this->render('MuzichCommentBundle:Comment:edit.html.twig', array(
      'comment'     => $comment,
      'element_id'  => $element->getId(),
      'date'        => $date
    ))->getContent();
    
    return $this->jsonResponse(array(
      'status' => 'success',
      'html'   => $html
    ));
  }
  
  public function updateAction($element_id, $date, $token, $dom_id)
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
      
    // On met a jour les commentaires
    $cm = new CommentsManager($element->getComments());
    $cm->update($this->getUser(), $date, $comment);
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
  
}