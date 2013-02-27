<?php

namespace Muzich\CoreBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InfoController extends Controller
{
  
   
  /**
   *
   * @Template() 
   */
  public function aboutAction()
  {
    return array();
  }
  
  /**
   *
   * @Template() 
   */
  public function developmentAction()
  {
    return array();
  }
  
  /**
   *
   * @Template() 
   */
  public function sitesAction()
  {
    return array();
  }
  
  public function teapotAction()
  {
    throw new HttpException(418, "I'm a teapot !");
  }
  
  /**
   *
   * @Template() 
   */
  public function cguAction()
  {
    return array();
  }
  
  public function helpboxAction($ressource_id)
  {
    if (!in_array($ressource_id, array(
      'element_add_url'
    )))
    {
      return $this->jsonNotFoundResponse();
    }
    
    return $this->jsonResponse(array(
      'status' => 'success',
      'data'     => $this->render('MuzichCoreBundle:Helpbox:'.$ressource_id.'.html.twig')
                      ->getContent()
    ));
  }
  
}