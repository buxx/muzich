<?php

namespace Muzich\CoreBundle\Form\Tag;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TagFavoritesForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {    
    $builder->add('tags', 'hidden');
  }

  public function getName()
  {
    return 'tag_favorites_form';
  }
  
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'tags'       => '',
      //'data_class' => null
    ));
  }
}