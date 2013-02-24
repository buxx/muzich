<?php

namespace Muzich\AdminBundle\Controller\Moderate_comment;

use Muzich\CoreBundle\lib\Controller as BaseController;
use Muzich\CoreBundle\Managers\CommentsManager;

class ListController extends BaseController
{
  
  public function listAction()
  {
    return $this->render('MuzichAdminBundle:Moderate_commentList:list.html.twig', array(
      'Comments' => $this->getComments()
    ));
  }
  
  protected function getComments()
  {
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
          'username'   => $comment['u']['n'],
          'comment'    => $comment['c'],
          'date'       => $comment['d']
        );
      }
      
    }
    
    return $comments;
  }
  
}
