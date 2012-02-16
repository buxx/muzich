<?php

namespace Muzich\CoreBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Muzich\CoreBundle\Searcher\ElementSearcher;
use Muzich\CoreBundle\Form\Search\ElementSearchForm;
use Symfony\Component\HttpFoundation\Response;
use Muzich\CoreBundle\Util\StrictCanonicalizer;

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
   * Ajoute le tag au début du tableau passé en paramètre si celui-ci
   * n'est pas a l'intérieur.
   * 
   * @param array $array
   * @param Tag $tag
   * @return array 
   */
  private function sort_addtop_if_isnt_in($array, $tag)
  {
    $in = false;
    for ($x=0;$x<=sizeof($array)-1;$x++)
    {
      if ($array[$x]['id'] == $tag['id'])
      {
        $in = true;
        break;
      }
    }
    
    if (!$in)
    {
      array_unshift($array, $tag);
      return $array;
    }
    return $array;
  }
  
  /**
   * Ajoute le tag a al fin du tableau passé en paramètre si celui-ci
   * n'est pas a l'intérieur.
   * 
   * @param array $array
   * @param Tag $tag
   * @return array 
   */
  private function sort_addbottom_if_isnt_in($array, $tag)
  {
    $in = false;
    for ($x=0;$x<=sizeof($array)-1;$x++)
    {
      if ($array[$x]['id'] == $tag['id'])
      {
        $in = true;
        break;
      }
    }
    
    if (!$in)
    {
      $array[] = $tag;
      return $array;
    }
    return $array;
  }
  
  /**
   * Organise le trie des tags de manière plus friendly user.
   *
   * @param array $tags
   * @param string $search
   * @return array 
   */
  private function sort_search_tags($tags, $search)
  {
    $same_found = false;
    $canonicalizer = new StrictCanonicalizer();
    $tag_sorted = array();
    
    foreach ($tags as $i => $tag)
    {
      // Pas plus de trois caractères en plus de la recherche
      $terms = array_merge(array($search), explode(' ', $search));
      foreach ($terms as $word)
      {
        if (strlen($word) > 1)
        {
          if (
            strlen(str_replace(strtoupper($canonicalizer->canonicalize($word)), '', strtoupper($tag['slug']))) < 4
            && $word != $search
          )
          {
            $tag_sorted = $this->sort_addtop_if_isnt_in($tag_sorted, $tag);
          }
        }
      }
      
    }
    
    // Uniquement dans le cas de présence d'un espace/separateur ou plus
    // on cherche les mot composé comme lui
    $terms = array_merge(
      explode(' ', $search), 
      explode('-', $search)
    );
    
    $tags_counteds = array();
    foreach ($tags as $i => $tag)
    {
      $terms_search = array_merge(
        explode(' ', $tag['slug']), 
        explode('-', $tag['slug'])
      );
      
      foreach ($terms as $word)
      {
        if (
          strpos(strtoupper($tag['slug']), strtoupper($word)) !== false
          && count($terms_search) > 2
        )
        {
          $count = 1;
          if (array_key_exists($tag['id'], $tags_counteds))
          {
            $count = ($tags_counteds[$tag['id']]['count'])+1;
          }
          $tags_counteds[$tag['id']] = array(
            'count' => $count,
            'tag'   => $tag
          );
        }
      }
    }
    
    foreach ($tags_counteds as $id => $counted)
    {
      if ($counted['count'] > 1)
      {
        // Ci-dessous on va chercher a voir si le tag et la recherche on le 
        // même nombre de mots, si c'est le cas on pourra considérer cette 
        // recherche comme lié a un tag connu.
        
        $words_search = array_merge(
          explode(' ', $search), 
          explode('-', $search)
        );
        
        $words_tag = array_merge(
          explode(' ', $counted['tag']['slug']), 
          explode('-', $counted['tag']['slug'])
        );
        
        if (count($words_search) == count($words_tag))
        {
          $same_found = true;
        }
        
        // Cette verif permet de ne pas ajouter les tags qui n'ont qu'un mot
        // Si on ajouté ce tag maintenant il ne serais pas ajouté au controle en dessous
        // (nom identique) et donc pas au dessus.
        $tag_sorted = $this->sort_addtop_if_isnt_in($tag_sorted, $counted['tag']);
        
      }
    }
    
    foreach ($tags as $i => $tag)
    {
      // Chaine de caractère identique
      $terms = array_merge(
        array($search), 
        explode(' ', $search), 
        explode('-', $search),
        array(str_replace(' ', '-', $search)),
        array(str_replace('-', ' ', $search))
      );
      
      foreach ($terms as $word)
      {
        if (strlen($word) > 1)
        {
          if (strtoupper($canonicalizer->canonicalize($word)) == strtoupper($tag['slug']))
          {
            // Ci-dessous on déduit si le mot étant identique au tag représente bien
            // le terme de recherche. De façon a si c'est le cas pouvoir dire:
            // oui le terme recherché est connu.
            if (in_array($word, array(
              $search,
              str_replace(' ', '-', $search),
              str_replace('-', ' ', $search)
            ))) 
            { 
              $same_found = true;
            }
            $tag_sorted = $this->sort_addtop_if_isnt_in($tag_sorted, $tag);
          }
        }
      }
    }
    
    foreach ($tags as $i => $tag)
    {
      $tag_sorted = $this->sort_addbottom_if_isnt_in($tag_sorted, $tag);
    }
    
    return array(
      'tags'       => $tag_sorted,
      'same_found' => $same_found
    );
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
   
    $string_search = trim($string_search);
    $canonicalizer = new StrictCanonicalizer();
    
    if ($this->getRequest()->isXmlHttpRequest())
    {
      if (strlen($string_search) > 1)
      {
        $words = array_merge(
          explode(' ', $string_search),
          explode('-', $string_search),
          explode(',', $string_search),
          explode(', ', $string_search)
        );
        $where = '';
        $params = array();
        foreach ($words as $i => $word)
        {
          if (strlen($word) > 1)
          {
            $word = $canonicalizer->canonicalize($word);
            if ($where == '')
            {
              $where .= 'WHERE UPPER(t.slug) LIKE :str'.$i;
            }
            else
            {
              $where .= ' OR UPPER(t.slug) LIKE :str'.$i;
            }

            $params['str'.$i] = '%'.strtoupper($word).'%';
          }
        }

        $params['uid'] = '%"'.$this->getUserId().'"%';
        $tags = $this->getDoctrine()->getEntityManager()->createQuery("
          SELECT t.name, t.slug, t.id FROM MuzichCoreBundle:Tag t
          $where
          
          AND (t.tomoderate = '0'
          OR t.privateids LIKE :uid)
          
          ORDER BY t.name ASC"
        )->setParameters($params)
        ->getScalarResult()
        ;
        
        $tags_response = array();
        foreach ($tags as $tag)
        {
          $tags_response[] = array(
            'name' => $tag['name'], 
            'id'   => $tag['id'],
            'slug' => $tag['slug']
          );
        }
        
        $sort_response = $this->sort_search_tags($tags_response, $string_search);
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