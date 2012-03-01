<?php

namespace Muzich\CoreBundle\Form\Group;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class GroupForm extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('name', 'text', array(
      'required' => true,
    ));
    
    $builder->add('description', 'textarea', array(
      'required' => false,
    ));
    
    $builder->add('open', 'checkbox', array(
      'required' => false,
    ));
        
    $builder->add('tags', 'hidden');
  }

  public function getName()
  {
    return 'group';
  }
  
  public function getDefaultOptions(array $options)
  {
    return array(
      'name' => '',
      'open' => true,
      'tags' => '',
      'data_class' => 'Muzich\CoreBundle\Entity\Group'
    );
  }
}


