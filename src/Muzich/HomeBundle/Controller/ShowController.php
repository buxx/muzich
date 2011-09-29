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
      $user = $this->getUser();
      
    } catch (\Doctrine\ORM\NoResultException $e) {
        throw $this->createNotFoundException('Utilisateur introuvable.');
    }
    
    return array(
      'viewed_user' => $viewed_user,
      'elements'    => $this->getShowedEntityElements($viewed_user->getId(), 'User'),
      'following'   => $user->isFollowingUserByQuery($this->getDoctrine(), $viewed_user->getId()),
      'user'        => $user
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
      $user = $this->getUser();
      
    } catch (\Doctrine\ORM\NoResultException $e) {
        throw $this->createNotFoundException('Groupe introuvable.');
    }
    
    return array(
      'group'       => $group,
      'elements'    => $this->getShowedEntityElements($group->getId(), 'Group'),
      'following'   => $user->isFollowingGroupByQuery($this->getDoctrine(), $group->getId()),
      'user'        => $user
    );
  }
  
  /**
   *
   * @param Entity $entity
   * @param string $type
   * @return array 
   */
  protected function getShowedEntityElements($entity_id, $type)
  {
    $findBy = 'findBy'.$type;
    return $this->getDoctrine()
      ->getRepository('MuzichCoreBundle:Element')
      ->$findBy($entity_id, 10)
      
      ->execute()
    ;
  }
  
}