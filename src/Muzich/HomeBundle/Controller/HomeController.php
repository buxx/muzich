<?php

namespace Muzich\HomeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
    $user = $this->container->get('security.context')->getToken()->getUser();
    
    $search = new ElementSearcher();
    
    $search->init(array(
      'network' => ElementSearcher::NETWORK_PUBLIC,
      'tags' => array(
        $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek'),
        $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe')
      ),
      'count' => 30
    ));
    
    $query = $this->getDoctrine()
      ->getRepository('MuzichCoreBundle:Element')
      ->findBySearch($search)
    ;
    
    //$product = new Query();
//    $product->execute();
    
    return array('elements' => $query->execute());
  }
}