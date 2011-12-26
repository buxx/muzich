<?php

namespace Muzich\CoreBundle\Form\Tag;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TagFavoritesForm extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {    
    $builder->add('tags', 'choice', array(
      'choices'           => $options['tags'],
      'expanded'          => true,
      'multiple'          => true
    ));
  }

  public function getName()
  {
    return 'tag_favorites_form';
  }
  
  public function getDefaultOptions(array $options)
  {
    return array(
      'tags' => array(),
    );
  }
}