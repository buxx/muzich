<?php

namespace Muzich\HomeBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Muzich\CoreBundle\Form\Element\ElementAddForm;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Searcher\ElementSearcher;

class ShowController extends Controller
{
  
  /**
   * Page public de l'utilisateur demandé.
   * 
   * @Template()
   * @param string $slug
   */
  public function showUserAction($slug, $count = null)
  {
    $viewed_user = $this->findUserWithSlug($slug);
        
    $search_object = $this->createSearchObject(array(
      'user_id'  => $viewed_user->getId(),
      'count'    => ($count)?$count:$this->container->getParameter('search_default_count')
    ));
    
    $tags = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
      ->getElementsTags($viewed_user->getId())      
    ;
    
    $tags_id = array();
    foreach ($tags as $tag)
    {
      $tags_id[] = $tag->getId();
    }
    
    return array(
      'tags'          => $tags,
      'tags_id_json'  => json_encode($tags_id),
      'viewed_user'   => $viewed_user,
      'elements'      => $search_object->getElements($this->getDoctrine(), $this->getUserId()),
      'following'     => $this->getUser()->isFollowingUserByQuery($this->getDoctrine(), $viewed_user->getId()),
      'user'          => $this->getUser(),
      'more_count'    => ($count)?$count+$this->container->getParameter('search_default_count'):$this->container->getParameter('search_default_count')*2,
      'more_route'    => 'show_user_more'
    );
  }
  
  /**
   * Page publique du groupe demandé.
   * 
   * @Template()
   * @param string $slug
   */
  public function showGroupAction($slug, $count = null)
  {
    $group = $this->findGroupWithSlug($slug);
        
    $search_object = $this->createSearchObject(array(
      'group_id'  => $group->getId(),
      'count'     => ($count)?$count:$this->container->getParameter('search_default_count')
    ));
    
    ($group->getOwner()->getId() == $this->getUserId()) ? $his = true : $his = false;
    if ($his || $group->getOpen())
    {      
      $add_form = $this->getAddForm();
    }
    
    $tags = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')
      ->getElementsTags($group->getId())      
    ;
    
    $tags_id = array();
    foreach ($tags as $tag)
    {
      $tags_id[] = $tag->getId();
    }
    
    return array(
      'tags'          => $tags,
      'tags_id_json'  => json_encode($tags_id),
      'group'         => $group,
      'his_group'     => ($group->getOwner()->getId() == $this->getUserId()) ? true : false,
      'elements'      => $search_object->getElements($this->getDoctrine(), $this->getUserId()),
      'following'     => $this->getUser()->isFollowingGroupByQuery($this->getDoctrine(), $group->getId()),
      'user'          => $this->getUser(),
      'add_form'      => (isset($add_form)) ? $add_form->createView() : null,
      'add_form_name' => (isset($add_form)) ? 'add' : null,
      'more_count'    => ($count)?$count+$this->container->getParameter('search_default_count'):$this->container->getParameter('search_default_count')*2,
      'more_route'    => 'show_group_more'
    );
  }
  
  public function getElementsAction($type, $object_id, $tags_ids_json, $id_limit = null, $invert = false)
  {
    if ($this->getUser() == 'anon.')
    {
      if ($this->getRequest()->isXmlHttpRequest())
      {
        return $this->jsonResponse(array(
          'status' => 'mustbeconnected'
        ));
      }
      else
      {
        return $this->redirect($this->generateUrl('index'));
      }
    }
    
    if ($type != 'user' && $type != 'group')
    {
      throw new \Exception("Wrong Type.");
    }
    
    $tag_ids = json_decode($tags_ids_json);
    $search_object = new ElementSearcher();
    
    $tags = array();
    foreach ($tag_ids as $id)
    {
      $tags[$id] = $id;
    }
    
    $search_object->init(array(
      'tags'       => $tags,
      $type.'_id'  => $object_id,
      'count'      => $this->container->getParameter('search_default_count'),
      'id_limit'   => $id_limit
    ));
    
    $message = $this->trans(
      'elements.ajax.more.noelements', 
      array(), 
      'elements'
    );
    
    $elements = $search_object->getElements($this->getDoctrine(), $this->getUserId());
    $count = count($elements);
    $html = '';
    if ($count)
    {
      $html = $this->render('MuzichCoreBundle:SearchElement:default.html.twig', array(
        'user'        => $this->getUser(),
        'elements'    => $elements,
        'invertcolor' => $invert
      ))->getContent();
    }
    
    return $this->jsonResponse(array(
      'count'   => $count,
      'message' => $message,
      'html'    => $html
    ));
  }
  
}