<?php

namespace Muzich\CoreBundle\Form\Tag;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TagFavoritesForm extends AbstractType
{
  public function buildForm(FormBuilder $builder, array $options)
  {    
    $builder->add('tags', 'hidden');
  }

  public function getName()
  {
    return 'tag_favorites_form';
  }
  
  public function getDefaultOptions(array $options)
  {
    return array(
      'tags' => '',
    );
  }
}