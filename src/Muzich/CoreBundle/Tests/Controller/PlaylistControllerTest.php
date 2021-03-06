<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;
use Muzich\CoreBundle\Tests\lib\Security\Context as SecurityContextTest;
use Muzich\CoreBundle\Security\Context as SecurityContext;
use Muzich\CoreBundle\Tests\lib\Security\ContextTestCases;
use Muzich\CoreBundle\lib\Collection\ElementCollectionManager;
use Muzich\CoreBundle\Entity\Playlist;

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
    $this->exist('h1:contains("'.$playlist->getName().'")');
    
    if ($elements !== null)
    {
      $this->checkPlaylistElements($playlist, $elements[$playlist->getId()]);
    }
  }
  
  protected function checkPlaylistElements(Playlist $playlist, $elements, $exists = true, $check_count = true)
  {
    $this->goToPage($this->generateUrl('playlist', array(
      'playlist_id' => $playlist->getId(),
      'user_slug'   => $playlist->getOwner()->getSlug()
    )));
    
    if ($check_count)
      $this->assertEquals(count($elements), $this->crawler->filter('li.playlist_element')->count());
    
    foreach ($elements as $element)
    {
      if ($exists)
        $this->exist('a[data-id="'.$element->getId().'"]');
      if (!$exists)
        $this->notExist('a[data-id="'.$element->getId().'"]');
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
    $this->jsonResponseIsSuccess($response);
  }
  
  public function testAddAndRemoveElement()
  {
    $this->init();
    $this->initAddRemoveContextData();
    $this->connectUser('bux', 'toor');
    
    $this->tests_cases->playlistShow($this->users['bux']->getSlug(), $this->playlists['bux_2_priv']->getId());
    $this->checkReadPlaylist($this->playlists['bux_2_priv'],
      array($this->playlists['bux_2_priv']->getId() => array(
        $this->elements['heretik'],
        $this->elements['fab']
      ))
    );
    
    $this->addElementToPlaylist($this->playlists['bux_2_priv'], $this->elements['azyd']);
    
    $this->tests_cases->playlistShow($this->users['bux']->getSlug(), $this->playlists['bux_2_priv']->getId());
    $this->checkReadPlaylist($this->playlists['bux_2_priv'],
      array($this->playlists['bux_2_priv']->getId() => array(
        $this->elements['heretik'],
        $this->elements['fab'],
        $this->elements['azyd']
      ))
    );
    
    $this->removeElementFromPlaylist($this->playlists['bux_2_priv'], $this->elements['fab']);
    
    $this->tests_cases->playlistShow($this->users['bux']->getSlug(), $this->playlists['bux_2_priv']->getId());
    $this->checkReadPlaylist($this->playlists['bux_2_priv'],
      array($this->playlists['bux_2_priv']->getId() => array(
        $this->elements['heretik'],
        $this->elements['azyd']
      ))
    );
  }
  
  protected function initAddRemoveContextData()
  {
    $this->initOrderContextData();
    $this->elements['heretik'] = $this->findOneBy('Element', 'Heretik System Popof - Resistance');
    $this->elements['fab'] = $this->findOneBy('Element', 'DJ FAB');
    $this->elements['azyd'] = $this->findOneBy('Element', 'AZYD AZYLUM Live au Café Provisoire'); 
  }
  
  protected function addElementToPlaylist($playlist, $element)
  {
    $this->tests_cases->playlistAddElement($playlist->getId(), $element->getId());
  }
  
  protected function removeElementFromPlaylist($playlist, $element)
  {
    $index = $playlist->getElementIndex($element);
    $this->tests_cases->playlistRemoveElement($playlist->getId(), $index);
  }
  
  public function testCopyWhenAddingElementToPickedPlaylist()
  {
    $this->init();
    $this->initCopysContextData();
    $this->connectUser('bux', 'toor');
    
    $this->checkPlaylistPickedBy($this->playlists['bob_pub'], $this->users['bux']);
    $this->addElementAndCopyPlaylist($this->playlists['bob_pub'], $this->elements['azyd']);
    $this->playlists['bux_bob_pub'] = $this->findOneBy('Playlist', array(
      'name' => 'A travers l\'espace',
      'owner' => $this->users['bux']->getId()
    ));
    $this->assertTrue(!is_null($this->playlists['bux_bob_pub']));
    $this->checkPlaylistOwnedBy($this->playlists['bux_bob_pub'], $this->users['bux']);
  }
  
  protected function initCopysContextData()
  {
    $this->initReadContextData();
    $this->elements['azyd'] = $this->findOneBy('Element', 'AZYD AZYLUM Live au Café Provisoire'); 
  }
  
  protected function checkPlaylistPickedBy($playlist, $user)
  {
    $this->assertTrue($playlist->havePickerUser($user));
  }
  
  protected function addElementAndCopyPlaylist($playlist, $element)
  {
    $response = $this->tests_cases->playlistAddElementAndCopy($playlist->getId(), $element->getId());
    $this->jsonResponseIsSuccess($response);
  }
  
  protected function checkPlaylistOwnedBy($playlist, $user)
  {
    $this->assertEquals($playlist->getOwner()->getUsername(), $user->getUsername());
  }
  
  protected function checkPlaylistNotOwnedBy($playlist, $user)
  {
    $this->assertNotEquals($playlist->getOwner()->getId(), $user->getId());
  }
  
  public function testCopyWhenPickedPlaylistDeleted()
  {
    $this->init();
    $this->initCopysContextData();
    $this->connectUser('bob', 'toor');
    
    $this->checkPlaylistPickedBy($this->playlists['bob_pub'], $this->users['bux']);
    $this->checkPlaylistNotOwnedBy($this->playlists['bob_pub'], $this->users['bux']);
    $this->deletePlaylist($this->playlists['bob_pub']);
    $this->playlists['bux_bob_pub'] = $this->findOneBy('Playlist', array('name' => 'A travers l\'espace'));
    $this->assertTrue(!is_null($this->playlists['bux_bob_pub']));
    $this->checkPlaylistOwnedBy($this->playlists['bux_bob_pub'], $this->users['bux']);
  }
  
  public function testCopyWhenPickedPlaylistPrivatized()
  {
    $this->init();
    $this->initCopysContextData();
    $this->connectUser('bob', 'toor');
    
    $this->checkPlaylistPickedBy($this->playlists['bob_pub'], $this->users['bux']);
    $this->checkPlaylistNotOwnedBy($this->playlists['bob_pub'], $this->users['bux']);
    
    $this->playlists['bob_pub']->setPublic(false);
    $this->updatePlaylist($this->playlists['bob_pub']);
    $this->playlists['bux_bob_pub'] = $this->findOneBy('Playlist', array(
      'name' => 'A travers l\'espace',
      'owner' => $this->users['bux']->getId()
    ));
    $this->assertTrue(!is_null($this->playlists['bux_bob_pub']));
    $this->assertEquals(false, $this->playlists['bux_bob_pub']->isPublic());
    $this->checkPlaylistOwnedBy($this->playlists['bux_bob_pub'], $this->users['bux']);
  }
  
  protected function deletePlaylist($playlist)
  {
    $this->tests_cases->playlistDelete($playlist->getId());
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
  }
  
  public function testPickAndUnpick()
  {
    $this->init();
    $this->initReadContextData();
    $this->users['jean'] = $this->findUserByUsername('jean');
    $this->connectUser('jean', 'toor');
    
    $this->checkReadPlaylists($this->users['jean'], array());
    $this->pickPlaylist($this->playlists['bob_pub']);
    $this->checkReadPlaylists($this->users['jean'], array($this->playlists['bob_pub']));
    $this->unPickPlaylist($this->playlists['bob_pub']);
    $this->checkReadPlaylists($this->users['jean'], array());
  }
  
  protected function pickPlaylist($playlist)
  {
    $response = $this->tests_cases->playlistPick($playlist->getId());
    $this->jsonResponseIsSuccess($response);
  }
  
  protected function unPickPlaylist($playlist)
  {
    $this->tests_cases->playlistUnPick($playlist->getId());
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
  }
  
  public function testUpdate()
  {
    $this->init();
    $this->initReadContextData();
    $this->connectUser('bux', 'toor');
    $this->goToPage($this->generateUrl('playlist', array('user_slug' => $this->users['bux']->getSlug(), 'playlist_id' => $this->playlists['bux_1_pub']->getId())));
    $this->checkReadPlaylist($this->playlists['bux_1_pub']);
    $new_name = $this->playlists['bux_1_pub']->getName().' dans tes oreilles ?!';
    $this->playlists['bux_1_pub']->setName($new_name);
    $this->updatePlaylist($this->playlists['bux_1_pub']);
    $this->checkPlaylistName($this->playlists['bux_1_pub'], $new_name);
    $this->goToPage($this->generateUrl('playlist', array('user_slug' => $this->users['bux']->getSlug(), 'playlist_id' => $this->playlists['bux_1_pub']->getId())));
    $this->checkReadPlaylist($this->playlists['bux_1_pub']);
  }
  
  protected function updatePlaylist($playlist)
  {
    $this->goToPage($this->generateUrl('playlist_edit', array(
      'user_slug' => $playlist->getOwner()->getSlug(),
      'playlist_id' => $playlist->getId()
    )));
    $this->isResponseSuccess();
    $this->exist('form.playlist_edit');
    
    $form = $this->selectForm('form.playlist_edit input[type="submit"]');
    $form['playlist[name]'] = $playlist->getName();
    $form['playlist[public]'] = $playlist->isPublic();
    $this->submit($form);
  }
  
  protected function checkPlaylistName($playlist, $name)
  {
    $playlist_in_database = $this->findOneBy('Playlist', $name);
    $this->assertTrue(!is_null($playlist_in_database));
  }
  
  public function testAddPrivateLinks()
  {
    $this->init();
    $this->initReadContextData();
    $this->connectUser('bux', 'toor');
    
    $this->goToPage($this->generateUrl('playlist', array('user_slug' => $this->users['bux']->getSlug(), 'playlist_id' => $this->playlists['bux_1_pub']->getId())));
    $this->exist('div.private_links form');
    $this->exist('a.open_playlist_private_links');
    
    $this->addSomePrivateLinks($this->playlists['bux_1_pub'], $private_links = array(
      'https://soundcloud.com/st-tetik/read-only-memories-g-noush',
      'https://soundcloud.com/triby/triby-extrait-next-liveset',
      'http://blog.bux.fr'
    ));
    $elements = $this->checkElementExistanceAndPresenceInPlaylistWithUrls($private_links, $this->playlists['bux_1_pub']);
    
    $this->checkPlaylistElements($this->playlists['bux_1_pub'], $elements, true, false);
    
    $this->disconnectUser();
    $this->connectUser('paul', 'toor');
    
    $this->checkPlaylistElements($this->playlists['bux_1_pub'], $elements, false, false);
  }
  
  protected function addSomePrivateLinks(Playlist $playlist, $links)
  {
    $this->tests_cases->playlistAddPrivateLinks($playlist, $links);
    $this->isResponseRedirection();
  }
  
  protected function checkElementExistanceAndPresenceInPlaylistWithUrls($urls_to_check, Playlist $playlist)
  {
    $elements = array();
    foreach ($urls_to_check as $url_to_check)
    {
      $element_to_check = $this->findOneBy('Element', array('url' => $url_to_check));
      $this->assertTrue(!is_null($element_to_check));
      $elements[] = $element_to_check;
      $playlist_to_check = $this->findOneBy('Playlist', array('id' => $playlist->getId()));
      $this->assertTrue(!is_null($playlist_to_check));
      $this->assertTrue($playlist_to_check->haveElement($element_to_check));
    }
    
    return $elements;
  }
  
}