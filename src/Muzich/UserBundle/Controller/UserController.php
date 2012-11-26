<?php

namespace Muzich\UserBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Muzich\CoreBundle\Form\Tag\TagFavoritesForm;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Collection;

class UserController extends Controller
{
  
  protected $tags_favorites = null;
  
  protected function getChangeEmailForm()
  {
    $collectionConstraint = new Collection(array(
      'email' => new Email(array('message' => 'error.changeemail.email.invalid')),
    ));
    
    return $this->createFormBuilder(null, array(
      'validation_constraint' => $collectionConstraint,
    ))
      ->add('email', 'text')
      ->getForm()
    ;
  }
  
  protected function getTagsFavoritesForm($user)
  {
    $ids = array();
    foreach ($this->getTagsFavorites() as $id => $name)
    {
      $ids[] = $id;
    }
    
    return $this->createForm(
      new TagFavoritesForm(), 
      array('tags' => json_encode($ids))
    );
  }
  
  protected function getTagsFavorites($force = false)
  {
    if ($this->tags_favorites === null || $force)
    {
      $user = $this->getUser();
      
      /**
       * Bug lors des tests: L'user n'est pas 'lié' a celui en base par doctrine.
       * Docrine le voit si on faire une requete directe.
       */
      if ($this->container->getParameter('env') == 'test')
      {
        $user = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneById(
          $this->container->get('security.context')->getToken()->getUser()->getId(),
          array()
        )->getSingleResult();
      }
      
      $this->tags_favorites = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
        ->getTagsFavorites($user->getId())
      ;
    }
    
    return $this->tags_favorites;
  }
  
  /**
   * Page de configuration de son compte
   *
   * @Template()
   */
  public function accountAction()
  {
    $user = $this->getUser();
    $form_password = $this->container->get('fos_user.change_password.form');
    $form_tags_favorites = $this->getTagsFavoritesForm($user);
    $change_email_form = $this->getChangeEmailForm();
    
    return array(
      'user'                     => $user,
      'form_password'            => $form_password->createView(),
      'form_tags_favorites'      => $form_tags_favorites->createView(),
      'form_tags_favorites_name' => $form_tags_favorites->getName(),
      'favorite_tags_id'         => $this->getTagsFavorites(),
      'change_email_form'        => $change_email_form->createView()
    );
  }
  
  /**
   * Un bug étrange empêche la mise ne place de contraintes sur le formulaire
   * d'inscription. On effectue alors les vérifications ici.
   * 
   * C'est sale, mais ça marche ...
   * 
   * @return array of string errors
   */
  protected function checkRegistrationInformations($form)
  {
    $errors = array();
    $form->bindRequest($this->getRequest());
    $form_values = $this->getRequest()->request->get($form->getName());
    $user = $form->getData();
    
    /**
     * Contrôle du token
     */
    
    $r_token = $this->getDoctrine()->getRepository('MuzichCoreBundle:RegistrationToken')
      ->findOneBy(array('token' => $form_values["token"], 'used' => false))
    ;
      
    if (!$r_token)
    {
      $errors[] = $this->get('translator')->trans(
        'registration.token.error', 
        array(),
        'validators'
      );
    }
    else
    {
      $r_token->setUsed(true);
      $em = $this->getDoctrine()->getEntityManager();
      $em->persist($r_token);
      $em->flush();
    }
    
    /*
     * Contrôle de la taille du pseudo
     * min: 3
     * max: 32
     */
    if (strlen($user->getUsername()) < 3)
    {
      $errors[] = $this->get('translator')->trans(
        'error.registration.username.min', 
        array('%limit%' => 3),
        'validators'
      );
    }
    
    if (strlen($user->getUsername()) > 32)
    {
      $errors[] = $this->get('translator')->trans(
        'error.registration.username.max', 
        array('%limit%' => 32),
        'validators'
      );
    }
    
    /**
     * Mot de passes indentiques
     */
    if ($form_values['plainPassword']['first'] != $form_values['plainPassword']['second'])
    {
      $errors[] = $this->get('translator')->trans(
        'error.registration.password.notsame', 
        array(),
        'validators'
      );
    }
    
    return $errors;
  }
  
  public function registerAction()
  {
    $form = $this->container->get('fos_user.registration.form');
    $formHandler = $this->container->get('fos_user.registration.form.handler');
    $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');
    
    // Pour palier bug, verif interne
    if (count(($errors = $this->checkRegistrationInformations($form))) < 1)
    {
      $process = $formHandler->process($confirmationEnabled);
      if ($process) {
        $user = $form->getData();

        if ($confirmationEnabled) {
          $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
          $route = 'fos_user_registration_check_email';
        } else {
          $this->authenticateUser($user);
          $route = 'start';
        }

        $this->setFlash('fos_user_success', 'registration.flash.user_created');
        $url = $this->generateUrl($route);

        return new RedirectResponse($url);
      }
    }

    return $this->container->get('templating')->renderResponse(
      'MuzichIndexBundle:Index:index.html.twig',
      array(
        'form'                     => $form->createView(),
        'error'                    => null,
        'registration_errors'      => $form->getErrors(),
        'registration_errors_pers' => $errors,
        'last_username'            => null,
        'registration_page'        => true
      )
    );
  }
  
  /**
   * Un bug étrange empêche la mise ne place de contraintes sur le formulaire
   * d'inscription. On effectue alors les vérifications ici.
   * 
   * C'est sale, mais ça marche ...
   * 
   * @return array of string errors
   */
  protected function checkChangePasswordInformations($form)
  {
    $errors = array();
    $form_values = $this->getRequest()->request->get($form->getName());
    $user = $form->getData();
    
    /**
     * Mot de passes indentiques
     */
    if ($form_values['new']['first'] != $form_values['new']['second'])
    {
      $errors[] = $this->get('translator')->trans(
        'error.changepassword.new.notsame', 
        array(),
        'validators'
      );
    }
    
    return $errors;
  }
    
  public function changePasswordAction()
  {
    $user = $this->getUser();
    
    /**
     * Bug lors des tests: L'user n'est pas 'lié' a celui en base par doctrine.
     * Docrine le voit si on faire une requete directe.
     */
    if ($this->container->getParameter('env') == 'test')
    {
      $user = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneById(
        $this->container->get('security.context')->getToken()->getUser()->getId(),
        array()
      )->getSingleResult();
    }
    
    if (!is_object($user) || !$user instanceof UserInterface) {
        throw new AccessDeniedException('This user does not have access to this section.');
    }

    $form = $this->container->get('fos_user.change_password.form');
    $formHandler = $this->container->get('fos_user.change_password.form.handler');
    
    $process = $formHandler->process($user);
    if (count(($errors = $this->checkChangePasswordInformations($form))) < 1 && $process)
    {
      $this->container->get('session')->setFlash('fos_user_success', 'change_password.flash.success');
      return new RedirectResponse($this->generateUrl('my_account'));
    }
    else
    {
      $form_tags_favorites = $this->getTagsFavoritesForm($user);
      $change_email_form = $this->getChangeEmailForm();
      
      return $this->container->get('templating')->renderResponse(
        'MuzichUserBundle:User:account.html.twig',
        array(
          'form_password'            => $form->createView(),
          'errors_pers'              => $errors,
          'user'                     => $user,
          'form_tags_favorites'      => $form_tags_favorites->createView(),
          'form_tags_favorites_name' => $form_tags_favorites->getName(),
          'favorite_tags_id'         => $this->getTagsFavorites(),
          'change_email_form'        => $change_email_form->createView()
        )
      );
    }

    
  }
  
  /**
   * Page ouverte après l'inscription sur laquelle on propose de saisir ses
   * tags favoris.
   * 
   * @Template()
   */
  public function startAction()
  {
    $user = $this->getUser();
    $form_tags_favorites = $this->getTagsFavoritesForm($user);
    
    return array(
      'favorite_tags_id'         => $this->getTagsFavorites(),
      'form_tags_favorites'      => $form_tags_favorites->createView(),
      'form_tags_favorites_name' => $form_tags_favorites->getName(),
    );
  }
  
  /**
   *
   * @param string $redirect 
   */
  public function updateTagFavoritesAction($redirect)
  {
    $request = $this->getRequest();
    $user = $this->getUser(true, array('join' => array('favorites_tags')));
    
    /**
     * Bug lors des tests: L'user n'est pas 'lié' a celui en base par doctrine.
     * Docrine le voit si on faire une requete directe.
     */
    if ($this->container->getParameter('env') == 'test')
    {
      $user = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneById(
        $this->container->get('security.context')->getToken()->getUser()->getId(),
        array()
      )->getSingleResult();
    }
    
    $form = $this->getTagsFavoritesForm($user);
    
    if ($request->getMethod() == 'POST')
    {
      $form->bindRequest($request);
      if ($form->isValid())
      {
        $data = $form->getData();
        $user->updateTagsFavoritesById($this->getDoctrine()->getEntityManager(), $data['tags']);
        
        // On réinitialise l'eventuel session de recherche en mémoire
        $session = $this->get("session");
        $session->remove('user.element_search.params');
        
        $this->container->get('session')->setFlash('success', 'Vos tags péférés ont correctements été mis a jour.');
      }
      else
      {
        return $this->container->get('templating')->renderResponse(
          'MuzichUserBundle:User:start.html.twig',
          array(
            'form' => $form->createView()
          )
        );
      }
    }
    
    // (Il y aura aussi une redirection vers "mon compte / tags")
    if ($redirect == 'home')
    {
      return $this->redirect($this->generateUrl('home'));
    }
    else
    {
      return $this->redirect($this->generateUrl('my_account'));
    }
  }
  
  protected function checkChangeEmailFrequencies($user, $new_email)
  {
    $delay = $this->container->getParameter('changeemail_security_delay');
    if (($last_request_datetime = $user->getEmailRequestedDatetime()))
    {
      if ((time() - $last_request_datetime) < $delay)
      {
        return false;
      }
    }
    return true;
  }
  
  
  /**
   * Procédure de demande de changement de mot de passe
   */
  public function changeEmailRequestAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $user = $this->getUser();
    
    /**
     * Bug lors des tests: L'user n'est pas 'lié' a celui en base par doctrine.
     * Docrine le voit si on faire une requete directe.
     */
    if ($this->container->getParameter('env') == 'test')
    {
      $user = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneById(
        $this->container->get('security.context')->getToken()->getUser()->getId(),
        array()
      )->getSingleResult();
    }
    
    $request = $this->getRequest();
    $change_email_form = $this->getChangeEmailForm();
    
    $change_email_form->bindRequest($request);
    if ($change_email_form->isValid())
    {
      $data = $change_email_form->getData();
      $email = $data['email'];
      
      if (!$this->checkChangeEmailFrequencies($user, $email))
      {
        $this->setFlash('error', 'user.changeemail.wait');
        return new RedirectResponse($this->generateUrl('my_account'));
      }
      
      /*
       * Optimisation: Ecrire une lib Mailer pour gérer les envois.
       * cf le mailer de FOSUserBundle
       */
      
      // On renseigne en base l'email demandé
      $user->setEmailRequested($email);
      $user->setEmailRequestedDatetime(time());
      $user->generateConfirmationToken();
      $token = hash('sha256', $user->getConfirmationToken().$email);
      $url = $this->get('router')->generate('change_email_confirm', array('token' => $token), true);
      $rendered = $this->get('templating')->render('MuzichUserBundle:User:change_email_mail.txt.twig', array(
          'user' => $user,
          'confirmationUrl' => $url
      ));
      
      //$this->sendEmailMessage($rendered, $this->parameters['from_email']['resetting'], $user->getEmail());
      
      // Render the email, use the first line as the subject, and the rest as the body
      $renderedLines = explode("\n", trim($rendered));
      $subject = $renderedLines[0];
      $body = implode("\n", array_slice($renderedLines, 1));

      $message = \Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom('noreply@muzi.ch')
        ->setTo($email)
        ->setBody($body);

      $mailer = $this->get('mailer');
      $mailer->send($message);
      
      $this->setFlash('info', 'user.changeemail.mail_send');
      $em->flush();
      return new RedirectResponse($this->generateUrl('my_account'));
    }
    
    // En cas d'échec
    $form_password = $this->container->get('fos_user.change_password.form');
    $form_tags_favorites = $this->getTagsFavoritesForm($user);
    
    return $this->container->get('templating')->renderResponse(
      'MuzichUserBundle:User:account.html.twig',
      array(
        'user'                     => $user,
        'form_password'            => $form_password->createView(),
        'form_tags_favorites'      => $form_tags_favorites->createView(),
        'form_tags_favorites_name' => $form_tags_favorites->getName(),
        'favorite_tags_id'         => $this->getTagsFavorites(),
        'change_email_form'        => $change_email_form->createView()
      )
    );
  }
  
  
  
  /**
   * Procédure de confirmation de la nouvelle adresse email.
   */
  public function changeEmailConfirmAction($token)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $um = $this->get('muzich_user_manager');
    $user = $this->getUser();
    
    /**
     * Bug lors des tests: L'user n'est pas 'lié' a celui en base par doctrine.
     * Docrine le voit si on faire une requete directe.
     */
    if ($this->container->getParameter('env') == 'test')
    {
      $user = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneById(
        $this->container->get('security.context')->getToken()->getUser()->getId(),
        array()
      )->getSingleResult();
    }
    
    $token_ = hash('sha256', $user->getConfirmationToken().($email = $user->getEmailRequested()));
    
    // Le token est-il valide
    if ($token_ != $token)
    {
      $this->setFlash('error', 'user.changeemail.token_invalid');
      return new RedirectResponse($this->generateUrl('my_account'));
    }
    
    $user->setEmail($email);
    $user->setEmailRequested(null);
    $um->updateCanonicalFields($user);
    $em->flush();
    
    $this->setFlash('success', 'user.changeemail.success');
    return new RedirectResponse($this->generateUrl('my_account'));
  }
  
  /**
   *
   * @param string $town
   * @param string $country
   * @param string $token
   * @return Response 
   */
  public function updateAddressAction($token)
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    /**
     * Bug lors des tests: L'user n'est pas 'lié' a celui en base par doctrine.
     * Docrine le voit si on faire une requete directe.
     */
    $user = $this->getUser();
    if ($this->container->getParameter('env') == 'test')
    {
      $user = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneById(
        $this->container->get('security.context')->getToken()->getUser()->getId(),
        array()
      )->getSingleResult();
    }
    
    $errors = array();
    if ($user->getPersonalHash() != $token)
    {
      $errors[] = 'NotAllowed';
    }
    
    if (!trim($this->getRequest()->request->get('town')))
    {
      $errors[] = $this->trans('my_account.address.form.errors.notown', array(), 'userui');
    }
    if (!trim($this->getRequest()->request->get('country')))
    {
      $errors[] = $this->trans('my_account.address.form.errors.nocountry', array(), 'userui');
    }
    
    if (count($errors))
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => $errors
      ));
    }
    
    $user->setTown(trim($this->getRequest()->request->get('town')));
    $user->setCountry(trim($this->getRequest()->request->get('country')));
    $this->getDoctrine()->getEntityManager()->persist($user);
    $this->getDoctrine()->getEntityManager()->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
    
}
