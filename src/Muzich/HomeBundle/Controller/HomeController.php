<?php

namespace Muzich\HomeBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Doctrine\ORM\Query;
use Muzich\CoreBundle\Form\Search\ElementSearchForm;

class HomeController extends Controller
{
  
  /**
   * @Template()
   */
  public function indexAction()
  {
    $search_object = $this->getElementSearcher($this->getUser()->getId());
    
    $search_form = $this->createForm(
      new ElementSearchForm(), 
      $search_object->getParams(),
      array('tags' => $this->getTagsArray())
    );
        
    return array(
      'search_object' => $search_object,
      'search_form'   => $search_form->createView()
    );
  }
}