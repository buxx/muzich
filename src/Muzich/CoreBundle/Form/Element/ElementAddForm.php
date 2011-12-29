<?php

namespace Muzich\CoreBundle\Form\Element;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ElementAddForm extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {
    $builder->add('name', 'text', array(
      'required' => true,
    ));
    
    $builder->add('url', 'text', array(
      'required' => true,
    ));
        
    $builder->add('tags', 'hidden');    
  }

  public function getName()
  {
    return 'element_add';
  }
  
  public function getDefaultOptions(array $options)
  {
    return array(
      'name' => '',
      'url' => '',
      'tags' => '',
      //'groups' => array(),
      'data_class' => 'Muzich\CoreBundle\Entity\Element'
    );
  }
}