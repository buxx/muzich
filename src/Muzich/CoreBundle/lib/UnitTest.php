<?php

namespace Muzich\CoreBundle\lib;

require_once(__DIR__ . "/../../../../app/AppKernel.php");
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\ElementFactory\ElementManager;

class UnitTest extends \PHPUnit_Framework_TestCase
{
    protected $_container;
  
  public function __construct()
  {
    $kernel = new \AppKernel("test", true);
    $kernel->boot();
    $this->_container = $kernel->getContainer();
    parent::__construct();
  }

  protected function get($service)
  {
    return $this->_container->get($service);
  }
  
  /**
   *
   * @return \Symfony\Bundle\DoctrineBundle\Registry
   */
  protected function getDoctrine()
  {
    return $this->get('doctrine');
  }
  
  protected function getParam($param)
  {
    return $this->_container->getParameter($param);
  }
  
  protected function proceed_elementAndFill($user, $name, $url, $tag_ids, $final_embed)
  {
    $youtube_width = '590';
    $youtube_height = '300';
        
    $element = new Element();
    $element->setName($name);
    $element->setTags(json_encode($tag_ids));
    $element->setUrl($url);
    
    $factory = new ElementManager(
      $element, 
      $this->getDoctrine()->getEntityManager(), 
      $this->_container
    );
    $factory->proceedFill($user);
        
    $this->assertEquals($element->getName(), $name);
    $this->assertEquals($element->getUrl(), $url);
    
    // check tags
    $element_tag_ids = array();
    foreach ($element->getTags() as $tag)
    {
      $element_tag_ids[] = $tag->getId();
    }
    
    $this->assertEquals($element_tag_ids, $tag_ids);
    $this->assertEquals($element->getEmbed(), $final_embed);
  }
  
  protected function getUser($username)
  {
    return $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername($username)
    ;
  }
  
}