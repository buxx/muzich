<?php

namespace Muzich\GroupBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Muzich\CoreBundle\Entity\Group;
use Muzich\CoreBundle\Form\Group\GroupForm;
use Symfony\Component\HttpFoundation\Request;
use Muzich\CoreBundle\Managers\GroupManager;

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
    
    $new_group = new Group();
    $form_new = $this->createForm(
      new GroupForm(), 
      $new_group,
      array('tags' => $this->getTagsArray())
    );
    
    return array(
      'groups'   => $user->getGroupsOwned(),
      'form_new' => $form_new->createView()
    );
  }
  
  /**
   * Procédure d'ajout d'un groupe
   */
  public function addAction(Request $request)
  {
    $user = $this->getUser();
    $em = $this->getDoctrine()->getEntityManager();
    
    $new_group = new Group();
    $new_group->setOwner($user);
    $form_new = $this->createForm(
      new GroupForm(), 
      $new_group,
      array('tags' => $this->getTagsArray())
    );
    
    $form_new->bindRequest($request);
    
    if ($form_new->isValid())
    {
      $factory = new GroupManager($new_group, $em, $this->container);
      $factory->proceedTags($new_group->getTags());
      
      $em->persist($new_group);
      $em->flush();
      
      $this->setFlash('success', 'group.create.success');
      return $this->redirect($this->generateUrl('groups_own_list'));
    }
    else
    {
      $user = $this->getUser(true, array('join' => array(
        'groups_owned'
      )));
      
      $this->setFlash('error', 'group.create.failure');
      
      return $this->render(
        'GroupBundle:Default:myList.html.twig', 
         array(
           'groups'   => $user->getGroupsOwned(),
           'form_new' => $form_new->createView()
         )
      );
    }
  }
  
}
