<?php

namespace Muzich\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    parent::buildForm($builder, $options);

    $builder->add('token', 'text', array(
      "property_path" => false
    ));
    
  }

  public function getName()
  {
    return 'muzich_user_registration';
  }
}