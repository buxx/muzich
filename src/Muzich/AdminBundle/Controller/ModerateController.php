<?php

namespace Muzich\AdminBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
//use Muzich\CoreBundle\Util\TagLike;
use Muzich\CoreBundle\Entity\UsersTagsFavorites;
use Muzich\CoreBundle\Entity\GroupsTagsFavorites;
use Muzich\CoreBundle\Managers\TagManager;
use Muzich\CoreBundle\Propagator\EventElement;
use Muzich\CoreBundle\Managers\CommentsManager;

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
    $count_comments = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->countForCommentToModerate();
    
    return array(
      'count_tags' => $count_tags,
      'count_elements' => $count_elements,
      'count_comments' => $count_comments
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
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
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
    
    $tagManager = new TagManager();
    if (!$tagManager->moderateTag($this->getDoctrine(), $tag, true))
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
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
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
    
    $tagManager = new TagManager();
    if (!$tagManager->moderateTag($this->getDoctrine(), $tag, false))
    {
      return $this->jsonResponse(array(
        'status'  => 'error',
        'message' => 'NotFound'
      ));
    }
    
    // Tout c'est bien passé, on incremente ceci dit le compteur
    // de tag refusés par la modération pour le ou les utilisateurs
    // ayant fait la demande d'ajout
    $uids = json_decode($tag->getPrivateids(), true);
    
    $users = $this->getDoctrine()->getEntityManager()
      ->createQuery('
        SELECT u FROM MuzichCoreBundle:User u
        WHERE u.id IN (:uids)'
      )
      ->setParameter('uids', $uids)
      ->getResult()
    ;
    
    // Pour chacun on augmente le compteur
    foreach ($users as $user)
    {
      $user->addModeratedTagCount();
      $this->getDoctrine()->getEntityManager()->persist($user);
    }
    
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
    if (($response = $this->mustBeConnected(true)))
    {
      return $response;
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
    if (!$tagManager->moderateTag($this->getDoctrine(), $tag, false, $tag_new_id))
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
    
    $event = new EventElement($this->container);
    $event->elementRemoved($element);
    $element->getOwner()->addModeratedElementCount();

    $this->getDoctrine()->getEntityManager()->persist($element->getOwner());
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
  
  /**
   *
   * @Template()
   */
  public function commentsAction()
  {
    // Récupération des elements
    $elements = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->getForCommentToModerate();
    
    $comments = array();
    foreach ($elements as $element)
    {
      $cm = new CommentsManager($element->getComments());
      foreach ($cm->getAlertedComments() as $comment)
      {
        $comments[] = array(
          'element_id' => $element->getId(),
          'comment'    => $comment
        );
      }
      
    }
    
    return array(
      'comments' => $comments
    );
  }
  
  /**
   * Considérer le commentaire signalé comme étant tout a fait acceptable
   * 
   * Ceci induit:
   * * Que le commentaire en question ne soit plus signalé 
   * * Que le ou les ids d'utilisateurs qui l'ont signalé comme soit "pénalisé
   * 
   * @param int $element_id
   * @param date $date 
   * 
   * @return Response
   */
  public function commentCleanAction($element_id, $date)
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
    
    $cm = new CommentsManager($element->getComments());
    // On nettoie le commentaire et on récupère les ids des "signaleurs"
    $ids = $cm->cleanAlertsOnComment($date);
    $element->setComments($cm->get());
    $element->setCountCommentReport($cm->countCommentAlert());
    
    $this->getDoctrine()->getEntityManager()->persist($element);
    
    // On récupère les user qui ont signalés ce commentaire
    $users = $this->getDoctrine()->getEntityManager()
      ->createQuery('
        SELECT u FROM MuzichCoreBundle:User u
        WHERE u.id IN (:uids)'
      )
      ->setParameter('uids', $ids)
      ->getResult()
    ;
    
    // Pour chacun on augmente le compteur de signalements inutiles
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
  
  /**
   * Considérer le commentaire signalé comme étant tout a fait acceptable
   * 
   * Ceci induit:
   * * Que le commentaire en question ne soit plus signalé 
   * * Que le ou les ids d'utilisateurs qui l'ont signalé comme soit "pénalisé
   * 
   * @param int $element_id
   * @param date $date 
   * 
   * @return Response
   */
  public function commentRefuseAction($element_id, $date)
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
    
    $cm = new CommentsManager($element->getComments());
    $comment = $cm->get($cm->getIndexWithDate($date));
    // On supprime le commentaire
    $cm->deleteWithDate($date);
    $element->setComments($cm->get());
    $element->setCountCommentReport($cm->countCommentAlert());
    
    // On récupère l'auteur du commentaire pour lui incrémenté son compteur
    // de contenu modéré
    $user = $this->getDoctrine()->getEntityManager()->getRepository('MuzichCoreBundle:User')
      ->findOneBy(array(
        'id' => $comment['u']['i']
      ));
    
    $user->addModeratedCommentCount();
    
    $this->getDoctrine()->getEntityManager()->persist($user);
    $this->getDoctrine()->getEntityManager()->persist($element);
    $this->getDoctrine()->getEntityManager()->flush();
    
    return $this->jsonResponse(array(
      'status' => 'success'
    ));
  }
}
