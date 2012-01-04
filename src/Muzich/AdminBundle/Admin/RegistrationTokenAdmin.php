<?php

namespace Muzich\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class RegistrationTokenAdmin extends Admin
{
  
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
      ->addIdentifier('token')
      ->add('_action', 'actions', array(
        'actions' => array(
          'view' => array(),
          'edit' => array(),
        )
      ))
    ;
  }
  
  protected function configureDatagridFilters(DatagridMapper $datagrid)
  {
    $datagrid
      ->add('token')
      ->add('used')
    ;
  }
  
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('token')
      ->add('used')
    ;
  }
  
}
