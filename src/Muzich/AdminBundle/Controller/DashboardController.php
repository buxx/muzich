<?php

namespace Muzich\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
  public function welcomeAction()
  {
    return $this->render('AdmingeneratorGeneratorBundle:Dashboard:welcome.html.twig', array(
      'base_admin_template' => $this->container->getParameter('admingenerator.base_admin_template'),
    ));
  }
}