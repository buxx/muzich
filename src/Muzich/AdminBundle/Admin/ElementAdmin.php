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
      ->add('owner', 'many_to_one')
      ->add('type')
      ->add('tags')
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
      ->add('url')
      ->add('tags')
      ->add('type')
    ;
  }
  
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('type', 'sonata_type_model', array(), array('edit' => 'list'))
      ->add('name')
      ->add('url')
      ->add('owner', 'sonata_type_model', array(), array('edit' => 'list'))
      ->add('group', 'sonata_type_model', array('required' => false), array('edit' => 'list'))
      ->add('embed', null, array('required' => false))
      ->add('tags', null, array('required' => false))
      
    ;
  }

//  public function validate(ErrorElement $errorElement, $object)
//  {
//     
//  }
  
}
