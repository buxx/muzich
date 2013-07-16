<?php

namespace Muzich\AdminBundle\Controller\Moderate_user;

use Muzich\CoreBundle\lib\Controller as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EditController extends BaseController
{
  
  protected function getUserContext($user_id)
  {
    $user = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
      ->findOneById($user_id)->getSingleResult();
    
    if (!$user) {
        throw new NotFoundHttpException("The Muzich\CoreBundle\Entity\User with id $user_id can't be found");
    }
    return $user;
  }
  
  public function disableAndDeleteAllHisElementsAction($pk)
  {
    $user = $this->getUserContext($pk);
    $user->setEnabled(false);
    $this->persist($user);
    foreach ($user->getElements() as $element)
    {
      $this->remove($element);
    }
    
    try
    {
      $this->flush();
      $this->get('session')->setFlash('success', $this->get('translator')->trans("object.edit.success", array(), 'Admingenerator') );
    }
    catch (\Exception $e)
    {
      throw $e;
      $this->get('session')->setFlash('error', $this->get('translator')->trans("object.edit.error", array(), 'Admingenerator') );
    }
    
    return new RedirectResponse($this->generateUrl("Muzich_AdminBundle_Admin_user_show", array(
      'pk' => $pk
    )));
  }
  
  public function acceptAction($element_id, $date)
  {
    $element = $this->getElementContext($element_id); 
    $cm = new CommentsManager($element->getComments());
    // On nettoie le commentaire et on récupère les ids des "signaleurs"
    $ids = $cm->cleanAlertsOnComment($date);
    $element->setComments($cm->get());
    $element->setCountCommentReport($cm->countCommentAlert());
    
    $this->getDoctrine()->getManager()->persist($element);
    
    // On récupère les user qui ont signalés ce commentaire
    $users = $this->getDoctrine()->getManager()
      ->createQuery('
        SELECT u FROM MuzichCoreBundle:User u
        WHERE u.id IN (:uids)'
      )
      ->setParameter('uids', $ids)
      ->getResult()
    ;
    
    // Pour chacun on augmente le compteur de signalements inutiles
    foreach ($users as $user)
    {
      $user->addBadReport();
      $this->getDoctrine()->getManager()->persist($user);
    }
    
    $this->getDoctrine()->getManager()->flush();
    
    if (!$this->getRequest()->isXmlHttpRequest())
    {
      $this->get('session')->setFlash('success', $this->get('translator')->trans("object.edit.success", array(), 'Admingenerator') );
      return new RedirectResponse($this->generateUrl("Muzich_AdminBundle_Moderate_comment_list" ));
    }
    return $this->getJsonEmptyResponse();
  }
  
  protected function getJsonEmptyResponse()
  {
    $response = new Response(json_encode(array()));
    $response->headers->set('Content-Type', 'application/json; charset=utf-8');
    return $response;
  }
  
  public function refuseAction($element_id, $date)
  {
    $element = $this->getElementContext($element_id); 
    $cm = new CommentsManager($element->getComments());
    $comment = $cm->get($cm->getIndexWithDate($date));
    // On supprime le commentaire
    $cm->deleteWithDate($date);
    $element->setComments($cm->get());
    $element->setCountCommentReport($cm->countCommentAlert());
    
    // On récupère l'auteur du commentaire pour lui incrémenté son compteur
    // de contenu modéré
    $user = $this->getDoctrine()->getManager()->getRepository('MuzichCoreBundle:User')
      ->findOneBy(array(
        'id' => $comment['u']['i']
      ));
    
    $user->addModeratedCommentCount();
    
    $this->getDoctrine()->getManager()->persist($user);
    $this->getDoctrine()->getManager()->persist($element);
    $this->getDoctrine()->getManager()->flush();
    
    if (!$this->getRequest()->isXmlHttpRequest())
    {
      $this->get('session')->setFlash('success', $this->get('translator')->trans("object.edit.success", array(), 'Admingenerator') );
      return new RedirectResponse($this->generateUrl("Muzich_AdminBundle_Moderate_comment_list" ));
    }
    return $this->getJsonEmptyResponse();
  }
  
}
