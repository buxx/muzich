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
    $count_tags = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->countToModerate();
    $count_elements = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->countToModerate();
    
    return array(
      'count_tags' => $count_tags,
      'count_elements' => $count_elements
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
    if (($response = $this->mustBeConnected()))
    {
      return $response;
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
    if (($response = $this->mustBeConnected()))
    {
      return $response;
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
    if (($response = $this->mustBeConnected()))
    {
      return $response;
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
  
  /**
   *
   * @Template()
   */
  public function elementsAction()
  {
    // Récupération des elements
    $elements = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->getToModerate();
    
    return array(
      'elements' => $elements
    );
  }
  
  /**
   * 
   */
  public function deleteElementAction($element_id)
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id))
    )
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotFound')
      ));
    }
    
    $this->getDoctrine()->getEntityManager()->remove($element);
    $this->getDoctrine()->getEntityManager()->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
  
  public function cleanElementAction($element_id)
  {
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
    }
    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id))
    )
    {
      return $this->jsonResponse(array(
        'status' => 'error',
        'errors' => array('NotFound')
      ));
    }
    
    $user_ids = $element->getReportIds();
    $element->setReportIds(null);
    $element->setCountReport(null);
    $this->getDoctrine()->getEntityManager()->persist($element);
    
    $users = $this->getDoctrine()->getEntityManager()
      ->createQuery('
        SELECT u FROM MuzichCoreBundle:User u
        WHERE u.id IN (:uids)'
      )
      ->setParameter('uids', $user_ids)
      ->getResult()
    ;
    
    foreach ($users as $user)
    {
      $user->addBadReport();
      $this->getDoctrine()->getEntityManager()->persist($user);
    }
    
    $this->getDoctrine()->getEntityManager()->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
  
}
