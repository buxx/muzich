<?php

namespace Muzich\CoreBundle\Form\Element;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ElementAddForm extends AbstractType
{
  private $name = null;
  
  public function buildForm(FormBuilder $builder, array $options)
  {    
    $builder->add('name', 'text', array(
      'required' => true,
      'error_bubbling' => true
    ));
    
    $builder->add('url', 'text', array(
      'required' => true,
      'error_bubbling' => true
    ));
        
    $builder->add('tags', 'hidden');    
  }
  
  public function setName($name)
  {
    $this->name = $name;
  }

  public function getName()
  {
    if ($this->name)
    {
      return $this->name;
    }
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