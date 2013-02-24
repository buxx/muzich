<?php

namespace Muzich\AdminBundle\Controller\Admin_user;

use Admingenerated\MuzichAdminBundle\BaseAdmin_userController\EditController as BaseEditController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class EditController extends BaseEditController
{
  
  protected function getUserContext($pk)
  {
    $User = $this->getObject($pk);
    if (!$User) {
        throw new NotFoundHttpException("The Muzich\CoreBundle\Entity\User with id $pk can't be found");
    }
    return $User;
  }
  
  protected function getFormPassword($User)
  {
    return $this->createFormBuilder($User)
      ->add('plain_password', 'text')
      ->getForm();
  }
  
  public function passwordAction($pk)
  {
    $User = $this->getUserContext($pk);
    $form = $this->getFormPassword($User);
    
    return $this->render('MuzichAdminBundle:Admin_userEdit:password.html.twig', array(
        "User" => $User,
        "form" => $form->createView(),
    ));
  }
  
  public function passwordUpdateAction($pk)
  {
    $User = $this->getUserContext($pk);
    $form = $this->getFormPassword($User);
    $form->bind($this->getRequest());
    
    if ($form->isValid())
    {
      $this->container->get('fos_user.user_manager')->updateUser($User);
      $em = $this->getDoctrine()->getManager();
      $em->persist($User);
      $em->flush();
      
      $this->get('session')->setFlash('success', $this->get('translator')->trans("object.edit.success", array(), 'Admingenerator') );
      return new RedirectResponse($this->generateUrl("Muzich_AdminBundle_Admin_user_list" ));
    }
    
    $this->get('session')->setFlash('error',  $this->get('translator')->trans("object.edit.error", array(), 'Admingenerator') );
    return $this->render('MuzichAdminBundle:Admin_userEdit:password.html.twig', array(
        "User" => $User,
        "form" => $form->createView(),
    ));
  }
  
}
