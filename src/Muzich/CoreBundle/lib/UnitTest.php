<?php

namespace Muzich\CoreBundle\lib;

require_once(__DIR__ . "/../../../../app/AppKernel.php");
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Managers\ElementManager;

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
   * @return \Doctrine\Bundle\DoctrineBundle\Registry
   */
  protected function getDoctrine()
  {
    return $this->get('doctrine');
  }
  
  protected function getParam($param)
  {
    return $this->_container->getParameter($param);
  }
  
  protected function proceed_elementAndFill($user, $name, $url, $tag_ids, $final_embed = null, $embed_contained = null)
  {
    $element = new Element();
    $element->setName($name);
    $element->setTags(json_encode($tag_ids));
    $element->setUrl($url);
    
    $factory = new ElementManager(
      $element, 
      $this->getDoctrine()->getManager(), 
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
    
    if ($final_embed)
      $this->assertEquals($element->getEmbed(), $final_embed);
    
    if ($embed_contained)
      $this->assertTrue((strpos($element->getEmbed(), $embed_contained) !== false));
  }
  
  protected function proceed_element_datas_api($user, $url)
  {
    $element = new Element();
    $element->setUrl($url);
    
    $factory = new ElementManager($element, 
      $this->getDoctrine()->getManager(), $this->_container);
    $factory->proceedFill($user);
    
    return $element->getDatas();
  }


  protected function getUser($username)
  {
    return $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername($username)
    ;
  }


  protected function getTag($name)
  {
    return $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->findOneByName($name)
    ;
  }
  
  protected function persist($entity)
  {
    $this->getDoctrine()->getManager()->persist($entity);
  }
  
  protected function flush()
  {
    $this->getDoctrine()->getManager()->flush();
  }
  
  /**
   *
   * @return \Doctrine\ORM\EntityManager 
   */
  protected function getEntityManager()
  {
    return $this->getDoctrine()->getManager();
  }
  
  /**
   * Raccourcis de findOneBy
   * 
   * @param string $entityName
   * @param array $params
   * @return object 
   */
  protected function findOneBy($entityName, array $params)
  {
    return $this->getEntityManager()->getRepository('MuzichCoreBundle:'.$entityName)
      ->findOneBy($params);
  }
  
}