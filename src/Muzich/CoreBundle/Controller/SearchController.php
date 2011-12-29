<?php

namespace Muzich\CoreBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Muzich\CoreBundle\Searcher\ElementSearcher;
use Muzich\CoreBundle\Form\Search\ElementSearchForm;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{
  
  /**
   * Procédure de recherche, qui met a jour l'objet de recherche (ainsi
   * que les paramétres en session). 
   * 
   */
  public function searchElementsAction()
  {
    $request = $this->getRequest();
    $search_object = $this->getElementSearcher();
    
    $search_form = $this->getSearchForm($search_object);
    
    if ($request->getMethod() == 'POST')
    {
      $search_form->bindRequest($request);
      // Si le formulaire est valide
      if ($search_form->isValid())
      {
        // On met a jour l'objet avec les nouveaux paramétres saisie dans le form
        $data = $search_form->getData();
        
        // Le formulaire nous permet de récupérer uniquement les ids.
        // On va donc chercher les name en base pour le passer a l'objet
        // ElementSearch
        $data['tags'] = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
          ->getTagsForElementSearch(json_decode($data['tags'], true));
        
        $search_object->update($data);
        // Et on met a jour la "mémoire" de la recherche
        $this->setElementSearcherParams($search_object->getParams());
      }
    }
    
    if ($this->getRequest()->isXmlHttpRequest())
    {
      // template qui apelle doSearchElementsAction 
    }
    else
    {
      return $this->redirect($this->generateUrl('home'));
    }
  }
  
  /**
   *
   * @param string $string_search 
   */
  public function searchTagAction($string_search)
  {
    if ($this->getRequest()->isXmlHttpRequest())
    {
      $tags = $this->getDoctrine()->getEntityManager()->createQuery("
        SELECT t.name FROM MuzichCoreBundle:Tag t
        WHERE UPPER(t.name) LIKE :str
        ORDER BY t.name ASC"
      )->setParameter('str', '%'.strtoupper($string_search).'%')
      ->getScalarResult()
      ;
      
      $tags_response = array();
      foreach ($tags as $tag)
      {
        $tags_response[] = $tag['name'];
      }
      
      $response = new Response(json_encode($tags_response));
      $response->headers->set('Content-Type', 'application/json; charset=utf-8');
      return $response;
    }
    
    throw $this->createNotFoundException('Cette ressource n\'est pas accessible');
  }
  
  /**
   *
   * @param type $string_search
   * @return Response 
   */
  public function searchTagIdAction($string_search)
  {
    if ($this->getRequest()->isXmlHttpRequest())
    {
      $tag_id = $this->getDoctrine()->getEntityManager()->createQuery("
        SELECT t.id FROM MuzichCoreBundle:Tag t
        WHERE t.name = :str
        ORDER BY t.name ASC"
      )->setParameter('str', $string_search)
      ->getSingleScalarResult()
      ;
      
      $response = new Response(json_encode($tag_id));
      $response->headers->set('Content-Type', 'application/json; charset=utf-8');
      return $response;
    }
    
    throw $this->createNotFoundException('Cette ressource n\'est pas accessible');
  }
  
}