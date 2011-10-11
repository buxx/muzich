<?php

namespace Muzich\AdminBundle\Admin;

use WhiteOctober\AdminBundle\Admin\Admin;

class ElementAdmin extends Admin
{
  
  protected function configure()
  {
    $this
      // model class to admin
      ->setDataClass('MuzichCoreBundle\Entity\Element')
      // optional, if not the admin class urlized
      ->setRoutePatternPrefix('/admin/elements')
      // optional, if not the admin class urlized
      ->setRouteNamePrefix('admin_elements')
      // fields to use
      ->addFields(array(
          'name',
          'url'
      ))
      // actions the admin has
      ->addActions(array(
          'mandango.crud',
      ))
    ;
  }
  
}

?>
