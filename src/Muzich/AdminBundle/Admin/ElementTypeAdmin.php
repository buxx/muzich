<?php

namespace Muzich\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class ElementTypeAdmin extends Admin
{
  
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
      ->add('name')
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
      ->add('id')
      ->add('name')
    ;
  }

//  public function validate(ErrorElement $errorElement, $object)
//  {
//     
//  }
  
}
