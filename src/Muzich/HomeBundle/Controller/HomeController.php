<?php

namespace Muzich\HomeBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Muzich\CoreBundle\Searcher\ElementSearcher;

class HomeController extends Controller
{
  
  /**
   * Page d'accueil ("home") de l'utilisateur. Cette page regroupe le fil
   * d'éléments général et personalisable et de quoi ajouter un élément.
   * 
   * @Template()
   */
  public function indexAction($count = null, $network = 'public')
  {
    $search_object = $this->getElementSearcher($count);
    $user = $this->getUser(true, array('join' => array(
      'groups_owned'
    )), true);
    $search_object->setNetwork($network);
    $search_form = $this->getSearchForm($search_object);
    $add_form = $this->getAddForm();
    
    $elements = $search_object->getElements($this->getDoctrine(), $this->getUserId());
    //$count_elements = count($elements);
        
    return array(
      'search_tags_id'   => $search_object->getTags(),
      'ids_display'      => $search_object->getIdsDisplay(),
      'user'             => $user,
      'add_form'         => $add_form->createView(),
      'add_form_name'    => 'add',
      'search_form'      => $search_form->createView(),
      'search_form_name' => 'search',
      'network_public'   => $search_object->isNetworkPublic(),
      'elements'         => $elements,
      'from_url'         => $this->getRequest()->get('from_url')
    );
  }
  
  public function needTagsAction()
  {
    $es = new ElementSearcher();
    $es->init(array(
      'count'     => $this->container->getParameter('search_default_count'),
      'need_tags' => true
    ));
    
    return $this->render('MuzichHomeBundle:Home:need_tags.html.twig', array(
      'elements' => $es->getElements($this->getDoctrine(), $this->getUserId()),
      'topmenu_active' => 'needs-tags'
    ));
  }
  
}