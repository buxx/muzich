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
   * Generate the redirection url when the resetting is completed.
   *
   * @param UserInterface $user
   * @return string
   */
  protected function getRedirectionUrl(UserInterface $user)
  {
      return $this->container->get('router')->generate('my_account');
  }
  
  /**
   * Tell the user to check his email provider
   * Réécriture de la fonction, il manque 'user' pour le template
   */
  public function checkEmailAction()
  {
    $session = $this->container->get('session');
    $email = $session->get(static::SESSION_EMAIL);
    $session->remove(static::SESSION_EMAIL);

    if (empty($email)) {
      // the user does not come from the sendEmail action
      return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_request'));
    }

    return $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:checkEmail.html.'.$this->getEngine(), array(
      'email' => $email, 'user' => $this->container->get('security.context')->getToken()->getUser()
    ));
  }
  
}

?>
