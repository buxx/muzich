<?php

namespace Muzich\UserBundle\Form\Handler;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class RegistrationFormHandler
{
    protected $request;
    protected $userManager;
    protected $form;
    protected $mailer;
    protected $tokenGenerator;
    
    protected $translator;
    protected $doctrine;
    protected $errors =  array();
    protected $token;

    public function __construct(FormInterface $form, Request $request, UserManagerInterface $userManager, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, $translator, $doctrine)
    {
        $this->form = $form;
        $this->request = $request;
        $this->userManager = $userManager;
        $this->mailer = $mailer;
        $this->tokenGenerator = $tokenGenerator;
        $this->translator = $translator;
        $this->doctrine = $doctrine;
    }
    
    protected function checkRegistrationInformations($user)
    {
      $form_values = $this->request->get($this->form->getName());
      $this->token = $this->doctrine->getRepository('MuzichCoreBundle:RegistrationToken')
        ->findOneBy(array('token' => $form_values["token"], 'used' => false))
      ;
      
      if (!$this->token)
      {
        $this->errors[] = $this->translator->trans(
          'registration.token.error', 
          array(),
          'validators'
        );
      }
      
      if (strlen($user->getUsername()) < 3)
      {
        $this->errors[] = $this->translator->trans(
          'error.registration.username.min', 
          array('%limit%' => 3),
          'validators'
        );
      }
    
      if (strlen($user->getUsername()) > 32)
      {
        $this->errors[] = $this->translator->trans(
          'error.registration.username.max', 
          array('%limit%' => 32),
          'validators'
        );
      }
      
      if ($form_values['plainPassword']['first'] != $form_values['plainPassword']['second'])
      {
        $this->errors[] = $this->translator->trans(
          'error.registration.password.notsame', 
          array(),
          'validators'
        );
      }
    }

    /**
     * @param boolean $confirmation
     */
    public function process($confirmation = false)
    {
        $user = $this->createUser();
        $this->form->setData($user);

        if ('POST' === $this->request->getMethod()) {
            $this->form->bind($this->request);
            
            $this->checkRegistrationInformations($user);
            
            if ($this->form->isValid() && !count($this->errors)) {
                $this->onSuccess($user, $confirmation);

                return true;
            }
        }

        return false;
    }

    /**
     * @param boolean $confirmation
     */
    protected function onSuccess(UserInterface $user, $confirmation)
    {
        if ($confirmation) {
            $user->setEnabled(false);
            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken($this->tokenGenerator->generateToken());
            }

            $this->mailer->sendConfirmationEmailMessage($user);
        } else {
            $user->setEnabled(true);
        }

        $this->userManager->updateUser($user);
    }

    /**
     * @return UserInterface
     */
    protected function createUser()
    {
        return $this->userManager->createUser();
    }
    
    public function getErrors()
    {
      return $this->errors;
    }
    
    public function getToken()
    {
      return $this->token;
    }
}
