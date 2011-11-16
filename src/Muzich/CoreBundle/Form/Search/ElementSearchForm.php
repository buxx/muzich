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
        
    $builder->add('tags', 'choice', array(
      'choices'           => $options['tags'],
      'expanded'          => true,
      'multiple'          => true
    ));
        
//    $builder->add('groups', 'choice', array(
//      'choices'           => $options['groups'],
//      'expanded'          => true,
//      'multiple'          => true
//    ));
  }

  public function getName()
  {
    return 'element_search_form';
  }
  
  public function getDefaultOptions(array $options)
  {
    return array(
      'tags' => array(),
//      'groups' => array(),
    );
  }
}