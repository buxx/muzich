<?php

namespace Muzich\HomeBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Doctrine\ORM\Query;
use Muzich\CoreBundle\Form\Search\ElementSearchForm;
use Muzich\CoreBundle\Form\Element\ElementAddForm;

class HomeController extends Controller
{
  
  /**
   * Page d'accueil ("home") de l'utilisateur. Cette page regroupe le fil
   * d'éléments général et personalisable et de quoi ajouter un élément.
   * 
   * @Template()
   */
  public function indexAction()
  {
    $search_object = $this->getElementSearcher();
    
    $search_form = $this->createForm(
      new ElementSearchForm(), 
      $search_object->getParams(),
      array(
        'tags' => $tags = $this->getTagsArray()
      )
    );
    
    $add_form = $this->createForm(
      new ElementAddForm(),
      array(),
      array(
        'tags' => $tags,
        'groups' => $this->getGroupsArray(),
      )
    );
        
    return array(
      'user'        => $this->getUser(),
      'add_form'    => $add_form->createView(),
      'search_form' => $search_form->createView(),
      'elements'    => $search_object->getElements($this->getDoctrine(), $this->getUserId())
    );
  }
}