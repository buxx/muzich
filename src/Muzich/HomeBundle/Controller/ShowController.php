<?php

namespace Muzich\HomeBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ShowController extends Controller
{
  
  /**
   * Page public de l'utilisateur demandé.
   * 
   * @Template()
   */
  public function showUserAction($slug)
  {
    $viewed_user = $this->findUserWithSlug($slug);
    $user = $this->getUser();
        
    return array(
      'viewed_user' => $viewed_user,
      'elements'    => $this->getShowedEntityElements($viewed_user->getId(), 'User'),
      'following'   => $user->isFollowingUserByQuery($this->getDoctrine(), $viewed_user->getId()),
      'user'        => $user
    );
  }
  
  /**
   * Page publique du groupe demandé.
   * 
   * @Template()
   */
  public function showGroupAction($slug)
  {
    $group = $this->findGroupWithSlug($slug);
    $user = $this->getUser();
        
    return array(
      'group'       => $group,
      'his_group'   => ($group->getOwner()->getId() == $user->getId()) ? true : false,
      'elements'    => $this->getShowedEntityElements($group->getId(), 'Group'),
      'following'   => $user->isFollowingGroupByQuery($this->getDoctrine(), $group->getId()),
      'user'        => $user
    );
  }
  
  /**
   * Refactorisation pour showUserAction et showGroupAction. Récupére les 
   * elements de l'entité demandé.
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