<?php

namespace Muzich\IndexBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\UserBundle\Controller\SecurityController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Muzich\CoreBundle\Entity\Tag;
use Muzich\CoreBundle\Entity\UsersTagsFavorites;
use Muzich\CoreBundle\Entity\User;

class IndexController extends BaseController
{
  
  /**
   * 
   * @Template()
   */
  public function indexAction()
  {
    $vars = $this->proceedLogin();
    $vars = array_merge($vars, $this->proceedRegister());
    return $vars;
  }
  
  /**
   * Gestion du formulaire d'identification sur la page d'index.
   * 
   * @return type array
   */
  protected function proceedLogin()
  {
    $request = $this->container->get('request');
    /* @var $request \Symfony\Component\HttpFoundation\Request */
    $session = $request->getSession();
    /* @var $session \Symfony\Component\HttpFoundation\Session */

    // get the error if any (works with forward and redirect -- see below)
    if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
        $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
    } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
        $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        $session->remove(SecurityContext::AUTHENTICATION_ERROR);
    } else {
        $error = '';
    }

    if ($error) {
        // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
        $error = $error->getMessage();
    }
    // last username entered by the user
    $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

    return array(
        'last_username' => $lastUsername,
        'error'         => $error,
    );
  }
  
  /**
   * Gestion du formulaire d'inscription sur la page d'index.
   * 
   * @return type array
   */
  protected function proceedRegister()
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
            $route = 'fos_user_registration_confirmed';
        }

        $this->setFlash('fos_user_success', 'registration.flash.user_created');
        $url = $this->container->get('router')->generate($route);

        return new RedirectResponse($url);
    }

    return array(
        'form' => $form->createView(),
        'theme' => $this->container->getParameter('fos_user.template.theme'),
    );
  }
  
}