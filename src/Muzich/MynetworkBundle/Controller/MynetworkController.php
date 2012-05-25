<?php

namespace Muzich\MynetworkBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Muzich\CoreBundle\Searcher\UserAndGroupSearcher;

class MynetworkController extends Controller
{
  
  /**
   * Page listant les personnes et groupes que l'on suit, ainsi que les 
   * personnes qui nous duivent.
   *
   * @Template()
   */
  public function indexAction($event_id)
  {    
    $user = $this->getUser(true, array('join' => array(
      'followeds_users', 'followers_users', 'followeds_groups'
    )));
    
    $followeds_users = $user->getFollowedsUsers();
    $followeds_groups = $user->getFollowedGroups();
    $followers_users = $user->getFollowersUsers();
    
    if ($event_id)
    {
      if (!($event = $this->getDoctrine()->getRepository('MuzichCoreBundle:Event')
      ->findOneById($event_id)))
      {
        return $this->redirect($this->generateUrl('mynetwork_index'));
      }

      if ($event->getUser()->getId() != $this->getUserId())
      {
        throw $this->createNotFoundException('NotAllowed');
      }
      $followers_users = $this->proceedForEvent($event, $followers_users, $event_id);
    }
    
    return array(
      'followeds_users' => $followeds_users,
      'followeds_groups' => $followeds_groups,
      'followers_users' => $followers_users
    );
  }
  
  private function proceedForEvent($event, $followers_users, $event_id)
  {
    $ids = $event->getIds();
    
    $this->getDoctrine()->getEntityManager()->remove($event);
    $this->getDoctrine()->getEntityManager()->flush();
    
    $followers_users_new = array();
    foreach ($followers_users as $user)
    {
      if (in_array($user->getId(), $ids))
      {
        $user->addLiveData('new', true);
      }
      $followers_users_new[] = $user;
    }
    
    return $followers_users_new;
  }
  
}