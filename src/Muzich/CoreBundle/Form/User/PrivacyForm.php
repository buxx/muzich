<?php

namespace Muzich\CoreBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Muzich\CoreBundle\Managers\UserPrivacy as UserPrivacyManager;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PrivacyForm extends AbstractType
{
  protected $options;
  
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $this->options = $options;
    foreach (UserPrivacyManager::$configurations as $configuration_id => $configuration_default)
    {
      $builder->add($configuration_id, 'checkbox', array(
        'required' => false
      ));
    }
  }
  
  public function getName()
  {
    return 'user_privacy';
  }
  
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $defaults = array();
    $alloweds = array();
    
    foreach (UserPrivacyManager::$configurations as $configuration_id => $configuration_default)
    {
      $defaults[$configuration_id] = $configuration_default;
      $alloweds[$configuration_id] = array(true, false);
    }
    
    $resolver->setDefaults($defaults);
    $resolver->setAllowedValues($alloweds);
  }

}