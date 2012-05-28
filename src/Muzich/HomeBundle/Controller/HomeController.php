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
    
    $search_form = $this->getSearchForm($search_object);
    $add_form = $this->getAddForm();
    
    $elements = $search_object->getElements($this->getDoctrine(), $this->getUserId());
    $count_elements = count($elements);
    
    return array(
      'search_tags_id'   => $search_object->getTags(),
      'ids_display'      => $search_object->getIdsDisplay(),
      'user'             => $this->getUser(),
      'add_form'         => $add_form->createView(),
      'add_form_name'    => 'add',
      'search_form'      => $search_form->createView(),
      'search_form_name' => 'search',
      'network_public'   => $search_object->isNetworkPublic(),
      'elements'         => $elements,
      'display_more_button' => ($count_elements >= $this->container->getParameter('search_default_count'))?true:false
    );
  }
}