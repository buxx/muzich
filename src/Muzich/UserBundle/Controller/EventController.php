<?php

namespace Muzich\UserBundle\Controller;

use Muzich\CoreBundle\lib\Controller;

class EventController extends Controller
{
    
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
      throw $this->createNotFoundException('Ressource ajax uniquement.');
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
    //$this->getDoctrine()->getEntityManager()->remove($event);
    //$this->getDoctrine()->getEntityManager()->flush();
        
    $elements = $es->getElements($this->getDoctrine(), $this->getUserId());
    
    return $this->render('MuzichUserBundle:Event:elements.html.twig', array(
      'elements'        => $elements,
      'last_element_id' => $elements[count($elements)-1]->getId(),
      'event'           => $event
    ));
  }
  
}
