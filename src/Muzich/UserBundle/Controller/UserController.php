<?php

namespace Muzich\UserBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

class UserController extends Controller
{
  /**
   * @Template()
   */
  public function accountAction()
  {
    $user = $this->getUser();

    $form_password = $this->container->get('fos_user.change_password.form');

      return array(
        'user' => $user,
        'form_password' => $form_password->createView()
      );
  }
  
  public function registerAction()
  {
    $form = $this->container->get('fos_user.registration.form');
    $formHandler = $this->container->get('fos_user.registration.form.handler');
    $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');
    
    $process = $formHandler->process($confirmationEnabled);
    if ($process) {
      $user = $form->getData();

      if ($confirmationEnabled) {
        $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
        $route = 'fos_user_registration_check_email';
      } else {
        $this->authenticateUser($user);
        $route = 'register_finish';
      }

      $this->setFlash('success', 'Votre compte a bien été créé');
      $url = $this->generateUrl($route);

      return new RedirectResponse($url);
    }

    return $this->container->get('templating')->renderResponse(
      'MuzichIndexBundle:Index:index.html.twig',
      array(
        'form' => $form->createView(),
        'error' => null,
        'last_username' => null
      )
    );
  }
    
  public function changePasswordAction()
  {
    $user = $this->getUser();
    if (!is_object($user) || !$user instanceof UserInterface) {
        throw new AccessDeniedException('This user does not have access to this section.');
    }

    $form = $this->container->get('fos_user.change_password.form');
    $formHandler = $this->container->get('fos_user.change_password.form.handler');
    
    $process = $formHandler->process($user);
    if ($process)
    {
      $this->container->get('session')->setFlash('success', 'Le mot de passe a été changé avec succès.');
      return new RedirectResponse($this->generateUrl('my_account'));
    }
    else
    {
      return $this->container->get('templating')->renderResponse(
        'MuzichUserBundle:User:account.html.twig',
        array(
          'form_password' => $form->createView(),
          'user' => $user
        )
      );
    }

    
  }
  
  /**
   * 
   * @Template()
   */
  public function registerFinishAction()
  {
    
    return array();
  }
    
}
