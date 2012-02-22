<?php

namespace Muzich\CoreBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Muzich\CoreBundle\ElementFactory\ElementManager;

class ElementController extends Controller
{
  
  /**
   *
   * @param type $element_id
   * @return Muzich\CoreBundle\Entity\Element 
   */
  protected function checkExistingAndOwned($element_id)
  {    
    if (!($element = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_id)))
    {
      throw $this->createNotFoundException('Not found');
    }
    
    if ($element->getOwner()->getId() != $this->getUserId())
    {
      throw $this->createNotFoundException('Not found');
    }
    
    return $element;
  }
  
  /**
   * 
   */
  public function editAction($element_id)
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
    
    $element = $this->checkExistingAndOwned($element_id);
    
    $element_tags = $element->getTags();
    $element->setTags($element->getTagsIdsJson());
    $form = $this->getAddForm($element);
    
    $search_tags = array();
    foreach ($element_tags as $tag)
    {
      $search_tags[$tag->getId()] = $tag->getName();
    }
    
    $template = 'MuzichCoreBundle:Element:ajax.element.edit.html.twig'; 
    if (!$this->getRequest()->isXmlHttpRequest())
    {
      $template = 'MuzichCoreBundle:Element:element.edit.html.twig'; 
    }
    
    $response = $this->render($template, array(
      'form'        => $form->createView(),
      'form_name'   => 'element_'.$element->getId(),
      'element_id'  => $element->getId(),
      'search_tags' => $search_tags
    ));
    
    if ($this->getRequest()->isXmlHttpRequest())
    {
      return $this->jsonResponse(array(
        'status'    => 'success',
        'form_name' => 'element_'.$element->getId(),
        'tags'      => $search_tags,
        'html'      => $response->getContent()
      ));
    }
    
    return $response;
  }
  
  /**
   *
   */
  public function updateAction($element_id)
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
    
    $element = $this->checkExistingAndOwned($element_id);
    // Si il y a un groupe on le retire pour le bind
    $group = $element->getGroup();
    $element->setGroup(null);
    $form = $this->getAddForm($element);
    $form->bindRequest($this->getRequest());
    
    $errors = array();
    $html = '';
    if ($form->isValid())
    {
      $status = 'success';
      $em = $this->getDoctrine()->getEntityManager();
      $factory = new ElementManager($element, $em, $this->container);
      $factory->proceedFill($this->getUser());
      // Si il y avais un groupe on le remet
      $element->setGroup($group);
      $em->persist($element);
      $em->flush();
      
      // Récupération du li
      $html = $this->render('MuzichCoreBundle:SearchElement:element.html.twig', array(
        'element'     => $element
      ))->getContent();
    }
    else
    {
      $status = 'error';
      // Récupération des erreurs
      $validator = $this->container->get('validator');
      $errorList = $validator->validate($form);
      
      foreach ($errorList as $error)
      {
        $errors[] = $this->trans($error->getMessage(), array(), 'validators');
      }
    }
    
    if ($this->getRequest()->isXmlHttpRequest())
    {
      return $this->jsonResponse(array(
        'status'  => $status,
        'html'    => $html,
        'errors'  => $errors
      ));
    }
    
    if ($status == 'success')
    {
      return $this->redirect($this->generateUrl('home'));
    }
    
    
    $element->setTagsWithIds(
      $this->getDoctrine()->getEntityManager(), 
      json_decode($element->getTags())
    );
    
    return $this->render('MuzichCoreBundle:Element:element.edit.html.twig', array(
      'form'        => $form->createView(),
      'form_name'   => 'element_'.$element->getId(),
      'element_id'  => $element->getId(),
      'search_tags' => $element->getTagsIdsJson()
    ));
  }
  
  public function removeAction($element_id)
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
    
    try {
      $element = $this->checkExistingAndOwned($element_id);
      $em = $this->getDoctrine()->getEntityManager();
      $em->remove($element);
      $em->flush();
      
      if ($this->getRequest()->isXmlHttpRequest())
      {
        return $this->jsonResponse(array('status' => 'success'));
      }
      $this->setFlash('success', 'element.remove.success');
      return $this->redirect($this->container->get('request')->headers->get('referer'));
    } 
    catch(Exception $e)
    {
      if ($this->getRequest()->isXmlHttpRequest())
      {
        return $this->jsonResponse(array('status' => 'error'));
      }
      $this->setFlash('error', 'element.remove.error');
      return $this->redirect($this->container->get('request')->headers->get('referer'));
    }
  }
  
}