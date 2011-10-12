<?php

namespace Muzich\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

class GroupAdmin extends Admin
{
  
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('name')
      ->add('open')
      ->add('owner', 'many_to_one')
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
      ->add('tags')
    ;
  }
  
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('name')
      ->add('description')
      ->add('open', null, array('required' => false))
      ->add('owner', 'sonata_type_model', array(), array('edit' => 'list'))
      // Attention, il semble que le lien pointe vers des records de la table de relation
      // il faudra utiliser le many to many, quand j'y arriverai ...
      //->add('tags', null, array('required' => false))
      
    ;
  }

//  public function validate(ErrorElement $errorElement, $object)
//  {
//     
//  }
  
}
