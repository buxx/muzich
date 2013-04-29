<?php

namespace Muzich\CoreBundle\Tests\Searcher;

use Muzich\CoreBundle\lib\UnitTest;
use Muzich\CoreBundle\Util\TagLike;
use Muzich\CoreBundle\lib\Tag as TagLib;

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
    
    $TagLike = new TagLike($this->getDoctrine()->getEntityManager());
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
    
    $TagLike = new TagLike($this->getDoctrine()->getEntityManager());
    $tags = $this->getTagsNames($result = $TagLike->getSimilarTags('punk', $bux->getId()));
    
    $this->assertEquals($cresults, $tags);
    $this->assertEquals($result['same_found'], false);
    
    //////////////////////////
    $cresults = array(
      'Anarcho-punk', 'Dance-Punk', 'Horror punk', 'Pop-punk', 'Post-Punk',
        'Punk rock', 'Ska-punk', 'Skate punk', 'Synthpunk' 
    );
    
    $TagLike = new TagLike($this->getDoctrine()->getEntityManager());
    $tags = $this->getTagsNames($result = $TagLike->getSimilarTags('anarcho punk', $bux->getId()));
    
    $this->assertEquals($cresults, $tags);
    $this->assertEquals($result['same_found'], true);
    
    //////////////////////////
    $cresults = array(
      'Anarcho-punk', 'Dance-Punk', 'Horror punk', 'Pop-punk', 'Post-Punk',
        'Punk rock', 'Ska-punk', 'Skate punk', 'Synthpunk' 
    );
    
    $TagLike = new TagLike($this->getDoctrine()->getEntityManager());
    $tags = $this->getTagsNames($result = $TagLike->getSimilarTags('punk anarcho', $bux->getId()));
    
    $this->assertEquals($cresults, $tags);
    $this->assertEquals($result['same_found'], true);
    
    //////////////////////////
    $cresults = array(
      'Anarcho-punk', 'Dance-Punk', 'Horror punk', 'Pop-punk', 'Post-Punk',
        'Punk rock', 'Ska-punk', 'Skate punk', 'Synthpunk' 
    );
    
    $TagLike = new TagLike($this->getDoctrine()->getEntityManager());
    $tags = $this->getTagsNames($result = $TagLike->getSimilarTags('punk anar', $bux->getId()));
    
    $this->assertEquals($cresults, $tags);
    $this->assertEquals($result['same_found'], true);
    
    //////////////////////////
    $cresults = array(
      'Skate punk', 'Ska-punk', 'Ska', 'Anarcho-punk', 'Dance-Punk', 'Horror punk', 
        'Pop-punk', 'Post-Punk', 'Punk rock', 'Ska-jazz', 'Skacore',  'Synthpunk' 
    );
    
    $TagLike = new TagLike($this->getDoctrine()->getEntityManager());
    $tags = $this->getTagsNames($result = $TagLike->getSimilarTags('ska punk', $bux->getId()));
    
    $this->assertEquals($cresults, $tags);
    $this->assertEquals($result['same_found'], true);
    
    //////////////////////////
    $cresults = array(
      'Horror punk'
    );
    
    $TagLike = new TagLike($this->getDoctrine()->getEntityManager());
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
  
  /*
   * Test des opération de création d'une liste ordonné de tags en fonction 
   * d'une liste d'élèments
   */
  public function testTagOrder()
  {
    $bux = $this->getUser('bux');
    $joelle = $this->getUser('joelle');
    $tag_lib = new TagLib();
    
    $hardtek  = $this->findOneBy('Tag', array('name' => 'Hardtek'));
    $metal    = $this->findOneBy('Tag', array('name' => 'Metal'));
    $electro  = $this->findOneBy('Tag', array('name' => 'Electro'));
    $hardcore = $this->findOneBy('Tag', array('name' => 'Hardcore'));
    $chanteuse = $this->findOneBy('Tag', array('name' => 'Chanteuse'));
    $dubstep  = $this->findOneBy('Tag', array('name' => 'Dubstep'));
    $medieval = $this->findOneBy('Tag', array('name' => 'Medieval'));
    $beatbox = $this->findOneBy('Tag', array('name' => 'Beatbox'));
    
    /*
     * Test de la récuparéation de l'ordre des tags
     */
    
    $search = new \Muzich\CoreBundle\Searcher\ElementSearcher();
    $search->init(array(
      'user_id'  => $bux->getId()
    ));
    $elements = $search->getElements($this->getDoctrine(), $bux->getId());
    
    $tag_reference = $tag_lib->getOrderedTagsWithElements($elements);
    
    $this->assertEquals(array(
      $hardtek->getId(),
      $metal->getId(),
      $electro->getId(),
      $hardcore->getId()
    ), $tag_reference);
    
    ////////////
    
    
    $search = new \Muzich\CoreBundle\Searcher\ElementSearcher();
    $search->init(array(
      'user_id'  => $joelle->getId()
    ));
    $elements = $search->getElements($this->getDoctrine(), $bux->getId());
    
    $tag_reference = $tag_lib->getOrderedTagsWithElements($elements);
    
    $this->assertEquals(array(
      $chanteuse->getId(),
      $dubstep->getId(),
      $medieval->getId(),
      $beatbox->getId()
    ), $tag_reference);
    
    /*
     * Test du trie de tags en fonction d'une liste référente
     */
    
    // Tag non ordonés
    $tags_disordered = array(
      $medieval,
      $beatbox,
      $hardcore,
      $dubstep,
      $chanteuse
    );
    
    // On ordonne tout ça avec la référence calculé juste avant
    $tags_ordered = $tag_lib->sortTagWithOrderedReference($tags_disordered, $tag_reference);
    
    $tags_ordered_ids = array();
    foreach ($tags_ordered as $tag_ordered)
    {
      $tags_ordered_ids[] = $tag_ordered->getId();
    }
    
    $this->assertEquals(
      array(
        $chanteuse->getId(),
        $dubstep->getId(),
        $medieval->getId(),
        $beatbox->getId(),
        $hardcore->getId()
      ), $tags_ordered_ids
    );
  }
  
}