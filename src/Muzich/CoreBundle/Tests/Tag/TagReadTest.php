<?php

namespace Muzich\CoreBundle\Tests\Searcher;

use Muzich\CoreBundle\lib\UnitTest;
use Muzich\CoreBundle\Util\TagLike;

class TagReadTest extends UnitTest
{  
  private function getTagsNames($response = array())
  {
    if (count($response))
    {
      $tags_clean = array();
      if (count($response['tags']))
      {
        foreach ($response['tags'] as $tag)
        {
          $tags_clean[] = $tag['name'];
        }
      }
      return $tags_clean;
    }
  }
  private function getTagsNamesForQuery($response = array())
  {
    if (count($response))
    {
      $tags_clean = array();
      foreach ($response as $tag)
      {
        $tags_clean[] = $tag->getName();
      }
      return $tags_clean;
    }
  }
  
  
  /**
   * Simple test des tags retournés
   */
  public function testSearchTag()
  {
    $bux = $this->getUser('bux');
    
    $cresults = array(
      'Anarcho-punk', 'Dance-Punk', 'Horror punk', 'Pop-punk', 'Post-Punk',
        'Punk rock', 'Ska-punk', 'Skate punk', 'Synthpunk' 
    );
    
    $TagLike = new TagLike($this->getDoctrine());
    $response = $TagLike->getSimilarTags('punk', $bux->getId());
    
    $this->assertEquals(count($cresults), count($response['tags']));
    
    foreach ($response['tags'] as $tag)
    {
      $this->assertTrue(in_array($tag['name'], $cresults));
    }
  }
  
  /**
   * Test de l'ordre dans lequel les tags sont retourné
   */
  public function testSearchTagOrdered()
  {
    $bux = $this->getUser('bux');
    
    //////////////////////////
    $cresults = array(
      'Anarcho-punk', 'Dance-Punk', 'Horror punk', 'Pop-punk', 'Post-Punk',
        'Punk rock', 'Ska-punk', 'Skate punk', 'Synthpunk' 
    );
    
    $TagLike = new TagLike($this->getDoctrine());
    $tags = $this->getTagsNames($result = $TagLike->getSimilarTags('punk', $bux->getId()));
    
    $this->assertEquals($cresults, $tags);
    $this->assertEquals($result['same_found'], false);
    
    //////////////////////////
    $cresults = array(
      'Anarcho-punk', 'Dance-Punk', 'Horror punk', 'Pop-punk', 'Post-Punk',
        'Punk rock', 'Ska-punk', 'Skate punk', 'Synthpunk' 
    );
    
    $TagLike = new TagLike($this->getDoctrine());
    $tags = $this->getTagsNames($result = $TagLike->getSimilarTags('anarcho punk', $bux->getId()));
    
    $this->assertEquals($cresults, $tags);
    $this->assertEquals($result['same_found'], true);
    
    //////////////////////////
    $cresults = array(
      'Anarcho-punk', 'Dance-Punk', 'Horror punk', 'Pop-punk', 'Post-Punk',
        'Punk rock', 'Ska-punk', 'Skate punk', 'Synthpunk' 
    );
    
    $TagLike = new TagLike($this->getDoctrine());
    $tags = $this->getTagsNames($result = $TagLike->getSimilarTags('punk anarcho', $bux->getId()));
    
    $this->assertEquals($cresults, $tags);
    $this->assertEquals($result['same_found'], true);
    
    //////////////////////////
    $cresults = array(
      'Anarcho-punk', 'Dance-Punk', 'Horror punk', 'Pop-punk', 'Post-Punk',
        'Punk rock', 'Ska-punk', 'Skate punk', 'Synthpunk' 
    );
    
    $TagLike = new TagLike($this->getDoctrine());
    $tags = $this->getTagsNames($result = $TagLike->getSimilarTags('punk anar', $bux->getId()));
    
    $this->assertEquals($cresults, $tags);
    $this->assertEquals($result['same_found'], true);
    
    //////////////////////////
    $cresults = array(
      'Skate punk', 'Ska-punk', 'Ska', 'Anarcho-punk', 'Dance-Punk', 'Horror punk', 
        'Pop-punk', 'Post-Punk', 'Punk rock', 'Skacore', 'Ska-jazz',  'Synthpunk' 
    );
    
    $TagLike = new TagLike($this->getDoctrine());
    $tags = $this->getTagsNames($result = $TagLike->getSimilarTags('ska punk', $bux->getId()));
    
    $this->assertEquals($cresults, $tags);
    $this->assertEquals($result['same_found'], true);
    
    //////////////////////////
    $cresults = array(
      'Horror punk'
    );
    
    $TagLike = new TagLike($this->getDoctrine());
    $tags = $this->getTagsNames($result = $TagLike->getSimilarTags('horror', $bux->getId()));
    
    $this->assertEquals($cresults, $tags);
    $this->assertEquals($result['same_found'], false);
  }
  
  /**
   * Check de la récupération des tags de profil
   */
  public function testSearchTagProfile()
  {
    $bux = $this->getUser('bux');
    $joelle = $this->getUser('joelle');
    $paul = $this->getUser('paul');
    
    ////////////////////////////////
    $rtags = array('Electro', 'Hardcore', 'Hardtek', 'Metal');
    
    $tags = $this->getTagsNamesForQuery($this->getDoctrine()->getRepository('MuzichCoreBundle:User')
      ->getElementsTags($bux->getId(), $bux->getId())      
    );
    
    $this->assertEquals($rtags, $tags);
    
    ////////////////////////////////
    $rtags = array('Beatbox', 'Chanteuse', 'Dubstep', 'Medieval');
    
    $tags = $this->getTagsNamesForQuery($this->getDoctrine()->getRepository('MuzichCoreBundle:User')
      ->getElementsTags($joelle->getId(), $joelle->getId())      
    );
    
    $this->assertEquals($rtags, $tags);
    
    ////////////////////////////////
    $rtags = array('Hardtek', 'Psytrance', 'Tribe');
    
    $tags = $this->getTagsNamesForQuery($this->getDoctrine()->getRepository('MuzichCoreBundle:User')
      ->getElementsTags($paul->getId(), $paul->getId())      
    );
    
    $this->assertEquals($rtags, $tags);
  }
  
  
  public function testSearchTagFavorites()
  {
    $bux = $this->getUser('bux');
    $joelle = $this->getUser('joelle');
    $paul = $this->getUser('paul');
    
    ////////////////////////////////
    $rtags = array('Hardtek');
    
    $tags = $this->getTagsNamesForQuery($this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->getTags($bux->getId(), $bux->getId())          
    );
    
    $this->assertEquals($rtags, $tags);
    
    ////////////////////////////////
    $rtags = null;
    
    $tags = $this->getTagsNamesForQuery($this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->getTags($joelle->getId(), $joelle->getId())           
    );
    
    $this->assertEquals($rtags, $tags);
    
    ////////////////////////////////
    $rtags = array('Hardtek', 'Tribe');
    
    $tags = $this->getTagsNamesForQuery($this->getDoctrine()->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->getTags($paul->getId(), $paul->getId())
    );
    
    $this->assertEquals($rtags, $tags);
  }
  
}