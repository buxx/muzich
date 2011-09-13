<?php

namespace Muzich\HomeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Muzich\CoreBundle\Searcher\ElementSearcher;
use Doctrine\ORM\Query;

class HomeController extends Controller
{
  /**
   * @Template()
   */
  public function indexAction()
  {        
    return array('search' => $this->getElementSearcher($this->getUser()->getId()));
  }
}