<?php

namespace Muzich\MynetworkBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class MynetworkController extends Controller
{
  
  /**
   * 
   * @Template()
   */
  public function indexAction()
  {
    $user = $this->getUser();
    
    $followeds_users = $user->getFollowedsUsers();
    $followeds_groups = $user->getFollowedGroups();
    $followers_users = $user->getFollowersUsers();
    
    return array(
      'followeds_users' => $followeds_users,
      'followeds_groups' => $followeds_groups,
      'followers_users' => $followers_users
    );
  }
  
}