<?php

namespace Muzich\CoreBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Muzich\CoreBundle\Searcher\ElementSearcher;
use Muzich\CoreBundle\Form\Search\ElementSearchForm;

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
    
    $search_form = $this->createForm(
      new ElementSearchForm(), 
      $search_object->getParams(),
      array('tags' => $this->getTagsArray())
    );
    
    if ($request->getMethod() == 'POST')
    {
      $search_form->bindRequest($request);
      // Si le formulaire est valide
      if ($search_form->isValid())
      {
        // On met a jour l'objet avec les nouveaux paramétres saisie dans le form
        $search_object->update($search_form->getData());
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
  
}