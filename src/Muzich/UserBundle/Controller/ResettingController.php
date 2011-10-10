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
  
}

?>
