<?php

namespace Muzich\AdminBundle\Controller\Moderate_tag;

use Admingenerated\MuzichAdminBundle\BaseModerate_tagController\EditController as BaseEditController;
use Muzich\CoreBundle\Managers\TagManager;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
    
    $this->get('session')->setFlash('success', $this->get('translator')->trans("object.edit.success", array(), 'Admingenerator') );
    return new RedirectResponse($this->generateUrl("Muzich_AdminBundle_Moderate_tag_list" ));
  }
  
  public function refuseAction($pk)
  {
    $tag = $this->getTagContext($pk);
    $tagManager = new TagManager();
    $tagManager->moderateTag($this->getDoctrine(), $tag, false);
    
    $uids = json_decode($tag->getPrivateids(), true);
    
    $users = $this->getDoctrine()->getEntityManager()
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
      $this->getDoctrine()->getEntityManager()->persist($user);
    }
    
    $this->getDoctrine()->getEntityManager()->flush();
    
    $this->get('session')->setFlash('success', $this->get('translator')->trans("object.edit.success", array(), 'Admingenerator') );
    return new RedirectResponse($this->generateUrl("Muzich_AdminBundle_Moderate_tag_list" ));
  }
  
}
