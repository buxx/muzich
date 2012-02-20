<?php

namespace Muzich\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ModerateController extends Controller
{
    
  /**
   *
   * @Template()
   */
  public function indexAction()
  {
    $count_moderate = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->countToModerate();
    
    return array(
      'count_moderate' => $count_moderate
    );
  }
    
  /**
   *
   * @Template()
   */
  public function tagsAction()
  {
    // Récupération des tags
    $tags = $this->getDoctrine()->getEntityManager('MuzichCoreBundle:Tag')
      ->getToModerate();
  }
  
}
