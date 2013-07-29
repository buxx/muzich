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
   */
  public function indexAction($count = null, $network = null, $login = false, $search_object = null, $context = 'home', $page_title = null)
  {
    if (!$search_object)
      $search_object = $this->getElementSearcher($count);
    
    $user = $this->getUser(true, array('join' => array(
      'groups_owned'
    )), true);
    
    if (!$network && !$search_object->getNetwork())
    {
      $search_object->setNetwork(ElementSearcher::NETWORK_PUBLIC);
    }
    
    $search_form = $this->getSearchForm($search_object);
    $add_form = $this->getAddForm();
    
    $elements = $search_object->getElements($this->getDoctrine(), $this->getUserId(true));
    
    return $this->render('MuzichHomeBundle:Home:index.html.twig', array(
      'search_tags_id'   => $search_object->getTags(),
      'ids_display'      => $search_object->getIdsDisplay(),
      'user'             => $user,
      'add_form'         => $add_form->createView(),
      'add_form_name'    => 'add',
      'search_form'      => $search_form->createView(),
      'search_form_name' => 'search',
      'network_public'   => $search_object->isNetworkPublic(),
      'elements'         => $elements,
      'from_url'         => $this->getRequest()->get('from_url'),
      'display_launch_demo' => true,
      'login'            => $login,
      'email_token'      => $this->getEmailTokenIfExist(),
      'elements_context' => $context,
      'page_title'       => $page_title
    ));
  }
  
  public function showOneElementAction($element_id)
  {
    $page_title = null;
    
    if (($element = $this->getElementWithId($element_id)))
      $page_title = $element->getName();
    
    $es = $this->getNewElementSearcher();
    $es->setNoTags();
    $es->setIds(array($element_id));
    return $this->indexAction(null, null, false, $es, 'one', $page_title);
  }
  
  protected function getEmailTokenIfExist()
  {
    if ($this->get("session")->get('user.confirm_email.token'))
    {
      return $this->get("session")->get('user.confirm_email.token');
    }
    
    return null;
  }
  
  public function needTagsAction()
  {
    $es = $this->getNewElementSearcher();
    $es->init(array(
      'count'     => $this->container->getParameter('search_default_count'),
      'need_tags' => true,
      'tags'      => array()
    ));
    $this->setElementSearcherParams($es->getParams(), $this->getUser()->getPersonalHash('needstagpage'));
    $elements = $es->getElements($this->getDoctrine(), $this->getUserId());
    
    return $this->render('MuzichHomeBundle:Home:need_tags.html.twig', array(
      'elements'        => $elements,
      'topmenu_active'  => 'needs-tags',
      'last_element_id' => (count($elements))?$elements[count($elements)-1]->getId():null
    ));
  }
  
}