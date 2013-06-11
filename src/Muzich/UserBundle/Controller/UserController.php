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
use Symfony\Component\HttpFoundation\Request;
use Muzich\UserBundle\Form\Type\RegistrationFormType;
use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Form\User\PasswordForm;
use Muzich\CoreBundle\Form\User\PrivacyForm;
use Muzich\CoreBundle\Form\User\DeleteForm;

class UserController extends Controller
{
  
  protected $tags_favorites = null;
  
  protected function getChangeEmailForm()
  {
    $collectionConstraint = new Collection(array(
      'email' => new Email(array('message' => 'error.changeemail.email.invalid')),
    ));
    
    return $this->createFormBuilder(null, array(
      //'validation_constraint' => $collectionConstraint, UPGRADE 2.1
      'constraints' => $collectionConstraint,
    ))
      ->add('email', 'text')
      ->getForm()
    ;
  }
  
  protected function getPreferencesForm()
  {
    return $this->createFormBuilder($this->getUser())
      ->add('mail_newsletter', 'checkbox', array('required' => false))
      ->add('mail_partner', 'checkbox', array('required' => false))
      ->getForm()
    ;
  }
  
  protected function getPrivacyForm()
  {
    return $this->createForm(new PrivacyForm(), $this->getUser());
  }
  
  protected function getDeleteForm()
  {
    return $this->createForm(new DeleteForm(), $this->getUser());
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
    $form_password = $this->getChangePasswordForm($user);
    $form_tags_favorites = $this->getTagsFavoritesForm($user);
    $change_email_form = $this->getChangeEmailForm();
    
    return array(
      'user'                     => $user,
      'form_password'            => $form_password->createView(),
      'form_tags_favorites'      => $form_tags_favorites->createView(),
      'form_tags_favorites_name' => $form_tags_favorites->getName(),
      'favorite_tags_id'         => $this->getTagsFavorites(),
      'change_email_form'        => $change_email_form->createView(),
      'avatar_form'              => $this->getAvatarForm()->createView(),
      'preferences_form'         => $this->getPreferencesForm()->createView(),
      'privacy_form'             => $this->getPrivacyForm()->createView(),
      'delete_form'              => $this->getDeleteForm()->createView()
    );
  }
  
  protected function getChangePasswordForm(User $user)
  {
    return $this->createForm(new PasswordForm(), $user);
  }
  
  protected function getAvatarForm()
  {
    return $this->createFormBuilder($this->getUser())
      ->add('avatar')
      ->getForm()
    ;
  }
  
  public function registerAction(Request $request)
  {
    $userManager = $this->container->get('fos_user.user_manager');
    $user = $this->getNewUser($userManager);
    $form = $this->getRegistrationForm($user);
    $form->bindRequest($request);
    $errors = $this->checkRegistrationValues($form);
    
    if ($form->isValid() && !count($errors))
    {
      $response = $this->getSuccessRegistrationResponse();
      $userManager->updateUser($user);
      $this->authenticateUser($user, $response);
      $this->sendEmailconfirmationEmail(false);
      return $response;
    }
    
    return $this->getFailureRegistrationResponse($form, $errors);
  }
  
  protected function getRegistrationForm(User $user)
  {
    return $this->createForm(new RegistrationFormType(), $user);
  }
  
  /** @return User */
  protected function getNewUser()
  {
    return $this->container->get('muzich_user_manager')->getNewReadyUser();
  }
  
  protected function checkRegistrationValues($form)
  {
    if(!filter_var($form->getData()->getEmailCanonical(), FILTER_VALIDATE_EMAIL))
    {
      return array($this->trans('registration.email.invalid', array(), 'validators'));
    }
    
    $count = $this->getEntityManager()->createQuery("SELECT count(u.id) "
      ."FROM MuzichCoreBundle:User u "
      ."WHERE UPPER(u.email) = :email_canonical")
      ->setParameter('email_canonical', strtoupper($form->getData()->getEmailCanonical()))
      ->getSingleScalarResult()
    ;
    
    if ($count)
    {
      return array($this->trans('error.registration.email.duplicate', array(), 'validators'));
    }
    return array();
  }
  
  protected function getSuccessRegistrationResponse()
  {
    if (!$this->getRequest()->isXmlHttpRequest())
    {
      return new RedirectResponse($this->generateUrl('home'));
    }
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
  
  protected function getFailureRegistrationResponse($form, $errors = array())//, $formHandler)
  {
    $parameters = array(
      'form'                     => $form->createView(),
      'error'                    => null,
      'registration_errors'      => $form->getErrors(),
      'registration_errors_pers' => $errors,
      'last_username'            => null,
      'registration_page'        => true,
      'presubscription_form'     => $this->getPreSubscriptionForm()->createView()
    );
    
    if (!$this->getRequest()->isXmlHttpRequest())
    {
      return $this->render(
        'MuzichIndexBundle:Index:index.html.twig',
        $parameters
      );
    }
    
    return $this->jsonResponse(array(
      'status' => 'error',
      'data'   => array(
        'html' => $this->render(
          'MuzichUserBundle:Registration:register_form_content.html.twig',
          $parameters
        )->getContent()
      )
    ));
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
    
  public function changePasswordAction(Request $request)
  {
    $user = $this->getUser();
    $form = $this->getChangePasswordForm($user);
    $form->bind($request);
    
    if ($form->isValid())
    {
      $userManager = $this->container->get('fos_user.user_manager');
      $userManager->updateUser($form->getData());
      $form->getData()->setPasswordSet(true);
      $this->persist($form->getData());
      $this->flush();
      $this->container->get('session')->setFlash('fos_user_success', 'change_password.flash.success');
      return new RedirectResponse($this->generateUrl('home'));
    }
    
    $form_tags_favorites = $this->getTagsFavoritesForm($user);

    return $this->container->get('templating')->renderResponse(
      'MuzichUserBundle:User:account.html.twig',
      array(
        'form_password'            => $form->createView(),
        'errors_pers'              => array(),
        'user'                     => $user,
        'form_tags_favorites'      => $form_tags_favorites->createView(),
        'form_tags_favorites_name' => $form_tags_favorites->getName(),
        'favorite_tags_id'         => $this->getTagsFavorites(),
        'change_email_form'        => $this->getChangeEmailForm()->createView(),
        'avatar_form'              => $this->getAvatarForm()->createView(),
        'preferences_form'         => $this->getPreferencesForm()->createView(),
        'privacy_form'             => $this->getPrivacyForm()->createView(),
        'delete_form'              => $this->getDeleteForm()->createView()
      )
    );
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
  public function updateTagFavoritesAction(Request $request, $redirect)
  {
    $request = $this->getRequest();
    $user = $this->getUser(true, array('join' => array('favorites_tags')));
    $form = $this->getTagsFavoritesForm($user);
    
    if ($request->getMethod() == 'POST')
    {
      $form->bind($request);
      if ($form->isValid())
      {
        $data = $form->getData();
        $user->updateTagsFavoritesById($this->getDoctrine()->getEntityManager(), $data['tags']);
        
        // On réinitialise l'eventuel session de recherche en mémoire
        $session = $this->get("session");
        $session->remove('user.element_search.params');
      }
      else
      {
        if ($request->isXmlHttpRequest())
        {
          return $this->jsonResponse(array(
            'status' => 'error',
            'data'   => $this->render('MuzichUserBundle:User:helpbox_favorite_tags.html.twig', array( 
              'form'      => $form->createView(),
              'form_name' => 'favorites_tags_helpbox'
            ))->getContent()
          ));
        }
        
        return $this->container->get('templating')->renderResponse(
          'MuzichUserBundle:User:start.html.twig',
          array(
            'form' => $form->createView()
          )
        );
      }
    }
    
    if ($request->isXmlHttpRequest())
    {
      return $this->jsonResponse(array(
        'status' => 'success'
      ));
    }
    
    $this->container->get('session')->setFlash('success', 'Vos tags péférés ont correctements été mis a jour.');
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
    
    $request = $this->getRequest();
    $change_email_form = $this->getChangeEmailForm();
    
    $change_email_form->bind($request);
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
      
      //$user->generateConfirmationToken(); UPGRADE FOSUserBundle 1.3
      $tokenGenerator = $this->container->get('fos_user.util.token_generator');
      $user->setConfirmationToken($tokenGenerator->generateToken());
      
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
        ->setFrom('contact@muzi.ch')
        ->setTo($email)
        ->setBody($body);

      $mailer = $this->get('mailer');
      $mailer->send($message);
      
      $this->setFlash('success', 'user.changeemail.mail_send');
      $em->flush();
      return new RedirectResponse($this->generateUrl('my_account'));
    }
    
    // En cas d'échec
    $form_password = $this->getChangePasswordForm($user);
    $form_tags_favorites = $this->getTagsFavoritesForm($user);
    
    return $this->container->get('templating')->renderResponse(
      'MuzichUserBundle:User:account.html.twig',
      array(
        'user'                     => $user,
        'form_password'            => $form_password->createView(),
        'form_tags_favorites'      => $form_tags_favorites->createView(),
        'form_tags_favorites_name' => $form_tags_favorites->getName(),
        'favorite_tags_id'         => $this->getTagsFavorites(),
        'change_email_form'        => $change_email_form->createView(),
        'avatar_form'              => $this->getAvatarForm()->createView(),
        'preferences_form'         => $this->getPreferencesForm()->createView(),
        'privacy_form'             => $this->getPrivacyForm()->createView(),
        'delete_form'              => $this->getDeleteForm()->createView()
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
    
    $user = $this->getUser();
    
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
  
  public function updateAvatarAction(Request $request)
  {
    $form = $this->getAvatarForm();
    $form->bind($request);
    
    if ($form->isValid()) {
      $em = $this->getEntityManager();
      $form->getData()->preUploadAvatar();
      $form->getData()->uploadAvatar();
      $em->persist($form->getData());
      $em->flush();

      $this->setFlash('success',
        $this->trans('my_account.avatar.success', array(), 'userui'));
      return $this->redirect($this->generateUrl('my_account'));
    }
    
    $this->setFlash('error',
      $this->trans('my_account.avatar.error', array(), 'userui'));
    return $this->redirect($this->generateUrl('my_account'));
  }
  
  public function updatePreferencesAction(Request $request)
  {
    $form = $this->getPreferencesForm();
    $form->bind($request);
    
    if ($form->isValid()) {
      $em = $this->getEntityManager();
      $em->persist($form->getData());
      $em->flush();

      $this->setFlash('success',
        $this->trans('my_account.preferences.success', array(), 'userui'));
      return $this->redirect($this->generateUrl('my_account'));
    }
    
    $this->setFlash('error',
      $this->trans('my_account.preferences.error', array(), 'userui'));
    return $this->redirect($this->generateUrl('my_account'));
  }
  
  public function updatePrivacyAction(Request $request)
  {
    $form = $this->getPrivacyForm();
    $form->bind($request);
    
    if ($form->isValid()) {
      $em = $this->getEntityManager();
      $em->persist($form->getData());
      $em->flush();

      $this->setFlash('success',
        $this->trans('my_account.privacy.success', array(), 'userui'));
      return $this->redirect($this->generateUrl('my_account'));
    }
    
    $this->setFlash('error',
      $this->trans('my_account.privacy.error', array(), 'userui'));
    return $this->redirect($this->generateUrl('my_account'));
  }
  
  public function updateHelpViewedAction($help_id, $token)
  {
    if ($this->getUser()->getPersonalHash('updateHelpAction') != $token)
    {
      return $this->jsonNotFoundResponse();
    }
    
    $this->getUser()->setSeeHelp($help_id, false);
    $this->persist($this->getUser());
    $this->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
  
  public function subscribeOrLoginAction(Request $request)
  {
    return $this->jsonResponse(array(
      'status' => 'success',
      'data'   => $this->render('MuzichUserBundle:Account:subscribe_or_login.html.twig', array(
        'form' => $this->getRegistrationForm($this->getNewUser())->createView()
      ))->getContent()
    ));
  }
  
  public function changeUsernameAction(Request $request)
  {
    $user = $this->getUserRefreshed();
    
    if (!$user->isUsernameUpdatable())
    {
      return new RedirectResponse($this->generateUrl('my_account'));
    }
    
    $errors = array();
    $form = $this->getChangeUsernameForm($user);
    if ($request->getMethod() == 'POST')
    {
      $form->bind($request);
      $errors = $this->checkChangeUsernameValues($form);
      if ($form->isValid() && !count($errors))
      {
        $form->getData()->setUsernameUpdatable(false);
        $this->persist($user);
        $this->flush();
        $this->setFlash('success', 'user.change_username.success');
        return new RedirectResponse($this->generateUrl('my_account'));
      }
      else
      {
        $this->setFlash('error', 'user.change_username.failure');
      }
    }
    
    return $this->render('MuzichUserBundle:User:change_username.html.twig', array(
      'form'   => $form->createView(),
      'errors' => $errors
    ));
  }
  
  protected function checkChangeUsernameValues($form)
  {
    $errors = array();
    $userManager = $this->container->get('fos_user.user_manager');
    if ($userManager->findUserByUsername($form->getData()->getUsername()))
    {
      $errors[] = $this->trans('error.change_username.duplicate', array(), 'validators');
    }
    
    if (strlen($form->getData()->getUsername()) < 3)
    {
      $errors[] = $this->trans(
        'error.change_username.min', 
        array('%limit%' => 3),
        'validators'
      );
    }
  
    if (strlen($form->getData()->getUsername()) > 32)
    {
      $errors[] = $this->trans(
        'error.change_username.max', 
        array('%limit%' => 32),
        'validators'
      );
    }
    
    return $errors;
  }
  
  protected function getChangeUsernameForm(User $user)
  {
    return $this->createFormBuilder($user)
      ->add('username', 'text')
      ->getForm()
    ;
  }
  
  public function sendEmailConfirmAction(Request $request, $set_send_time = true)
  {
    $user = $this->getUser();
    if ($user->isEmailConfirmed())
    {
      if ($request->isXmlHttpRequest())
      {
        return $this->jsonResponse(array(
          'status' => 'success',
          'result' => 'already_confirmed',
          'message' => $this->trans('user.confirm_email.alreaydy', array(), 'flash')
        ));
      }
      
      $this->setFlash('success', 'user.confirm_email.alreaydy');
      return new RedirectResponse($this->generateUrl('home'));
    }
    if ((time() - $user->getEmailConfirmationSentTimestamp() < $this->getParameter('email_confirmation_email_interval')))
    {
      if ($request->isXmlHttpRequest())
      {
        return $this->jsonResponse(array(
          'status' => 'error',
          'result' => 'already_sent_recently',
          'message' => $this->trans('user.confirm_email.sent_recently', array(), 'flash')
        ));
      }
      
      $this->setFlash('success', 'user.confirm_email.sent_recently');
      return new RedirectResponse($this->generateUrl('my_account'));
    }
    
    $this->sendEmailconfirmationEmail($set_send_time);
    
    if ($request->isXmlHttpRequest())
    {
      return $this->jsonResponse(array(
        'status' => 'success',
        'result' => 'sent',
        'message' => $this->trans('user.confirm_email.sent', array(), 'flash')
      ));
    }
    
    $this->setFlash('success', 'user.confirm_email.sent');
    return new RedirectResponse($this->generateUrl('my_account'));
  }
  
  public function confirmEmailAction(Request $request, $token)
  {
    if ($this->isVisitor())
    {
      $this->get("session")->set('user.confirm_email.token', $token);
      return $this->redirect($this->generateUrl('home_login'));
    }
    
    $user = $this->getUser();
    
    if ($token == hash('sha256', $user->getConfirmationToken().$user->getEmail()))
    {
      $user->setEmailConfirmed(true);
      $this->persist($user);
      $this->flush();
      $this->setFlash('success', 'user.confirm_email.confirmed');
      return new RedirectResponse($this->generateUrl('home'));
    }
    
    $this->setFlash('success', 'user.confirm_email.failtoken');
    return new RedirectResponse($this->generateUrl('my_account'));
  }
  
  public function showEmailNotConfirmedAction()
  {
    return $this->jsonResponse(array(
      'status' => 'success',
      'data' => $this->render('MuzichUserBundle:Account:email_not_confirmed.html.twig')->getContent()
    ));
  }
  
  public function favoriteTagsHelpboxAction()
  {
    return $this->jsonResponse(array(
      'status' => 'success',
      'data' => $this->render('MuzichUserBundle:User:helpbox_favorite_tags.html.twig', array( 
        'form'             => $this->getTagsFavoritesForm($this->getUser())->createView(),
        'form_name'        => 'favorites_tags_helpbox'
      ))->getContent()
    ));
  }
  
  public function deleteAction(Request $request)
  {
    $form = $this->getDeleteForm();
    $form->bind($request);
    
    if ($form->isValid())
    {
      $this->getUserManager()->disableUser($form->getData());
      $this->setFlash('success', 'user.delete.success');
      return $this->redirect($this->generateUrl('fos_user_security_logout'));
    }
    
    $this->setFlash('error', 'user.delete.fail');
    $form_tags_favorites = $this->getTagsFavoritesForm($form->getData());
    return $this->container->get('templating')->renderResponse(
      'MuzichUserBundle:User:account.html.twig',
      array(
        'form_password'            => $this->getChangePasswordForm($form->getData())->createView(),
        'errors_pers'              => array(),
        'user'                     => $form->getData(),
        'form_tags_favorites'      => $form_tags_favorites->createView(),
        'form_tags_favorites_name' => $form_tags_favorites->getName(),
        'favorite_tags_id'         => $this->getTagsFavorites(),
        'change_email_form'        => $this->getChangeEmailForm()->createView(),
        'avatar_form'              => $this->getAvatarForm()->createView(),
        'preferences_form'         => $this->getPreferencesForm()->createView(),
        'privacy_form'             => $this->getPrivacyForm()->createView(),
        'delete_form'              => $form->createView()
      )
    );
  }
  
}