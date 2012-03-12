<?php

namespace Muzich\CommentBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Muzich\CoreBundle\Managers\CommentsManager;

class CommentController extends Controller
{
  
  public function addAction($element_id)
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)))
    {
      throw $this->createNotFoundException('Not found');
    }
        
    // TODO: Faire un objet pour le formulaire ajout de commentaire
    if (
      strlen((($comment = $this->getRequest()->request->get('comment'))))
      >= $this->container->getParameter('comment_add_min_length')
    )
    {
      
      // On met a jour les commentaires
      $cm = new CommentsManager($element->getComments());
      $cm->add($this->getUser(), $comment);
      $element->setComments($cm->get());
      
      $this->getDoctrine()->getEntityManager()->persist($element);
      $this->getDoctrine()->getEntityManager()->flush();
      
      // On récupère le html du li avec le comment pour la réponse
      $html = $this->render('MuzichCommentBundle:Comment:comment.html.twig', array(
        'comment'     => $cm->getLast()
      ))->getContent();
      
      return $this->jsonResponse(array(
        'status' => 'success',
        'html'   => $html
      ));
      
    }
    else
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array($this->trans(
          'element.comments.add_error.min', 
          array(
            '%limit%' => $this->container->getParameter('comment_add_min_length')
          ), 
          'elements'
        )
      )));
    }
    
  }
  
}