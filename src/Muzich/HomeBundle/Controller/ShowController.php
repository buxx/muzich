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
        
    $search_object = $this->createSearchObject(array(
      'user_id'  => $viewed_user->getId()
    ));
    
    return array(
      'viewed_user' => $viewed_user,
      'elements'    => $search_object->doSearch($this->getDoctrine(), $this->getUserId()),
      'following'   => $this->getUser()->isFollowingUserByQuery($this->getDoctrine(), $viewed_user->getId()),
      'user'        => $this->getUser()
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
        
    $search_object = $this->createSearchObject(array(
      'group_id'  => $group->getId()
    ));
    
    return array(
      'group'     => $group,
      'his_group' => ($group->getOwner()->getId() == $this->getUserId()) ? true : false,
      'elements'  => $search_object->doSearch($this->getDoctrine(), $this->getUserId()),
      'following' => $this->getUser()->isFollowingGroupByQuery($this->getDoctrine(), $group->getId()),
      'user'      => $this->getUser()
    );
  }
  
}