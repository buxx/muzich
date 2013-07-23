<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;
use Muzich\CoreBundle\Tests\lib\Security\ContextTestCases;

class ScoreTest extends FunctionalTest
{
  
  protected $scores_reference = array(
    'User' => array(
      "admin"  => 0,
      "bux"    => 22,
      "jean"   => 18,
      "paul"   => 14,
      "bob"    => 1,
      "joelle" => 9
    ),
    'Element' => array(
      "Heretik System Popof - Resistance" => 5, 
      "Dtc che passdrop"                  => 0, 
      "Antropod - Polakatek"              => 0, 
      "KoinkOin - H5N1"                   => 0, 
      "DJ FAB"                            => 6, 
      "Dj antoine"                        => 1, 
      "Acrotek Hardtek G01"               => 1, 
      "All Is Full Of Pain"               => 0, 
      "RE-FUCK (ReVeRB_FBC) mix."         => 1, 
      "CardioT3K - Juggernaut Trap"       => 1, 
      "DUDELDRUM"                         => 1, 
      "Infected Mushroom - Psycho"        => 2, 
      "Infected mushroom - Muse Breaks"   => 1, 
      "Cents Pas - Joëlle"                => 2, 
      "Cents Pas - Joëlle (bis)"          => 2, 
      "UKF Dubstep Mix - August "         => 1, 
      "Dubstep Beatbox"                   => 3, 
      "SOULFLY - Prophecy"                => 1, 
      "AZYD AZYLUM Live au Café Provisoire"             => 3, 
      "Babylon Pression - Des Tasers et des Pauvres"    => 1, 
      "Ed Cox - La fanfare des teuffeurs (Hardcordian)" => 2, 
    )
  );
  
  protected function init()
  {
    $this->client = self::createClient();
    $this->tests_cases = new ContextTestCases($this->client, $this);
  }
  
  protected function checkScores()
  {
    foreach ($this->scores_reference as $object_type => $objects_scores)
    {
      foreach ($objects_scores as $name => $score)
      {
        $this->assertEquals($score, $this->getEntityScore($object_type, $name));
      }
    }
  }
  
  protected function getEntityScore($object_type, $name)
  {
    if ($object_type == 'Element')
    {
      return $this->findOneBy('Element', $name)->getPoints();
    }
    if ($object_type == 'User')
    {
      return $this->findUserByUsername($name)->getReputation();
    }
  }
  
  public function testScores()
  {
    $this->init(); 
    $this->checkScores();
    
    $this->checkAddVoteGood();
    $this->checkRemoveVoteGood();
    
    $this->checkAddToFavorites();
    $this->checkRemoveFromFavorites();
    
    $this->checkWithPlaylists();
    $this->checkTagsPropositions();
    $this->checkFollowing();
    $this->checkDeleteElement();
  }
  
  protected function checkAddVoteGood()
  {
    $this->connectUser('paul');
    
    $response = $this->tests_cases->elementAddGoodPoint(
      $this->findOneBy('Element', "Cents Pas - Joëlle (bis)"),
      $this->findUserByUsername('paul'));
    $this->stringResponseIsSuccess($response);
    
    $this->scores_reference['User']['joelle'] += 1;
    $this->scores_reference['Element']['Cents Pas - Joëlle (bis)'] += 1;
    $this->checkScores();
    
    $this->disconnectUser();
  }
  
  protected function stringResponseIsSuccess($response_string)
  {
    $response_array = json_decode($response_string, true);
    $this->assertEquals('success', $response_array['status']);
  }
  
  protected function checkRemoveVoteGood()
  {
    $this->connectUser('paul');
    
    $response = $this->tests_cases->elementRemoveGoodPoint(
      $this->findOneBy('Element', "Cents Pas - Joëlle (bis)"),
      $this->findUserByUsername('paul'));
    $this->stringResponseIsSuccess($response);
    
    $this->scores_reference['User']['joelle'] -= 1;
    $this->scores_reference['Element']['Cents Pas - Joëlle (bis)'] -= 1;
    $this->checkScores();
    
    $this->disconnectUser();
  }
  
  protected function checkAddToFavorites()
  {
    $this->connectUser('paul');
    
    $response = $this->tests_cases->elementAddToFavorites(
      $this->findOneBy('Element', "Cents Pas - Joëlle (bis)"),
      $this->findUserByUsername('paul'));
    $this->stringResponseIsSuccess($response);
    
    $this->scores_reference['User']['joelle'] += 5;
    $this->scores_reference['Element']['Cents Pas - Joëlle (bis)'] += 5;
    $this->checkScores();
    
    $this->disconnectUser();
  }
  
  protected function checkRemoveFromFavorites()
  {
    $this->connectUser('paul');
    
    $response = $this->tests_cases->elementRemoveFromFavorites(
      $this->findOneBy('Element', "Cents Pas - Joëlle (bis)"),
      $this->findUserByUsername('paul'));
    $this->stringResponseIsSuccess($response);
    
    $this->scores_reference['User']['joelle'] -= 5;
    $this->scores_reference['Element']['Cents Pas - Joëlle (bis)'] -= 5;
    $this->checkScores();
    
    $this->disconnectUser();
  }
  
  protected function checkWithPlaylists()
  {
    $this->connectUser('joelle');
    
    $response = $this->tests_cases->playlistCreate(
      $this->findOneBy('Element', "Babylon Pression - Des Tasers et des Pauvres")->getId(),
      "JoellePlaylist:)");
    $this->stringResponseIsSuccess($response);
    
    $this->scores_reference['User']['bux'] += 1;
    $this->scores_reference['Element']['Babylon Pression - Des Tasers et des Pauvres'] += 1;
    $this->checkScores();
    
    $response = $this->tests_cases->playlistCreate(
      $this->findOneBy('Element', "Babylon Pression - Des Tasers et des Pauvres")->getId(),
      "EncoreUnePlaylistAJoelle)");
    $this->stringResponseIsSuccess($response);
    $this->checkScores();
    
    $response = $this->tests_cases->playlistAddElement(
      $this->findOneBy('Playlist', "JoellePlaylist:)")->getId(),
      $this->findOneBy('Element', "Babylon Pression - Des Tasers et des Pauvres")->getId());
    $this->stringResponseIsSuccess($response);
    $this->checkScores();
    
    $response = $this->tests_cases->playlistAddElement(
      $this->findOneBy('Playlist', "JoellePlaylist:)")->getId(),
      $this->findOneBy('Element', "Ed Cox - La fanfare des teuffeurs (Hardcordian)")->getId());
    $this->stringResponseIsSuccess($response);
    
    $this->scores_reference['User']['bux'] += 1;
    $this->scores_reference['Element']['Ed Cox - La fanfare des teuffeurs (Hardcordian)'] += 1;
    $this->checkScores();
    
    $this->disconnectUser();
    $this->connectUser('paul');
    
    $response = $this->tests_cases->playlistCreate(
      $this->findOneBy('Element', "Babylon Pression - Des Tasers et des Pauvres")->getId(),
      "TrololoPaul");
    $this->stringResponseIsSuccess($response);
    
    $this->scores_reference['User']['bux'] += 1;
    $this->scores_reference['Element']['Babylon Pression - Des Tasers et des Pauvres'] += 1;
    $this->checkScores();
    
    $response = $this->tests_cases->playlistRemoveElement(
      $this->findOneBy('Playlist', "TrololoPaul")->getId(),
      0);
    $this->stringResponseIsSuccess($response);
    
    $this->scores_reference['User']['bux'] -= 1;
    $this->scores_reference['Element']['Babylon Pression - Des Tasers et des Pauvres'] -= 1;
    $this->checkScores();
    
    
    $this->disconnectUser();
  }
  
  protected function checkTagsPropositions()
  {
    $this->connectUser('joelle');
    
    $response = $this->tests_cases->elementProposeTags(
      $this->findOneBy('Element', "Ed Cox - La fanfare des teuffeurs (Hardcordian)"),
      $this->findUserByUsername('joelle'),
      array($this->findOneBy('Tag', "Hardtek")->getId()));
    $this->stringResponseIsSuccess($response);
    $this->checkScores();
    
    $this->disconnectUser();
    $this->connectUser('bux');
            
    $response = $this->tests_cases->elementAcceptTagsProposition(
      $this->findUserByUsername('bux'),
      $this->getLastTagsProposition($this->findOneBy('Element', "Ed Cox - La fanfare des teuffeurs (Hardcordian)")));
    $this->stringResponseIsSuccess($response);
    
    $this->scores_reference['User']['joelle'] += 12;
    $this->checkScores();
    
    $this->disconnectUser();
  }
  
  protected function checkFollowing()
  {
    $this->connectUser('joelle');
    
    $response = $this->tests_cases->followUser(
      $this->findUserByUsername('paul')->getId());
    $this->stringResponseIsSuccess($response);
    
    $this->scores_reference['User']['paul'] += 10;
    $this->checkScores();
    
    $response = $this->tests_cases->followUser(
      $this->findUserByUsername('paul')->getId());
    $this->stringResponseIsSuccess($response);
    
    $this->scores_reference['User']['paul'] -= 10;
    $this->checkScores();
    
    $this->disconnectUser();
  }
  
  protected function checkDeleteElement()
  {
    $this->connectUser('paul');
    
    $response = $this->tests_cases->elementAddGoodPoint(
      $this->findOneBy('Element', "KoinkOin - H5N1"),
      $this->findUserByUsername('paul'));
    $this->stringResponseIsSuccess($response);
    
    $this->scores_reference['User']['bux'] += 1;
    $this->scores_reference['Element']["KoinkOin - H5N1"] += 1;
    $this->checkScores();
    
    $this->disconnectUser();
    $this->connectUser('joelle');
    
    $response = $this->tests_cases->elementAddGoodPoint(
      $this->findOneBy('Element', "KoinkOin - H5N1"),
      $this->findUserByUsername('joelle'));
    $this->stringResponseIsSuccess($response);
    
    $this->scores_reference['User']['bux'] += 1;
    $this->scores_reference['Element']["KoinkOin - H5N1"] += 1;
    $this->checkScores();
    
    $this->disconnectUser();
    
    $this->connectUser('paul');
    
    $response = $this->tests_cases->elementAddToFavorites(
      $this->findOneBy('Element', "KoinkOin - H5N1"),
      $this->findUserByUsername('paul'));
    $this->stringResponseIsSuccess($response);
    
    $this->scores_reference['User']['bux'] += 5;
    $this->scores_reference['Element']["KoinkOin - H5N1"] += 5;
    $this->checkScores();
    
    $this->disconnectUser();
    
    $this->connectUser('jean');
    
    $response = $this->tests_cases->playlistCreate(
      $this->findOneBy('Element', "KoinkOin - H5N1")->getId(),
      "4564564752478942892489)");
    $this->stringResponseIsSuccess($response);
    
    $this->scores_reference['User']['bux'] += 1;
    $this->scores_reference['Element']['KoinkOin - H5N1'] += 1;
    $this->checkScores();
    
    $this->disconnectUser();
    $this->connectUser('bux');
    
    $response = $this->tests_cases->elementDelete(
      $this->findOneBy('Element', "KoinkOin - H5N1"),
      $this->findUserByUsername('bux'));
    $this->stringResponseIsSuccess($response);
    
    $this->scores_reference['User']['bux'] -= 8;
    unset($this->scores_reference['Element']['KoinkOin - H5N1']);
    $this->checkScores();
    
    $this->disconnectUser();
  }
  
}