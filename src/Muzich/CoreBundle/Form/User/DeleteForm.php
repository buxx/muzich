<?php

namespace Muzich\CoreBundle\Form\User;

use Symfony\Component\Security\Core\Validator\Constraint\UserPassword;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

class DeleteForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('current_password', 'password', array(
      'label' => 'form.current_password',
      'translation_domain' => 'FOSUserBundle',
      'mapped' => false,
      'constraints' => new UserPassword(),
    ));
  }
  
  public function getName()
  {
    return 'delete_user_form';
  }
}
