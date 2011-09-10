<?php

namespace Muzich\HomeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class HomeController extends Controller
{
  /**
   * @Route("/")
   * @Template()
   */
  public function indexAction()
  {        
    $user = $this->container->get('security.context')->getToken()->getUser();
    
    return array('user' => $user);
  }
}