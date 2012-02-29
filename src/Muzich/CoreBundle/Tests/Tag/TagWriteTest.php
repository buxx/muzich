<?php

namespace Muzich\CoreBundle\Tests\Searcher;

use Muzich\CoreBundle\lib\UnitTest;
use Muzich\CoreBundle\Managers\TagManager;

class TagWriteTest extends UnitTest
{  
  
  public function testAddTag()
  {
    $bux = $this->getUser('bux');
    $paul = $this->getUser('paul');
    
    $tagManager = new TagManager();
    $tag_returned = $tagManager->addTag(
      $this->getDoctrine(), 
      'Xvlsd aoj 12', 
      $bux
    );
    
    $this->assertTrue(!is_null($tag_returned));
    
    // Simple ajout de tag en base
    $tag_database = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->findBy(array(
        'name'       => 'Xvlsd aoj 12',
        'tomoderate' => true,
        'privateids' => json_encode(array($bux->getId()))
      ))
    ;
    $this->assertTrue(!is_null($tag_database));
    
    // Si la demande est réitéré (bug js) pas de changements
    $tag_returned = $tagManager->addTag(
      $this->getDoctrine(), 
      'Xvlsd aoj 12', 
      $bux
    );
    
    $this->assertTrue(!is_null($tag_returned));
    
    // Simple ajout de tag en base
    $tag_database = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->findBy(array(
        'name'       => 'Xvlsd aoj 12',
        'tomoderate' => true,
        'privateids' => json_encode(array($bux->getId()))
      ))
    ;
    $this->assertTrue(!is_null($tag_database));
    
    // Si un autre user fait la demande sur ce même nom
    $tag_returned = $tagManager->addTag(
      $this->getDoctrine(), 
      'Xvlsd aoj 12', 
      $paul
    );
    
    $this->assertTrue(!is_null($tag_returned));
    
    // Simple ajout de tag en base
    $tag_database = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')
      ->findBy(array(
        'name'       => 'Xvlsd aoj 12',
        'tomoderate' => true,
        'privateids' => json_encode(array($bux->getId(), $paul->getId()))
      ))
    ;
    $this->assertTrue(!is_null($tag_database));
  }
  
  public function testModerateTag()
  {
    
  }
  
}