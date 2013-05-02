<?php

namespace Muzich\CoreBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PasswordForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('plain_password', 'repeated', array(
      'type' => 'password',
      'options' => array('translation_domain' => 'FOSUserBundle'),
      'first_options' => array('label' => 'form.new_password', 'always_empty' => true),
      'second_options' => array('label' => 'form.new_password_confirmation', 'always_empty' => true),
      'invalid_message' => 'fos_user.password.mismatch',
    ));
  }
  
  public function getName()
  {
    return 'user_password';
  }

}