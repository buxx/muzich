<?php

namespace Muzich\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ElementAdmin extends Admin
{
  
  protected $baseRouteName = 'elements_admin';
  
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      ->addIdentifier('id')
      ->add('name')
      ->add('url')

      // add custom action links
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
      ->add('tags', null, array('label' => 'les tags'), null, array('expanded' => true, 'multiple' => true))
    ;
  }
  
}

?>
