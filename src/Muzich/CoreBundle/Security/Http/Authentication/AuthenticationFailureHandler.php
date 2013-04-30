<?php

namespace Muzich\CoreBundle\Security\Http\Authentication;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Doctrine\ORM\EntityManager;
use Muzich\CoreBundle\Entity\User;

/**
 * Custom authentication success handler
 */
class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{

   private $router;
   private $em;
   private $translator;

   /**
    * Constructor
    * @param RouterInterface   $router
    * @param EntityManager     $em
    */
   public function __construct(RouterInterface $router, EntityManager $em, Translator $translator)
   {
      $this->translator = $translator;
      $this->router = $router;
      $this->em = $em;
   }

   function onAuthenticationFailure(Request $request, AuthenticationException $exception)
   {
      if ($request->isXmlHttpRequest())
      {
        $response = new Response(json_encode($this->getResponseParameters($request)));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
      }
      
      return new RedirectResponse($this->router->generate('index'));
   }
   
   protected function getResponseParameters(Request $request)
   {
    $session = $request->getSession();
    return array(
      'status' => 'error',
      'data'   => array(
        'error' => $this->translator->trans('login.fail', array(), 'users')
      )
    );
   }
   
}