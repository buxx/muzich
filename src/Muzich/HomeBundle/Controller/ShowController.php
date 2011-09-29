<?php

namespace Muzich\HomeBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ShowController extends Controller
{
  
  /**
   * 
   * 
   * @Template()
   */
  public function showUserAction($slug)
  {
    try {
      $viewed_user = $this->getDoctrine()
        ->getRepository('MuzichCoreBundle:User')
        ->findOneBySlug($slug)
        ->getSingleResult()
      ;
    } catch (\Doctrine\ORM\NoResultException $e) {
        throw $this->createNotFoundException('Utilisateur introuvable.');
    }
    
    
    return array(
      'viewed_user' => $viewed_user
    );
  }
  
  /**
   * 
   * 
   * @Template()
   */
  public function showGroupAction($slug)
  {
    try {
      $group = $this->getDoctrine()
        ->getRepository('MuzichCoreBundle:Group')
        ->findOneBySlug($slug)
        ->getSingleResult()
      ;
    } catch (\Doctrine\ORM\NoResultException $e) {
        throw $this->createNotFoundException('Groupe introuvable.');
    }
    
    
    return array(
      'group' => $group
    );
  }
  
  protected function getShowedEntityElements()
  {
    
  }
  
}