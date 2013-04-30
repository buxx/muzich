<?php

namespace Muzich\CoreBundle\Security\Http\Authentication;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Muzich\CoreBundle\Entity\User;

/**
 * Custom authentication success handler
 */
class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{

   private $router;
   private $em;

   /**
    * Constructor
    * @param RouterInterface   $router
    * @param EntityManager     $em
    */
   public function __construct(RouterInterface $router, EntityManager $em)
   {
      $this->router = $router;
      $this->em = $em;
   }

   /**
    * This is called when an interactive authentication attempt succeeds. This
    * is called by authentication listeners inheriting from AbstractAuthenticationListener.
    * @param Request        $request
    * @param TokenInterface $token
    * @return Response The response to return
    */
   function onAuthenticationSuccess(Request $request, TokenInterface $token)
   {
      if ($request->isXmlHttpRequest())
      {
        $response = new Response(json_encode(array('status' => 'success')));
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        return $response;
      }
      
      return new RedirectResponse($this->router->generate('home'));
   }
}