<?php

namespace Muzich\HomeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Muzich\CoreBundle\Searcher\ElementSearcher;
use Doctrine\ORM\Query;

class HomeController extends Controller
{
  /**
   * @Route("/")
   * @Template()
   */
  public function indexAction()
  {        
    $user = $this->getUser();
       
    $query = $this->getDoctrine()
      ->getRepository('MuzichCoreBundle:Element')
      ->findBySearch($this->getElementSearcher($user->getId()))
    ;
    
    return array('elements' => $query->execute());
  }
}