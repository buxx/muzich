<?php

namespace Muzich\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class TagAdmin extends Admin
{
  
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
      ->addIdentifier('name')
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
      ->add('name')
    ;
  }
  
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('name')
    ;
  }

//  public function validate(ErrorElement $errorElement, $object)
//  {
//     
//  }
  
}
