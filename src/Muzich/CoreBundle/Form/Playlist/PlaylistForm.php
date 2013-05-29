<?php

namespace Muzich\CoreBundle\Form\Playlist;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlaylistForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('name', 'text', array(
      'required' => true,
    ));
    $builder->add('public', 'checkbox', array(
      'required' => false,
    ));
  }

  public function getName()
  {
    return 'playlist';
  }
  
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
      'name'   => '',
      'public' => false,
    ));
  
    $resolver->setAllowedValues(array(
      'public' => array(true, false)
    ));
  }
}


