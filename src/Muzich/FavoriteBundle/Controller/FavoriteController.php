<?php

namespace Muzich\FavoriteBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Muzich\CoreBundle\Entity\UsersElementsFavorites;
use Muzich\CoreBundle\Searcher\ElementSearcher;
use Muzich\CoreBundle\Propagator\EventElement;
use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\lib\Tag as TagLib;
use Muzich\CoreBundle\Security\Context as SecurityContext;

//use Muzich\CoreBundle\Entity\Group;
//use Muzich\CoreBundle\Form\Group\GroupForm;
//use Symfony\Component\HttpFoundation\Request;
//use Muzich\CoreBundle\Managers\GroupManager;

class FavoriteController extends Controller
{
  
  /**
   * Ajoute comme favoris l'element en id
   * 
   * @param int $id
   * @param string $token 
   */
  public function addAction($id, $token)
  {
    if (($non_condition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_ELEMENT_ADD_TO_FAVORITES)) !== false)
    {
      return $this->jsonResponseError($non_condition);
    }
    
    if (($response = $this->mustBeConnected()))
    {
      return $response;
    }
    
    $user = $this->getUser();
    $em = $this->getEntityManager();
    
    if ($user->getPersonalHash($id) != $token || !is_numeric($id)
      || !($element = $em->getRepository('MuzichCoreBundle:Element')->findOneById($id))
    )
    {
      throw $this->createNotFoundException();
    }

    // Si l'élément n'est pas déjà en favoris
    if (!$em->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->findOneBy(array(
        'user'    => $user->getId(),
        'element' => $id
      )))
    {
      // On créer un objet 
      $favorite = new UsersElementsFavorites();
      $favorite->setUser($user);
      $favorite->setElement($element);
      
      if ($user->getId() != $element->getOwner()->getId())
      {
        // On déclenche les événements liés a cette action
        $event = new EventElement($this->container);
        $event->addedToFavorites($element, $user);
        $em->persist($user);
      }
      
      // On signale que cet user a modifié sa liste de favoris
      $user->setData(User::DATA_FAV_UPDATED, true);
      
      $em->persist($favorite);
      $em->persist($user);
      $em->flush();
    }
    
    if ($this->getRequest()->isXmlHttpRequest())
    {
      return $this->jsonResponse(array(
        'favorite'      => true,
        'link_new_url'  => $this->generateUrl('favorite_remove', array(
            'id'    => $id,
            'token' => $user->getPersonalHash($id)
        )),
        'img_new_src'   => $this->getAssetUrl('img/icon_star_2_red.png'),
        'img_new_title' => $this->trans('element.favorite.remove', array(), 'elements')
      ));
    }
    else
    {
      return $this->redirect($this->container->get('request')->headers->get('referer'));
    }
  }
  
  /**
   * Retire comme favoris l'element en id
   * 
   * @param int $id
   * @param string $token 
   */
  public function removeAction($id, $token)
  {
    if (($response = $this->mustBeConnected()))
    {
      return $response;
    }
    
    $user = $this->getUser();
    $em = $this->getDoctrine()->getEntityManager();
    
    if ($user->getPersonalHash($id) != $token || !is_numeric($id)
      || !($element = $em->getRepository('MuzichCoreBundle:Element')->findOneById($id))
    )
    {
      throw $this->createNotFoundException();
    }

    // Si l'élément est déjà en favoris, ce qui est cencé être le cas
    if (($fav = $em->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->findOneBy(array(
        'user'    => $user->getId(),
        'element' => $id
      ))))
    {
      if ($user->getId() != $element->getOwner()->getId())
      {
        // On déclenche les événements liés a cette action
        $event = new EventElement($this->container);
        $event->removedFromFavorites($element, $user);
      }
      
      // On signale que cet user a modifié sa liste de favoris
      $user->setData(User::DATA_FAV_UPDATED, true);
      
      $em->persist($element->getOwner());
      $em->remove($fav);
      $em->flush();
    }
    
    if ($this->getRequest()->isXmlHttpRequest())
    {
      return $this->jsonResponse(array(
        'favorite'      => true,
        'link_new_url'  => $this->generateUrl('favorite_add', array(
            'id'    => $id,
            'token' => $user->getPersonalHash($id)
        )),
        'img_new_src'   => $this->getAssetUrl('img/icon_star_2.png'),
        'img_new_title' => $this->trans('element.favorite.add', array(), 'elements')
      ));
    }
    else
    {
      return $this->redirect($this->container->get('request')->headers->get('referer'));
    }
  }
  
  /**
   * Page affichant les elements favoris de l'utilisateur
   * 
   * @Template()
   */
  public function myListAction()
  {
    $user = $this->getUser();
    
    $search_object = $this->createSearchObject(array(
      'user_id'  => $user->getId(),
      'favorite' => true,
      'count'    => $this->container->getParameter('search_default_count')
    ));
    
    // Récupération des tags
    $tags = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->getTags($this->getUserId(), $this->getUserId())      
    ;
    
    // Organisation des tags en fonction de leurs utilisation
    $tag_lib = new TagLib();
    $tags = $tag_lib->sortTagWithOrderedReference($tags, 
      $user->getData(User::DATA_TAGS_ORDER_PAGE_FAV, array()));
    
    $tags_id = array();
    foreach ($tags as $tag)
    {
      $tags_id[] = $tag->getId();
    }
    
    return array(
      'tags'          => $tags,
      'tags_id_json'  => json_encode($tags_id),
      'user'          => $this->getUser(),
      'elements'      => $search_object->getElements($this->getDoctrine(), $this->getUserId())
    );
  }
  
  /**
   * Affichage des elements favoris d'un utilisateur particulier.
   * 
   * @param type $slug 
   * @Template()
   */
  public function userListAction($slug)
  {
    $viewed_user = $this->findUserWithSlug($slug);
    
    $search_object = $this->createSearchObject(array(
      'user_id'  => $viewed_user->getId(),
      'favorite' => true,
      'count'    => $this->container->getParameter('search_default_count')
    ));
    
    $tags = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->getTags($viewed_user->getId(), $this->getUserId(true))      
    ;
    
    $tags_id = array();
    foreach ($tags as $tag)
    {
      $tags_id[] = $tag->getId();
    }
    
    return array(
      'tags'          => $tags,
      'tags_id_json'  => json_encode($tags_id),
      'user'          => $this->getUser(),
      'viewed_user'   => $viewed_user,
      'elements'      => $search_object->getElements($this->getDoctrine(), $this->getUserId(true))
    );
  }
  
  public function getElementsAction($user_id, $tags_ids_json, $id_limit = null)
  {
    $autoplay_context = 'favorite_user';
    if ($user_id == $this->getUserId(true))
    {
      $autoplay_context = 'favorite_my';
    }
    
    $tag_ids = json_decode($tags_ids_json);
    $search_object = new ElementSearcher();
    $tags = null;
    
    //die(var_dump($tag_ids));
    if (count($tag_ids))
    {
      $tags = array();
      foreach ($tag_ids as $id)
      {
        $tags[$id] = $id;
      }
    }
    
    $search_object->init(array(
      'tags'     => $tags,
      'user_id'  => $user_id,
      'favorite' => true,
      'count'    => $this->container->getParameter('search_default_count'),
      'id_limit' => $id_limit
    ));
    
    $message = $this->trans(
      'elements.ajax.more.noelements', 
      array(), 
      'elements'
    );
    
    $viewed_user = $this->getUser();
    if ($user_id != $this->getUserId(true))
    {
      $viewed_user = $this->getDoctrine()->getEntityManager()->getRepository('MuzichCoreBundle:User')
        ->findOneById($user_id, array())->getSingleResult();
    }
    
    $elements = $search_object->getElements($this->getDoctrine(), $this->getUserId(true));
    $count = count($elements);
    $html = '';
    if ($count)
    {
      $html = $this->render('MuzichCoreBundle:SearchElement:default.html.twig', array(
        'display_autoplay' => $this->getDisplayAutoplayBooleanForContext($autoplay_context),
        'autoplay_context' => $autoplay_context,
        'user'             => $this->getUser(),
        'elements'         => $elements,
        'tag_ids_json'     => $tags_ids_json,
        'viewed_user'      => $viewed_user
      ))->getContent();
    }
    
    return $this->jsonResponse(array(
      'count'   => $count,
      'message' => $message,
      'html'    => $html
    ));
  }
  
}
