<?php

namespace Muzich\CoreBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Muzich\CoreBundle\Entity\FollowUser;
use Doctrine\ORM\Query;

class CoreController extends Controller
{
  
  /**
   * 
   * @param string $type
   * @param int $id
   * @param string $salt 
   */
  public function followAction($type, $id, $token)
  {
    $user = $this->getUser();
    // Vérifications préléminaires
    if ($user->getPersonalHash() != $token || !in_array($type, array('user', 'group')) || !is_numeric($id))
    {
      throw $this->createNotFoundException();
    }
    
    $em = $this->getDoctrine()->getEntityManager();
    $FollowUser = $em
      ->getRepository('MuzichCoreBundle:Follow' . ucfirst($type))
      ->findOneBy(
        array(
          'follower' => $user->getId(),
          ($type == 'user') ? 'followed' : 'group' => $id
        )
      )
    ;
    
    if ($FollowUser)
    {
      // L'utilisateur suis déjà, on doit détruire l'entité
      $em->remove($FollowUser);
      $em->flush();
    }
    else
    {
      $followed_user = $em->getRepository('MuzichCoreBundle:User')->find($id);

      if (!$followed_user) {
          throw $this->createNotFoundException('No user found for id '.$id);
      }
      
      $FollowUser = new FollowUser();
      $FollowUser->setFollowed($followed_user);
      $FollowUser->setFollower($user);
      
      $em->persist($FollowUser);
      $em->flush();
    }
    
    if ($this->getRequest()->isXmlHttpRequest())
    {
      
    }
    else
    {
      return $this->redirect($this->container->get('request')->headers->get('referer'));
    }
  }
  
}
