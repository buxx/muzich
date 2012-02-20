<?php

namespace Muzich\AdminBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
//use Muzich\CoreBundle\Util\TagLike;

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
    
    if (!($tag = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneBy(array(
      'id'         => $tag_id,
      'tomoderate' => true
    ))))
    {
      return $this->jsonResponse(array(
        'status'  => 'error',
        'message' => 'NotFound'
      ));
    }
    
    $tag->setTomoderate(false);
    $tag->setPrivateids(null);
    $this->getDoctrine()->getEntityManager()->persist($tag);
    $this->getDoctrine()->getEntityManager()->flush();
    
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
    
    if (!($tag = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneBy(array(
      'id'         => $tag_id,
      'tomoderate' => true
    ))))
    {
      return $this->jsonResponse(array(
        'status'  => 'error',
        'message' => 'NotFound'
      ));
    }
    
    $this->getDoctrine()->getEntityManager()->remove($tag);
    $this->getDoctrine()->getEntityManager()->flush();
    
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
    
    if (
      !($tag = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneBy(array(
        'id'         => $tag_id,
        'tomoderate' => true
      )))
      || !($new_tag = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneById($tag_new_id))
    )
    {
      return $this->jsonResponse(array(
        'status'  => 'error',
        'message' => 'NotFound'
      ));
    }
    
    /*
     * Trois cas de figures ou sont utilisés les tags
     *  * Sur un élément
     *  * Tag favori
     *  * Tag d'un groupe
     */
    $em = $this->getDoctrine()->getEntityManager();
    
    $netags = array();
    foreach ($elements = $this->getDoctrine()->getEntityManager()->createQuery("
      SELECT e, t FROM MuzichCoreBundle:Element e
      JOIN e.tags t
      WHERE t.id  = :tid
    ")
      ->setParameter('tid', $tag_new_id)
      ->getResult() as $element)
    {
      
      // TODO: a faire ...
//      foreach ($element->getTags() as $etag)
//      {
//        if ($etag->getId() != $tag->getId())
//        {
//          $netags[] = $etag;
//        }
//      }
//      $netags[] = $new_tag;
//      
//      $element->setTags(array());
//      $em->persist($element);
//      $em->flush();
//      
//      $element->setTags($netags);
//      $em->persist($element);
//      $em->flush();
    }
    
    $em->remove($tag);
    $em->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
  
}
