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
        
    $builder->add('tags', 'choice', array(
      'choices'           => $options['tags'],
      'expanded'          => true,
      'multiple'          => true
    ));
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
      'tags' => array(),
      'data_class' => 'Muzich\CoreBundle\Entity\Element'
    );
  }
}