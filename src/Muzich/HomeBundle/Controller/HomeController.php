<?php

namespace Muzich\HomeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Muzich\CoreBundle\Searcher\ElementSearcher;

class HomeController extends Controller
{
  /**
   * @Route("/")
   * @Template()
   */
  public function indexAction()
  {        
    $user = $this->container->get('security.context')->getToken()->getUser();
    $s = new ElementSearcher();
    $s->init(array(
      'network' => ElementSearcher::NETWORK_PUBLIC,
      'tags' => array('toto', 'pipi'),
      'count' => 30
    ));
    
    //die(var_dump($s));
    
    return array('user' => $user);
  }
}