<?php

namespace Muzich\HomeBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Muzich\CoreBundle\Form\Element\ElementAddForm;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Searcher\ElementSearcher;
use Muzich\CoreBundle\lib\Tag as TagLib;
use Muzich\CoreBundle\Entity\User;

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
      ->getElementsTags($viewed_user->getId(), $this->getUserId())      
    ;
    
    // Organisation des tags en fonction de leurs utilisation
    $tag_lib = new TagLib();
    $tags = $tag_lib->sortTagWithOrderedReference($tags, 
    $viewed_user->getData(User::DATA_TAGS_ORDER_DIFF, array()));
    
    $tags_id = array();
    foreach ($tags as $tag)
    {
      $tags_id[] = $tag->getId();
    }
    
    $element_ids_owned = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
      ->getElementIdsOwned($viewed_user->getId())      
    ;
    
    $count_favorited = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->countFavoritedForUserElements($viewed_user->getId(), $element_ids_owned)      
    ;
    
    $count_favorited_users = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->countFavoritedUsersForUserElements($viewed_user->getId(), $element_ids_owned)      
    ;
    
    $count_followers = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
      ->countFollowers($viewed_user->getId())      
    ;
    
    return array(
      'tags'            => $tags,
      'tags_id_json'    => json_encode($tags_id),
      'viewed_user'     => $viewed_user,
      'elements'        => $search_object->getElements($this->getDoctrine(), $this->getUserId()),
      'following'       => $this->getUser()->isFollowingUserByQuery($this->getDoctrine(), $viewed_user->getId()),
      'user'            => $this->getUser(),
      'more_count'      => ($count)?$count+$this->container->getParameter('search_default_count'):$this->container->getParameter('search_default_count')*2,
      'more_route'      => 'show_user_more',
      'topmenu_active'  => ($viewed_user->getId() == $this->getUserId()) ? 'myfeeds' : 'public',
      'count_owned'     => count($element_ids_owned),
      'count_favorited' => $count_favorited,
      'count_favorited_users' => $count_favorited_users,
      'count_followers' => $count_followers,
      'add_form'        => ($this->getUserId() == $viewed_user->getId())?$this->getAddForm()->createView():null,
      'add_form_name'   => 'add'
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
      ->getElementsTags($group->getId(), $this->getUserId())      
    ;
    
    $tags_id = array();
    foreach ($tags as $tag)
    {
      $tags_id[] = $tag->getId();
    }
    
    $element_ids_owned = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')
      ->getElementIdsOwned($group->getId())      
    ;
    
    $count_favorited = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->countFavoritedForUserElements(null, $element_ids_owned)      
    ;
    
    $count_favorited_users = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->countFavoritedUsersForUserElements(null, $element_ids_owned)      
    ;
    
    $count_followers = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')
      ->countFollowers($group->getId())      
    ;
    
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
      'more_route'    => 'show_group_more',
      'count_owned'     => count($element_ids_owned),
      'count_favorited' => $count_favorited,
      'count_favorited_users' => $count_favorited_users,
      'count_followers' => $count_followers
    );
  }
  
  public function getElementsAction($type, $object_id, $tags_ids_json, $id_limit = null)
  {
    if (($response = $this->mustBeConnected()))
    {
      return $response;
    }
    
    if ($type != 'user' && $type != 'group')
    {
      throw new \Exception("Wrong Type.");
    }
    
    $viewed_user = null;
    if ($type == 'user' && $object_id == $this->getUserId())
    {
      $object = $viewed_user = $this->getUser();
    }
    else if ($type == 'user')
    {
      $object = $viewed_user = $this->getDoctrine()->getEntityManager()->getRepository('MuzichCoreBundle:User')
        ->findOneById($object_id, array())->getSingleResult();
    }
    else if ($type == 'group')
    {
      $object = $this->getDoctrine()->getEntityManager()->getRepository('MuzichCoreBundle:Group')
        ->findOneById($object_id);
    }
    
    $search_object = new ElementSearcher();
    $tags = null;
    $tag_ids = json_decode($tags_ids_json);
    
    if (count($tag_ids))
    {

      $tags = array();
      foreach ($tag_ids as $id)
      {
        $tags[$id] = $id;
      }
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
        'display_autoplay' => $this->getDisplayAutoplayBooleanForContext('show_'.$type),
        'autoplay_context' => 'show_'.$type,
        'user'             => $this->getUser(),
        'viewed_user'      => $viewed_user,
        'elements'         => $elements,
        'tag_ids_json'     => $tags_ids_json,
        $type              => $object
      ))->getContent();
    }
    
    return $this->jsonResponse(array(
      'status'  => 'success',
      'count'   => $count,
      'message' => $message,
      'html'    => $html
    ));
  }
  
}