<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;
use Muzich\CoreBundle\Tests\lib\Security\Context as SecurityContextTest;
use Muzich\CoreBundle\Security\Context as SecurityContext;
use Muzich\CoreBundle\Tests\lib\Security\ContextTestCases;
use Muzich\CoreBundle\lib\Collection\ElementCollectionManager;

class PlaylistControllerTest extends FunctionalTest
{
  
  protected $security_context_test;
  protected $tests_cases;
  
  protected function init()
  {
    $this->client = self::createClient();
    $this->security_context_test = new SecurityContextTest($this->client, $this);
    $this->tests_cases = new ContextTestCases($this->client, $this);
  }
  
  public function testActionsSecurityRoles()
  {
    $this->init();
    $this->checkProhibedActionsForAnonymous();
    $this->checkAutorizedsActionsForAnonymous();
  }
  
  protected function checkProhibedActionsForAnonymous()
  {
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_PLAYLIST_ADD_ELEMENT, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      true
    );
    
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_PLAYLIST_UPDATE_ORDER, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      true
    );
    
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_PLAYLIST_REMOVE_ELEMENT, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      true
    );
    
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_PLAYLIST_CREATE, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      true
    );
    
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_PLAYLIST_COPY, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      true
    );
    
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_PLAYLIST_DELETE, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      true
    );
    
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_PLAYLIST_UNPICK, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      true
    );
    
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_PLAYLIST_PICK, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      true
    );
  }
  
  protected function checkAutorizedsActionsForAnonymous()
  {
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_PLAYLIST_SHOW, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      false
    );
    
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_PLAYLIST_DATA_AUTOPLAY, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      false
    );
    
    $this->security_context_test->testUserCantMakeActionStatus( 
      SecurityContext::ACTION_PLAYLIST_ADD_PROMPT, 
      SecurityContext::CONDITION_USER_NOT_CONNECTED,
      false
    );
  }
  
  public function testPlaylistsRead()
  {
    $this->init();
    $this->initReadContextData();
    
    $this->checkReadablePlaylistsForAnonymous();
    $this->checkReadablePlaylistsForConnected();
    $this->checkReadablePlaylistsForOwner();
  }
  
  protected function initReadContextData()
  {
    $this->users['bux'] = $this->findUserByUsername('bux');
    $this->playlists['bux_1_pub'] = $this->findOneBy('Playlist', 'Un peu de basses ?');
    $this->playlists['bux_2_priv'] = $this->findOneBy('Playlist', 'Ma playlist perso');
    $this->playlists['bob_pub'] = $this->findOneBy('Playlist', 'A travers l\'espace');
  }
  
  protected function checkReadablePlaylistsForAnonymous()
  {
    $this->checkReadPlaylists($this->users['bux'], array($this->playlists['bux_1_pub']));
  }
  
  protected function checkReadPlaylists($user, $playlists, $elements = null)
  {
    $this->tests_cases->playlistsShow($user->getSlug());
    $this->isResponseSuccess();
    $this->checkReadPlaylistsDom($playlists);
    
    foreach ($playlists as $playlist)
    {
      $this->tests_cases->playlistShow($user->getSlug(), $playlist->getId());
      $this->isResponseSuccess();
      $this->checkReadPlaylist($playlist, $elements);
    }
  }
  
  protected function checkReadPlaylistsDom($playlists)
  {
    $this->assertEquals(count($playlists), $this->crawler->filter('ul.playlists li.playlist')->count());
    foreach ($playlists as $playlist)
    {
      $this->exist('a[href="'.$this->generateUrl('playlist_datas_for_autoplay', array('playlist_id' => $playlist->getId())).'"]');
    }
  }
  
  protected function checkReadPlaylist($playlist, $elements = null)
  {
    $this->exist('h2:contains("'.$playlist->getName().'")');
    
    if ($elements !== null)
    {
      $this->checkPlaylistElements($playlist, $elements[$playlist->getId()]);
    }
  }
  
  protected function checkPlaylistElements($playlist, $elements)
  {
    $this->assertEquals(count($elements), $this->crawler->filter('li.playlist_element')->count());
    foreach ($elements as $element)
    {
      $this->exist('a[data-id="'.$element->getId().'"]');
    }
  }
  
  protected function checkReadablePlaylistsForConnected()
  {
    $this->connectUser('jean', 'toor');
    $this->checkReadPlaylists($this->users['bux'], array($this->playlists['bux_1_pub']));
    $this->disconnectUser();
  }
  
  protected function checkReadablePlaylistsForOwner()
  {
    $this->connectUser('bux', 'toor');
    $this->checkReadPlaylists($this->users['bux'], array(
      $this->playlists['bux_1_pub'],
      $this->playlists['bux_2_priv'],
      $this->playlists['bob_pub'],
    ));
  }
  
  public function testPlaylistCreation()
  {
    $this->init();
    $this->initCreateContextData();
    $this->connectUser('joelle', 'toor');
    
    $this->playlists['joelle_1'] = $this->createPlaylistWithElement($this->elements['babylon']);
    $this->checkReadPlaylists($this->users['joelle'], 
      array($this->playlists['joelle_1']),
      array($this->playlists['joelle_1']->getId() => array($this->elements['babylon']))
    );
  }
  
  protected function initCreateContextData()
  {
    $this->initReadContextData();
    $this->users['joelle'] = $this->findUserByUsername('joelle');
    $this->users['bob'] = $this->findUserByUsername('bob');
    $this->elements['babylon'] = $this->findOneBy('Element', 'Babylon Pression - Des Tasers et des Pauvres');
  }
  
  protected function createPlaylistWithElement($element)
  {
    $this->tests_cases->playlistCreate($element->getId(), 'Playlist de test');
    $this->assertTrue(!is_null($playlist = $this->findOneBy('Playlist', 'Playlist de test')));
    return $playlist;
  }
  
  public function testAutoplayDatas()
  {
    $this->init();
    $this->initReadContextData();
    
    $this->checkPublicPlaylist();
    $this->checkPrivatePlaylist();
    $this->connectUser('bob', 'toor');
    $this->checkPublicPlaylist();
    $this->checkPrivatePlaylist();
    $this->disconnectUser();
    $this->connectUser('bux', 'toor');
    $this->checkPublicPlaylist();
    $this->checkPrivatePlaylist(true);
  }
  
  protected function checkPublicPlaylist()
  {
    $response = $this->tests_cases->playlistAutoplay($this->playlists['bux_1_pub']->getId());
    $this->jsonResponseIsSuccess($response);
  }
  
  protected function checkPrivatePlaylist($success = false)
  {
    $response = $this->tests_cases->playlistAutoplay($this->playlists['bux_2_priv']->getId());
    
    if (!$success)
      $this->jsonResponseIsError($response);
    if ($success)
      $this->jsonResponseIsSuccess($response);
  }
  
  public function testPrompt()
  {
    $this->init();
    $this->initCreateContextData();
    
    $this->setCrawlerWithJsonResponseData($this->tests_cases->playlistPrompt($this->elements['babylon']->getId()));
    $this->checkPlaylistsInPrompt(array());
    
    $this->connectUser('bux', 'toor');
    
    $this->setCrawlerWithJsonResponseData($this->tests_cases->playlistPrompt($this->elements['babylon']->getId()));
    
    $this->checkPlaylistsInPrompt(array(
      $this->playlists['bux_1_pub'],
      $this->playlists['bux_2_priv'],
      $this->playlists['bob_pub'],
    ));
  }
  
  protected function checkPlaylistsInPrompt($playlists)
  {
    $this->assertEquals(count($playlists), $this->crawler->filter('ul.playlists_for_element li.playlist')->count());
    foreach ($playlists as $playlist)
    {
      $this->exist('a:contains("'.$playlist->getName().'")');
    }
  }
  
  public function testUpdateOrder()
  {
    $this->init();
    $this->initOrderContextData();
    $this->connectUser('bux', 'toor');
    
    $this->checkPlaylistOrder($this->playlists['bux_2_priv'], array(
      $this->elements['heretik'], $this->elements['fab']
    ));
    $this->updatePlaylistOrder($this->playlists['bux_2_priv'], array(
      $this->elements['fab'], $this->elements['heretik']
    ));
    $this->playlists['bux_2_priv'] = $this->findOneBy('Playlist', 'Ma playlist perso');
    $this->checkPlaylistOrder($this->playlists['bux_2_priv'], array(
      $this->elements['fab'], $this->elements['heretik']
    ));
  }
  
  protected function initOrderContextData()
  {
    $this->initReadContextData();
    $this->elements['heretik'] = $this->findOneBy('Element', 'Heretik System Popof - Resistance');
    $this->elements['fab'] = $this->findOneBy('Element', 'DJ FAB');
  }
  
  protected function checkPlaylistOrder($playlist, $elements)
  {
    $collection_manager = new ElementCollectionManager(array());
    
    foreach ($elements as $element)
    {
      $collection_manager->add($element);
    }
    
    $this->assertEquals($collection_manager->getContent(), $playlist->getelements());
  }
  
  protected function updatePlaylistOrder($playlist, $elements)
  {
    $response = $this->tests_cases->playlistUpdateOrder($playlist->getId(), $elements);
    $this->outputDebug();
    $this->jsonResponseIsSuccess($response);
  }
  
}