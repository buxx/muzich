<?php

namespace Muzich\CoreBundle\Form\Search;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Muzich\CoreBundle\Searcher\ElementSearcher;

class ElementSearchForm extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
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
  
  public function getDefaultOptions(array $options)
  {
    return array(
      'tags' => ''
    );
  }
}