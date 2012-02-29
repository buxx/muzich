<?php

namespace Muzich\AdminBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
//use Muzich\CoreBundle\Util\TagLike;
use Muzich\CoreBundle\Entity\UsersTagsFavorites;
use Muzich\CoreBundle\Entity\GroupsTagsFavorites;
use Muzich\CoreBundle\Managers\TagManager;

class ModerateController extends Controller
{
    
  /**
   *
   * @Template()
   */
  public function indexAction()
  {
    $count_moderate = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->countToModerate();
    
    return array(
      'count_moderate' => $count_moderate
    );
  }
    
  /**
   *
   * @Template()
   */
  public function tagsAction()
  {
    // Récupération des tags
    $tags = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->getToModerate();
    
    // TODO: Ajouter a chaque tag la liste des tags ressemblant
    
    return array(
      'tags' => $tags
    );
  }
  
  public function tagAcceptAction($tag_id)
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
    
    $tagManager = new TagManager();
    if (!$tagManager->moderateTag($this->getDoctrine(), $tag_id, true))
    {
      return $this->jsonResponse(array(
        'status'  => 'error',
        'message' => 'NotFound'
      ));
    }
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
  
  public function tagRefuseAction($tag_id)
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
    
    $tagManager = new TagManager();
    if (!$tagManager->moderateTag($this->getDoctrine(), $tag_id, false))
    {
      return $this->jsonResponse(array(
        'status'  => 'error',
        'message' => 'NotFound'
      ));
    }
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
  
  /**
   * Cette action est plus délicate, elle consiste a remplacer le tag en question
   * par un autre.
   *
   * @param int $tag_id
   * @param int $tag_new_id
   * @return view 
   */
  public function tagReplaceAction($tag_id, $tag_new_id)
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
    
    $tag_array = json_decode($tag_new_id);
    if (!array_key_exists(0, $tag_array))
    {
      return $this->jsonResponse(array(
        'status'  => 'error',
        'message' => 'netTagError'
      ));
    }
    $tag_new_id = $tag_array[0];
    
    $tagManager = new TagManager();
    if (!$tagManager->moderateTag($this->getDoctrine(), $tag_id, false, $tag_new_id))
    {
      return $this->jsonResponse(array(
        'status'  => 'error',
        'message' => 'NotFound'
      ));
    }
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
    
  }
  
}
