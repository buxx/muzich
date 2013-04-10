<?php

namespace Muzich\AdminBundle\Controller;

use Muzich\CoreBundle\lib\Controller;

class DashboardController extends Controller
{
  public function welcomeAction()
  {
    return $this->render('MuzichAdminBundle:Dashboard:welcome.html.twig', array(
      'base_admin_template' => $this->container->getParameter('admingenerator.base_admin_template'),
    ));
  }
}