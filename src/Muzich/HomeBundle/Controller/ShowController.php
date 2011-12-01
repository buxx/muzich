<?php

namespace Muzich\HomeBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Muzich\CoreBundle\Form\Element\ElementAddForm;
use Muzich\CoreBundle\Entity\Element;

class ShowController extends Controller
{
  
  /**
   * Page public de l'utilisateur demandé.
   * 
   * @Template()
   * @param string $slug
   */
  public function showUserAction($slug)
  {
    $viewed_user = $this->findUserWithSlug($slug);
        
    $search_object = $this->createSearchObject(array(
      'user_id'  => $viewed_user->getId(),
      'count'    => $this->container->getParameter('search_default_count')
    ));
    
    return array(
      'viewed_user' => $viewed_user,
      'elements'    => $search_object->getElements($this->getDoctrine(), $this->getUserId()),
      'following'   => $this->getUser()->isFollowingUserByQuery($this->getDoctrine(), $viewed_user->getId()),
      'user'        => $this->getUser()
    );
  }
  
  /**
   * Page publique du groupe demandé.
   * 
   * @Template()
   * @param string $slug
   */
  public function showGroupAction($slug)
  {
    $group = $this->findGroupWithSlug($slug);
        
    $search_object = $this->createSearchObject(array(
      'group_id'  => $group->getId(),
      'count'     => $this->container->getParameter('search_default_count')
    ));
    
    ($group->getOwner()->getId() == $this->getUserId()) ? $his = true : $his = false;
    if ($his || $group->getOpen())
    {      
      $add_form = $this->createForm(
        new ElementAddForm(),
        array(),
        array(
          'tags' => $this->getTagsArray()
        )
      );
    }
    
    return array(
      'group'     => $group,
      'his_group' => ($group->getOwner()->getId() == $this->getUserId()) ? true : false,
      'elements'  => $search_object->getElements($this->getDoctrine(), $this->getUserId()),
      'following' => $this->getUser()->isFollowingGroupByQuery($this->getDoctrine(), $group->getId()),
      'user'      => $this->getUser(),
      'add_form'  => (isset($add_form)) ? $add_form->createView() : null
    );
  }
  
}