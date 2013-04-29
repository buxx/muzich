<?php

namespace Muzich\CoreBundle\Tests\User;

use Muzich\CoreBundle\lib\UnitTest;
use Muzich\CoreBundle\Entity\UsersTagsFavorites;

class UserTest extends UnitTest
{  
  
  public function testTagsFavoritesQuick()
  {
    // On vérifie en premier lieu que les donnée en base corresponde bien a 
    // ce que l'on veut avoir en fonction des fixtures (c'est une donnée calculé
    // lors de la manipulation de UsersTagsFavorite
    
    $tribe   = $this->getTag('Tribe');
    $hardtek   = $this->getTag('Hardtek');
    $electro = $this->getTag('Electro');
    $metal   = $this->getTag('Metal');
    $metalco = $this->getTag('Metalcore');
    $minimal = $this->getTag('Minimal');
    $jungle  = $this->getTag('Jungle');
    $melanco = $this->getTag('Melancolique');
    $mellow  = $this->getTag('Mellow');
    $melodiq = $this->getTag('Melodique');
    
    
    $bux = $this->getUser('bux');
    $original_bux_tags_quick = $bux->getTagsFavoritesQuick();
    if ((in_array('Nouveau 1', $bux->getTagsFavoritesQuick())))
    {
      // hack au cas ou on a éxécuté des tests avant qui on tinjecté le tag 
      // Nouveau 1
      $tags = $bux->getTagsFavoritesQuick();
      $this->assertEquals(array(
        $tribe->getId() => 'Tribe',
        $electro->getId() => 'Electro',
        $metal->getId() => 'Metal',
        $minimal->getId() => 'Minimal',
        $jungle->getId() => 'Jungle',
        $hardtek->getId()  => 'Hardtek',
        array_search('Nouveau 1', $bux->getTagsFavoritesQuick())  => 'Nouveau 1'
      ), array_unique($bux->getTagsFavoritesQuick()));
    }
    else
    {
      $bux = $this->getUser('bux');
      $this->assertEquals(array(
        $tribe->getId() => 'Tribe',
        $electro->getId() => 'Electro',
        $metal->getId() => 'Metal',
        $minimal->getId() => 'Minimal',
        $jungle->getId() => 'Jungle',
        $hardtek->getId()  => 'Hardtek'
      ), $bux->getTagsFavoritesQuick());
    }
    
    
    
    $jean = $this->getUser('jean');
    $this->assertEquals(array(
      $melanco->getId() => 'Melancolique',
      $mellow->getId() => 'Mellow',
      $melodiq->getId() => 'Melodique',
      $metal->getId() => 'Metal',
      $metalco->getId() => 'Metalcore',
      $minimal->getId() => 'Minimal'
    ), $jean->getTagsFavoritesQuick());
    
    /*
     * Si on effectue des modifs dans les tags favoris
     */
    
    $bux_melodique = new UsersTagsFavorites();
    $bux_melodique->setUser($bux);
    $bux_melodique->setTag($melodiq);
    $this->persist($bux_melodique);
    $this->flush();
    
    $bux = $this->getUser('bux');
    if ((in_array('Nouveau 1', $bux->getTagsFavoritesQuick())))
    {
      // hack au cas ou on a éxécuté des tests avant qui on tinjecté le tag 
      // Nouveau 1
      $tags = $bux->getTagsFavoritesQuick();
      $this->assertEquals(array(
        $tribe->getId() => 'Tribe',
        $electro->getId() => 'Electro',
        $metal->getId() => 'Metal',
        $minimal->getId() => 'Minimal',
        $jungle->getId() => 'Jungle',
        $hardtek->getId()  => 'Hardtek',
        array_search('Nouveau 1', $bux->getTagsFavoritesQuick())  => 'Nouveau 1',
        $melodiq->getId() => 'Melodique'
      ), $bux->getTagsFavoritesQuick());
    }
    else
    {
      $bux = $this->getUser('bux');
      $this->assertEquals(array(
        $tribe->getId() => 'Tribe',
        $electro->getId() => 'Electro',
        $metal->getId() => 'Metal',
        $minimal->getId() => 'Minimal',
        $jungle->getId() => 'Jungle',
        $hardtek->getId()  => 'Hardtek',
        $melodiq->getId() => 'Melodique'
      ), $bux->getTagsFavoritesQuick());
    }
    
    $this->getDoctrine()->getEntityManager()->remove($bux_melodique);
    $this->flush();
    
    $bux = $this->getUser('bux');
    $original_bux_tags_quick = $bux->getTagsFavoritesQuick();
    if ((in_array('Nouveau 1', $bux->getTagsFavoritesQuick())))
    {
      // hack au cas ou on a éxécuté des tests avant qui on tinjecté le tag 
      // Nouveau 1
      $tags = $bux->getTagsFavoritesQuick();
      $this->assertEquals(array(
        $tribe->getId() => 'Tribe',
        $electro->getId() => 'Electro',
        $metal->getId() => 'Metal',
        $minimal->getId() => 'Minimal',
        $jungle->getId() => 'Jungle',
        $hardtek->getId()  => 'Hardtek',
        array_search('Nouveau 1', $bux->getTagsFavoritesQuick())  => 'Nouveau 1'
      ), $bux->getTagsFavoritesQuick());
    }
    else
    {
      $bux = $this->getUser('bux');
      $this->assertEquals(array(
        $tribe->getId() => 'Tribe',
        $electro->getId() => 'Electro',
        $metal->getId() => 'Metal',
        $minimal->getId() => 'Minimal',
        $jungle->getId() => 'Jungle',
        $hardtek->getId()  => 'Hardtek'
      ), $bux->getTagsFavoritesQuick());
    }
    
    // Persistance des données de test arf, du coup on remet les tags d'origine
    // a la fin du test au cas ou on le rééxecute
    $bux->setTagsFavoritesQuick($original_bux_tags_quick);
    
  }
  
}