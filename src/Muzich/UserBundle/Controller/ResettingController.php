<?php

namespace Muzich\UserBundle\Controller;

use FOS\UserBundle\Controller\ResettingController as BaseResettingController;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 * @author bux
 */
class ResettingController extends BaseResettingController
{
  
  /**
   * Reset user password
   */
  public function resetAction($token)
  {
      $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);

      if (null === $user){
          throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
      }

      if (!$user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
          return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_request'));
      }

      $form = $this->container->get('fos_user.resetting.form');
      $formHandler = $this->container->get('fos_user.resetting.form.handler');
      $process = $formHandler->process($user);

      if ($process) {
          $this->authenticateUser($user);

          $this->setFlash('success', 'Votre mot de passe a été mis a jour avec succés.');

          return new RedirectResponse($this->getRedirectionUrl($user));
      }

      return $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:reset.html.'.$this->getEngine(), array(
          'token' => $token,
          'form' => $form->createView(),
          'theme' => $this->container->getParameter('fos_user.template.theme'),
      ));
  }

  /**
   * Generate the redirection url when the resetting is completed.
   *
   * @param UserInterface $user
   * @return string
   */
  protected function getRedirectionUrl(UserInterface $user)
  {
      return $this->container->get('router')->generate('my_account');
  }
  
}

?>
