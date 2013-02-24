<?php

namespace Muzich\AdminBundle\Controller\Moderate_element;

use Admingenerated\MuzichAdminBundle\BaseModerate_elementController\EditController as BaseEditController;
use Muzich\CoreBundle\Propagator\EventElement;
use Symfony\Component\HttpFoundation\RedirectResponse;

class EditController extends BaseEditController
{
  
  protected function getElementContext($pk)
  {
    $Element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($pk);
    if (!$Element) {
        throw new NotFoundHttpException("The Muzich\CoreBundle\Entity\Element with id $pk can't be found");
    }
    return $Element;
  }
  
  public function acceptAction($pk)
  {
    $element = $this->getElementContext($pk);
    $user_ids = $element->getReportIds();
    $element->setReportIds(null);
    $element->setCountReport(null);
    $this->getDoctrine()->getEntityManager()->persist($element);
    
    $users = $this->getDoctrine()->getEntityManager()
      ->createQuery('
        SELECT u FROM MuzichCoreBundle:User u
        WHERE u.id IN (:uids)'
      )
      ->setParameter('uids', $user_ids)
      ->getResult()
    ;
    
    foreach ($users as $user)
    {
      $user->addBadReport();
      $this->getDoctrine()->getEntityManager()->persist($user);
    }
    
    $this->getDoctrine()->getEntityManager()->flush();
    
    $this->get('session')->setFlash('success', $this->get('translator')->trans("object.edit.success", array(), 'Admingenerator') );
    return new RedirectResponse($this->generateUrl("Muzich_AdminBundle_Moderate_element_list" ));
  }
  
  public function refuseAction($pk)
  {
    $element = $this->getElementContext($pk);
    $event = new EventElement($this->container);
    $event->elementRemoved($element);
    $element->getOwner()->addModeratedElementCount();
    
    $this->getDoctrine()->getEntityManager()->persist($element->getOwner());
    $this->getDoctrine()->getEntityManager()->remove($element);
    $this->getDoctrine()->getEntityManager()->flush();
    
    $this->get('session')->setFlash('success', $this->get('translator')->trans("object.edit.success", array(), 'Admingenerator') );
    return new RedirectResponse($this->generateUrl("Muzich_AdminBundle_Moderate_element_list" ));
  }
  
}
