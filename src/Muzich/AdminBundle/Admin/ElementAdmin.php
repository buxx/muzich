<?php

namespace Muzich\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class ElementAdmin extends Admin
{
  
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('name')
      ->add('url')
      ->add('tags')
    ;
  }
  
  protected function configureDatagridFilters(DatagridMapper $datagrid)
  {
    $datagrid
      ->add('name')
      ->add('url')
      ->add('tags')
    ;
  }
  
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('id')
      ->add('name')
      ->add('url')
    ;
  }

//  public function validate(ErrorElement $errorElement, $object)
//  {
//     
//  }
  
}
