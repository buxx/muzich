<?php

namespace Muzich\UserBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class EventController extends Controller
{
  
  protected $event;
    
  public function infoBarAction()
  {
    $events = $this->getDoctrine()->getRepository('MuzichCoreBundle:Event')
      ->getEvents($this->getUserId())
    ;
    
    return $this->render('MuzichUserBundle:Info:bar.html.twig', array(
      'events' => $events
    ));
  }
  
  public function viewElementsAction($event_id)
  {
    if (($response = $this->mustBeConnected()))
    {
      return $response;
    }
    
    if (!($event = $this->getDoctrine()->getRepository('MuzichCoreBundle:Event')
      ->findOneById($event_id)))
    {
      if ($this->getRequest()->isXmlHttpRequest())
      {
        return $this->jsonResponse(array(
          'status' => 'error',
          'errors' => array('NotFound')
        ));
      }
      return $this->redirect($this->generateUrl('index'));
    }
    
    if ($event->getUser()->getId() != $this->getUserId())
    {
      if ($this->getRequest()->isXmlHttpRequest())
      {
        return $this->jsonResponse(array(
          'status' => 'error',
          'errors' => array('NotAllowed')
        ));
      }
      throw $this->createNotFoundException('Ressource ajax uniquement.');
    }
    
    // A partir d'ici on a ce qu'il faut.
    // On modifie l'Element Searcher en lui donnat les ids correspondant a l'event
    
    $user = $this->getUser();
    $es = $this->getNewElementSearcher();
    $es->setNoTags();
    $es->setIds($event->getIds());
    $es->setIdsDisplay($event->getType());
    
    $this->setElementSearcherParams($es->getParams(), $user->getPersonalHash($event->getId()));
    
        
    $elements = $es->getElements($this->getDoctrine(), $this->getUserId());
    
    return $this->render('MuzichUserBundle:Event:elements.html.twig', array(
      'elements'        => $elements,
      'last_element_id' => $elements[count($elements)-1]->getId(),
      'event'           => $event
    ));
  }
  
  public function userCanAccessToThisEvent($event_id)
  {
    if (!($this->event = $this->getDoctrine()->getRepository('MuzichCoreBundle:Event')
      ->findOneById($event_id)))
    {
      throw $this->createNotFoundException();
    }
    
    if ($this->event->getUser()->getId() != $this->getUserId())
    {
      throw $this->createNotFoundException();
    }
  }
  
  public function userUseCorrectToken($token)
  {
    if ($this->getUser()->getPersonalHash($this->event->getId()) != $token)
    {
      throw new AccessDeniedException();
    }
  }
  
  public function deleteAction($event_id, $token)
  {
    $this->userCanAccessToThisEvent($event_id);
    $this->userUseCorrectToken($token);
    
    $this->getEntityManager()->remove($this->event);
    $this->flush();
    
    return $this->redirect($this->generateUrl('home'));
  }
  
}
