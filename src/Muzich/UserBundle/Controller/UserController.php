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
      $this->container->get('session')->setFlash('fos_user_success', 'change_password.flash.success');
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
    
}
