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
        
    $search_object = $this->createSearchObject(array(
      'user_id'  => $viewed_user->getId()
    ));
    
    return array(
      'viewed_user'   => $viewed_user,
      'search_object' => $search_object,
      'following'     => $user->isFollowingUserByQuery($this->getDoctrine(), $viewed_user->getId()),
      'user'          => $user
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
        
    $search_object = $this->createSearchObject(array(
      'group_id'  => $group->getId()
    ));
    
    return array(
      'group'         => $group,
      'his_group'     => ($group->getOwner()->getId() == $user->getId()) ? true : false,
      'search_object' => $search_object,
      'following'     => $user->isFollowingGroupByQuery($this->getDoctrine(), $group->getId()),
      'user'          => $user
    );
  }
  
}