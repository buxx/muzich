<?php

namespace Muzich\AdminBundle\Controller\Admin_element;

use Admingenerated\MuzichAdminBundle\BaseAdmin_elementController\EditController as BaseEditController;
use Symfony\Component\Form\Form;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Managers\ElementManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EditController extends BaseEditController
{
  
  protected function getElementContext($pk)
  {
    $Element = $this->getObject($pk);
    if (!$Element) {
        throw new NotFoundHttpException("The Muzich\CoreBundle\Entity\Element with id $pk can't be found");
    }
    return $Element;
  }
  
  public function regenerateAction($pk)
  {
    $Element = $this->getElementContext($pk);
    $em = $this->getDoctrine()->getManager();
    $factory = new ElementManager($Element, $em, $this->container);
    $factory->regenerate();
    $em->persist($Element);
    $em->flush();
    
    $this->get('session')->setFlash('success', $this->get('translator')->trans("object.edit.success", array(), 'Admingenerator') );
    return new RedirectResponse($this->generateUrl("Muzich_AdminBundle_Admin_element_list" ));
  }
  
}
