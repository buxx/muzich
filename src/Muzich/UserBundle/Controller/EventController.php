<?php

namespace Muzich\UserBundle\Controller;

use Muzich\CoreBundle\lib\Controller;

class EventController extends Controller
{
    
  public function infoBarAction()
  {
    $events = $this->getDoctrine()->getRepository('MuzichCoreBundle:Event')
      ->getNotViewEvents($this->getUserId())
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
    
    $es = $this->getElementSearcher();
    $es->setIds($event->getIds());
    $this->setElementSearcherParams($es->getParams());
    $event->setView(true);
    $this->getDoctrine()->getEntityManager()->persist($event);
    $this->getDoctrine()->getEntityManager()->flush();
    
    // Si ajax
    if ($this->getRequest()->isXmlHttpRequest())
    {
      $html = $this->render('MuzichCoreBundle:SearchElement:default.html.twig', array(
        'user'        => $this->getUser(),
        'elements'    => $es->getElements($this->getDoctrine(), $this->getUserId()),
        'invertcolor' => false
      ))->getContent();
      
      return $this->jsonResponse(array(
        'status'  => 'success',
        'html'    => $html
      ));
    }
    
    // Sinon on redirige vers home
    return $this->redirect($this->generateUrl('home'));
  }
    
}
