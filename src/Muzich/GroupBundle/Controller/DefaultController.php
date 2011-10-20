<?php

namespace Muzich\GroupBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
  
  /**
   * 
   * @Template()
   */
  public function myListAction()
  {
    $user = $this->getUser(true, array('join' => array(
      'groups_owned'
    )));
    
    return array(
      'groups' => $user->getGroupsOwned()
    );
  }
  
}
