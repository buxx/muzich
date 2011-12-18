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
  public function indexAction($count = null)
  {
    $search_object = $this->getElementSearcher($count);
    $user = $this->getUser(true, array('join' => array(
      'groups_owned'
    )), true);
    
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
        //'groups' => $user->getGroupsOwnedArray(),
      )
    );
    
    return array(
      'user'        => $this->getUser(),
      'add_form'    => $add_form->createView(),
      'search_form' => $search_form->createView(),
      'elements'    => $search_object->getElements($this->getDoctrine(), $this->getUserId()),
      'more_count'  => ($count)?$count+$this->container->getParameter('search_default_count'):$this->container->getParameter('search_default_count')*2
    );
  }
}