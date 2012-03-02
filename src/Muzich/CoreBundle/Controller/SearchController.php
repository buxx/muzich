<?php

namespace Muzich\CoreBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Muzich\CoreBundle\Searcher\ElementSearcher;
use Muzich\CoreBundle\Form\Search\ElementSearchForm;
use Symfony\Component\HttpFoundation\Response;
use Muzich\CoreBundle\Util\TagLike;

class SearchController extends Controller
{
  
  protected function searchElementsMore($elements, $invertcolors, $message)
  {
    
    $end = (($count = count($elements)) < $this->container->getParameter('search_ajax_more'));
    $html = '';
    if ($count)
    {
      $html = $this->render('MuzichCoreBundle:SearchElement:default.html.twig', array(
        'user'        => $this->getUser(),
        'elements'    => $elements,
        'invertcolor' => $invertcolors
      ))->getContent();
    }
    
    return $this->jsonResponse(array(
      'status'  => 'success',
      'count'   => $count,
      'message' => $message,
      'html'    => $html,
      'end'     => $end
    ));
  }
  
  /**
   * Procédure de recherche, qui met a jour l'objet de recherche (ainsi
   * que les paramétres en session). 
   * 
   */
  public function searchElementsAction($id_limit = null, $invertcolors = false)
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
    
    $request = $this->getRequest();
    $search_object = $this->getElementSearcher();
    
    $search_form = $this->getSearchForm($search_object);
    
    $form_submited = false;
    if ($request->getMethod() == 'POST')
    {
      $form_submited = true;
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
      if ($form_submited)
      {
        $message = $this->trans(
          'noelements.sentence_filter',
          array('%link_string%' => $this->trans(
            'noelements.sentence_filter_link_string',
            array(),
            'elements'
          )),
          'elements'
        );
      }
      else
      {
        $message = $this->trans(
          'elements.ajax.more.noelements', 
          array(), 
          'elements'
        );
      }
      
      // template qui apelle doSearchElementsAction 
      $search = $this->getElementSearcher();
      $search->update(array(
        'count'    => $this->container->getParameter('search_ajax_more'),
        'id_limit' => $id_limit
      ));
      $elements = $search->getElements($this->getDoctrine(), $this->getUserId());
      
      return $this->searchElementsMore($elements, $invertcolors, $message);      
    }
    else
    {
      return $this->redirect($this->generateUrl('home'));
    }
  }
  
  public function searchElementsShowAction($type, $object_id, $id_limit, $invertcolors)
  {
    if ($this->getRequest()->isXmlHttpRequest())
    {
      $object = null;
      $param_id =  '';
      if ($type == 'user')
      {
        $object = $this->getDoctrine()
          ->getRepository('MuzichCoreBundle:User')
          ->findOneBy(array('id' => $object_id))
        ;
        $param_id = 'user_id';
      }
      elseif ($type == 'group')
      {
        $object = $this->getDoctrine()
          ->getRepository('MuzichCoreBundle:Group')
          ->findOneById($object_id)
        ;
        $param_id = 'group_id';
      }

      if (!$object)
      { 
        throw new \Exception('Object Unknow');
      }

      $search = $this->createSearchObject(array(
        $param_id  => $object->getId(),
        'count'    => $this->container->getParameter('search_ajax_more'),
        'id_limit' => $id_limit
      ));

      $elements = $search->getElements($this->getDoctrine(), $this->getUserId());
      
      return $this->searchElementsMore($elements, $invertcolors,
        $this->trans(
          'elements.ajax.more.noelements', 
          array(), 
          'elements'
        )
      );
    }
    
    throw new \Exception('XmlHttpRequest only for this action');
  }
    
  /**
   *
   * @param string $string_search 
   */
  public function searchTagAction($string_search, $timestamp)
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
    
    if ($this->getRequest()->isXmlHttpRequest())
    {
      if (strlen(trim($string_search)) > 1)
      {
        $TagLike = new TagLike($this->getDoctrine());
        $sort_response = $TagLike->getSimilarTags($string_search, $this->getUserId());
      
        $status = 'success';
        $error  = '';
        $message = $this->trans(
          'tags.search.message_found', 
          array('%string%' => $string_search), 
          'userui'
        );
      }
      else
      {
        $status = 'error';
        $sort_response = array('tags' => array(), 'same_found' => false);
        $error = 'Vous devez saisir au moins deux caractères';
        $message  = '';
      }
      
      $return_array = array(
        'status'     => $status,
        'timestamp'  => $timestamp,
        'error'      => $error,
        'message'    => $message,
        'same_found' => $sort_response['same_found'],
        'data'       => $sort_response['tags']
        
      );
      
      $response = new Response(json_encode($return_array));
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