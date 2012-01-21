<?php

namespace Muzich\CoreBundle\Controller;

use Muzich\CoreBundle\lib\Controller;

class ElementController extends Controller
{
  
  /**
   * 
   */
  public function editAction($element_id)
  {
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)))
    {
      $this->createNotFoundException();
    }
    
    if ($element->getOwner()->getId() != $this->getUserId())
    {
      $this->createNotFoundException();
    }
    
    $element_tags = $element->getTags();
    $element->setTags(array());
    $form = $this->getAddForm($element);
    
    $search_tags = array();
    foreach ($element_tags as $tag)
    {
      $search_tags[$tag->getId()] = $tag->getName();
    }
    
    $html = $this->render('MuzichCoreBundle:Element:element.edit.html.twig', array(
      'form'        => $form->createView(),
      'form_name'   => $form->getName(),
      'element_id'  => $element->getId(),
      'search_tags' => $search_tags
    ))->getContent();
    
    return $this->jsonResponse(array(
      'status' => 'success',
      'html'   => $html
    ));
  }
  
  /**
   *
   */
  public function updateAction()
  {
    return array();
  }
  
}