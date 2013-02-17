<?php

namespace Muzich\CoreBundle\Form\Element;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ElementAddForm extends AbstractType
{
  private $name = null;
  
  public function buildForm(FormBuilderInterface $builder, array $options)
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
    
    $builder->add('need_tags', 'checkbox', array(
      'required' => false,
      'error_bubbling' => true
    ));
         
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
  
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'name' => '',
      'url' => '',
      'tags' => '',
      'need_tags' => false,
      //'data_class' => 'Muzich\CoreBundle\Entity\Element'
    ));
  
    $resolver->setAllowedValues(array(
      'need_tags'       => array(true, false)
    ));
  }
}