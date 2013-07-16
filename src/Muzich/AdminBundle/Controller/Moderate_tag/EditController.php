<?php

namespace Muzich\AdminBundle\Controller\Moderate_tag;

use Admingenerated\MuzichAdminBundle\BaseModerate_tagController\EditController as BaseEditController;
use Muzich\CoreBundle\Managers\TagManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EditController extends BaseEditController
{
  
  protected function getTagContext($pk)
  {
    //$Tag = $this->getObject($pk); Error ?!
    $Tag = $tag = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneBy(array(
      'id'         => $pk,
      'tomoderate' => true
    ));
    if (!$Tag) {
        throw new NotFoundHttpException("The Muzich\CoreBundle\Entity\Tag with id $pk can't be found");
    }
    return $Tag;
  }
  
  public function acceptAction($pk)
  {
    $tag = $this->getTagContext($pk);
    $tagManager = new TagManager();
    $tagManager->moderateTag($this->getDoctrine(), $tag, true);
    
    if (!$this->getRequest()->isXmlHttpRequest())
    {
      $this->get('session')->setFlash('success', $this->get('translator')->trans("object.edit.success", array(), 'Admingenerator') );
      return new RedirectResponse($this->generateUrl("Muzich_AdminBundle_Moderate_tag_list" ));
    }
    return $this->getJsonEmptyResponse();
  }
  
  protected function getJsonEmptyResponse()
  {
    $response = new Response(json_encode(array()));
    $response->headers->set('Content-Type', 'application/json; charset=utf-8');
    return $response;
  }
  
  public function refuseAction($pk)
  {
    $tag = $this->getTagContext($pk);
    $tagManager = new TagManager();
    $tagManager->moderateTag($this->getDoctrine(), $tag, false);
    
    $uids = json_decode($tag->getPrivateids(), true);
    
    $users = $this->getDoctrine()->getManager()
      ->createQuery('
        SELECT u FROM MuzichCoreBundle:User u
        WHERE u.id IN (:uids)'
      )
      ->setParameter('uids', $uids)
      ->getResult()
    ;
    
    foreach ($users as $user)
    {
      $user->addModeratedTagCount();
      $this->getDoctrine()->getManager()->persist($user);
    }
    
    $this->getDoctrine()->getManager()->flush();
    
    if (!$this->getRequest()->isXmlHttpRequest())
    {
      $this->get('session')->setFlash('success', $this->get('translator')->trans("object.edit.success", array(), 'Admingenerator') );
      return new RedirectResponse($this->generateUrl("Muzich_AdminBundle_Moderate_tag_list" ));
    }
    return $this->getJsonEmptyResponse();
  }
  
}
