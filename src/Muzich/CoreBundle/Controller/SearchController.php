<?php

namespace Muzich\CoreBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Muzich\CoreBundle\Searcher\ElementSearcher;
use Doctrine\ORM\Query;

class SearchController extends Controller
{
  
  /**
   * 
   */
  public function searchelementsAction(ElementSearcher $search, $template = 'default')
  {
    $elements = $this->getDoctrine()
      ->getRepository('MuzichCoreBundle:Element')
      ->findBySearch($search)
      ->execute()
    ;
    
    return $this->render(
      'MuzichCoreBundle:SearchElement:default.html.twig', 
      array('elements' => $elements)
    );
  }   
  
}