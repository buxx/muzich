<?php

namespace Muzich\IndexBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Component\HttpFoundation\RedirectResponse;

class IndexController extends Controller
{
  
  /**
   * 
   * @Template()
   */
  public function indexAction()
  {
    $vars = $this->proceedLogin();
    $form = $this->container->get('fos_user.registration.form');
    
    return array_merge($vars, array(
      'form' => $form->createView()
    ));
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
        'registration_errors_pers' => array()
    );
  }
  
}