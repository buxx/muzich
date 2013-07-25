<?php

namespace Muzich\CoreBundle\Form\Playlist;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PrivateLinksForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('links', 'textarea', array(
      'required' => true,
    ));
  }

  public function getName()
  {
    return 'playlist_private_links_form';
  }
}


