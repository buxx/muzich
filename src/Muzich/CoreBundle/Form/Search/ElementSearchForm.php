<?php

namespace Muzich\CoreBundle\Form\Search;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Muzich\CoreBundle\Searcher\ElementSearcher;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ElementSearchForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('network', 'choice', array(
      'choices' => array(
        ElementSearcher::NETWORK_PUBLIC => 'tout le réseau',
        ElementSearcher::NETWORK_PERSONAL => 'mon réseau'
      ),
      'required' => true,
    ));
    
    $builder->add('tag_strict', 'checkbox', array(
      'required'  => false
    ));
        
    $builder->add('tags', 'hidden');
  }

  public function getName()
  {
    return 'element_search_form';
  }
  
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'tags'       => '',
      //'data_class' => null
    ));
  }
}