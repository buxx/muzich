<?php

namespace Muzich\CoreBundle\Tests\Utils;

use Muzich\CoreBundle\lib\Collection\TagCollectionManager;
use Muzich\CoreBundle\Entity\Tag;

class TagTest extends Tag
{
  public function __construct($id, $name)
  {
    $this->id = $id;
    $this->name = $name;
  }
}

class CollectionManagerTest extends \PHPUnit_Framework_TestCase
{
  
  public function testTagCollectionManager()
  {
    $tags_collection_manager = new TagCollectionManager(array());
    $this->assertEquals('[]', json_encode($tags_collection_manager->getContent(), true));
    
    $tag_pop = new TagTest(1, 'Pop');
    $tags_collection_manager->add($tag_pop);
    $this->assertEquals('[{"id":"1","name":"Pop"}]', json_encode($tags_collection_manager->getContent(), true));
    
    $tag_rock = new TagTest(2, 'Rock');
    $tags_collection_manager->add($tag_rock);
    $this->assertEquals('[{"id":"1","name":"Pop"},{"id":"2","name":"Rock"}]', json_encode($tags_collection_manager->getContent(), true));
    
    $this->assertTrue($tags_collection_manager->have($tag_rock));
    
    $tags_collection_manager->add($tag_rock);
    $this->assertEquals('[{"id":"1","name":"Pop"},{"id":"2","name":"Rock"}]', json_encode($tags_collection_manager->getContent(), true));
    
    $tag_metal = new TagTest(3, 'Metal');
    $tags_collection_manager->add($tag_metal);
    $this->assertEquals('[{"id":"1","name":"Pop"},{"id":"2","name":"Rock"},{"id":"3","name":"Metal"}]', json_encode($tags_collection_manager->getContent(), true));
    
    $this->assertEquals(array('1', '2', '3'), $tags_collection_manager->getAttributes(TagCollectionManager::ATTRIBUTE_ID));
    $this->assertEquals(array('Pop', 'Rock', 'Metal'), $tags_collection_manager->getAttributes(TagCollectionManager::ATTRIBUTE_NAME));
    
    $tags_collection_manager->remove($tag_pop);
    $this->assertEquals('[{"id":"2","name":"Rock"},{"id":"3","name":"Metal"}]', json_encode($tags_collection_manager->getContent(), true));
  }
  
}