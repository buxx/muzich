<?php

namespace Muzich\IndexBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\HttpFoundation\Request;
use Muzich\UserBundle\Form\Type\RegistrationFormType;
use Muzich\CoreBundle\Entity\User;

class IndexController extends Controller
{
  
  /**
   * 
   */
  public function indexAction()
  {
    // On rajoute le test sur l'environnement car dans les tests, d'un test a l'autre
    // l'utilisateur reste connecté et pas moyen de le déco ...
    if ($this->getUser() != 'anon.' && $this->container->getParameter('env') != 'test')
    {
      return $this->redirect($this->generateUrl('home'));
    }
    
    $vars = $this->proceedLogin();
    $form = $this->getRegistrationForm();
    
    return $this->render('MuzichIndexBundle:Index:index.html.twig', array_merge($vars, array(
      'form' => $form->createView(),
      'presubscription_form' => $this->getPreSubscriptionForm()->createView()
    )));
  }
  
  protected function getRegistrationForm()
  {
    return $this->createForm(new RegistrationFormType(), new User());
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
    /* @var $session Symfony\Component\HttpFoundation\Session\Session */

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
        $error = $this->trans('login.fail', array(), 'users');
    }
    // last username entered by the user
    $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);
    
    return array(
        'last_username' => $lastUsername,
        'error'         => $error,
        'registration_errors_pers' => array()
    );
  }
  
//  public function presubscriptionAction(Request $request)
//  {
//    $form = $this->getPreSubscriptionForm();
//    $form->bind($request);
//    if ($form->isValid())
//    {
//      $message = \Swift_Message::newInstance()
//        ->setSubject($this->trans('mail.presubscription.subject', array(), 'text'))
//        ->setFrom(array(
//          $this->container->getParameter('emails_from') => $this->container->getParameter('emails_from_name')
//        ))
//        ->setTo($form->getData()->getEmail())
//        ->setBody(
//          $this->renderView(
//            'MuzichIndexBundle:Presubscription:confirm.txt.twig',
//            array(
//              'url' => $this->generateUrl('presubscription_register_confirm', array(
//                'token' => $form->getData()->getToken()
//              ), true)
//            )
//          )
//        )
//      ;
//      $message->getHeaders()->addTextHeader('List-Unsubscribe', 'unsubscribe@muzi.ch');
//      
//      $this->get('mailer')->send($message);
//      
//      
//      $this->persist($form->getData());
//      $this->flush();
//      $this->setFlash('info', 'presubscription.success');
//      return $this->redirect($this->generateUrl('index'));
//    }
//    
//    $this->setFlash('error', 'presubscription.error');
//    return $this->render('MuzichIndexBundle:Index:index.html.twig', array(
//      'form' => $this->getRegistrationForm()->createView(),
//      'presubscription_form' => $form->createView(),
//      'last_username' => '',
//      'error'         => '',
//      'registration_errors_pers' => array()
//    ));
//  }
//  
//  public function presubscriptionConfirmAction($token)
//  {
//    $presubscription = $this->getDoctrine()->getRepository('MuzichCoreBundle:Presubscription')->findOneBy(array(
//      'token'     => $token,
//      'confirmed' => false
//    ));
//    
//    if (!$presubscription)
//    {
//      throw $this->createNotFoundException();
//    }
//    
//    $presubscription->setConfirmed(true);
//    $this->persist($presubscription);
//    $this->flush();
//    
//    $this->setFlash('success', 'presubscription.confirmed');
//    return $this->redirect($this->generateUrl('index'));
//  }
  
}