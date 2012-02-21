<?php

namespace Muzich\AdminBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
//use Muzich\CoreBundle\Util\TagLike;
use Muzich\CoreBundle\Entity\UsersTagsFavorites;
use Muzich\CoreBundle\Entity\GroupsTagsFavorites;

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
    
    // Sur un élément
    foreach ($elements = $this->getDoctrine()->getEntityManager()->createQuery("
      SELECT e, t FROM MuzichCoreBundle:Element e
      JOIN e.tags t
      WHERE t.id  = :tid
    ")
      ->setParameter('tid', $tag_id)
      ->getResult() as $element)
    {
      // Pour chaque elements lié a ce tag
      // On ajoute un lien vers le nouveau tag si il n'en n'a pas déjà
      if (!$element->hasTag($new_tag))
      {
        $element->addTag($new_tag);
        $em->persist($element);
      }
    }
    
    // Tag favoris
    foreach ($favorites = $this->getDoctrine()->getEntityManager()->createQuery("
      SELECT f FROM MuzichCoreBundle:UsersTagsFavorites f
      WHERE f.tag  = :tid
    ")
      ->setParameter('tid', $tag_id)
      ->getResult() as $fav)
    {
      // Pour chaque favoris utilisant ce tag on regarde si l'utilisateur
      // n'a pas déjà le nouveau tag en favoris.
      if (!$this->getDoctrine()->getEntityManager()->createQuery("
        SELECT COUNT(f.id) FROM MuzichCoreBundle:UsersTagsFavorites f
        WHERE f.tag  = :tid AND f.user = :uid
      ")
      ->setParameters(array('tid' => $tag_new_id, 'uid' => $fav->getUser()->getId()))
      ->getSingleScalarResult())
      {
        $new_fav = new UsersTagsFavorites();
        $new_fav->setTag($new_tag);
        $new_fav->setUser($fav->getUser());
        $new_fav->setPosition($fav->getPosition());
        $em->persist($new_fav);
      }
      $em->remove($fav);
    }
    
    // groupe
    foreach ($this->getDoctrine()->getEntityManager()->createQuery("
      SELECT f FROM MuzichCoreBundle:GroupsTagsFavorites f
      WHERE f.tag  = :tid
    ")
      ->setParameter('tid', $tag_id)
      ->getResult() as $fav)
    {
      // Pour chaque favoris utilisant ce tag on regarde si le groupe
      // n'a pas déjà le nouveau tag en favoris.
      if (!$this->getDoctrine()->getEntityManager()->createQuery("
        SELECT COUNT(f.id) FROM MuzichCoreBundle:GroupsTagsFavorites f
        WHERE f.tag  = :tid AND f.group = :gid
      ")
      ->setParameters(array('tid' => $tag_new_id, 'gid' => $fav->getGroup()->getId()))
      ->getSingleScalarResult())
      {
        $new_fav = new GroupsTagsFavorites();
        $new_fav->setTag($new_tag);
        $new_fav->setGroup($fav->getGroup());
        $new_fav->setPosition($fav->getPosition());
        $em->persist($new_fav);
      }
      $em->remove($fav);
    }
    
    $em->remove($tag);
    $em->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
  
}
