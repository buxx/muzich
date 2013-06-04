<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;
use Muzich\CoreBundle\Managers\PlaylistManager;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Entity\Playlist;

class ElementTest extends Element
{
  public function __construct($id, $name)
  {
    $this->id = $id;
    $this->name = $name;
  }
}

class PlaylistEntityTest extends Playlist
{
  public function setId($id)
  {
    $this->id = $id;
  }
}

class PlaylistTest extends FunctionalTest
{
  
  protected $users = array();
  protected $elements = array();
  protected $playlist_manager = null;
  protected $playlists = array();
  
  protected function init()
  {
    $this->client = self::createClient();
    $this->users['bux'] = $this->getUser('bux');
    $this->users['bob'] = $this->getUser('bob');
    
    $this->elements[1] = new ElementTest(1, 'Element 1');
    $this->elements[2] = new ElementTest(2, 'Element 2');
    $this->elements[3] = new ElementTest(3, 'Element 3');
    $this->elements[4] = new ElementTest(4, 'Element 4');
    
    $this->playlist_manager = new PlaylistManager($this->getEntityManager());
    
    $this->playlists['bux'] = new PlaylistEntityTest();
    $this->playlists['bux']->setName('Una playlist!');
    $this->playlists['bux']->setOwner($this->users['bux']);
    $this->playlists['bux']->setId(1);
  }
  
  public function testPlaylistCreationAndPick()
  {
    $this->init();
    
    $this->addElementsToBuxPlaylist(array($this->elements[1], $this->elements[2], $this->elements[3], $this->elements[4]));
    $this->checkElementsInBuxPlaylist(array($this->elements[1], $this->elements[2], $this->elements[3], $this->elements[4]));
    $this->removeAnElementFormBuxPlaylist($this->elements[3]);
    $this->checkElementsInBuxPlaylist(array($this->elements[1], $this->elements[2], $this->elements[4]));
    
    $this->bobPickBuxPlaylist();
    $this->checkBobPickedPlaylists(array($this->playlists['bux']));
    $this->bobRemoveBuxPickedPlaylist();
    $this->checkBobPickedPlaylists(array());
    $this->bobRemoveBuxPickedPlaylist();
    $this->bobCopyBuxPlaylist();
    $this->checkCopyedPlaylist();
  }
  
  protected function addElementsToBuxPlaylist($elements)
  {
    foreach ($elements as $element)
    {
      $this->playlists['bux']->addElement($element);
    }
  }
  
  protected function checkElementsInBuxPlaylist($elements)
  {
    $this->assertEquals(count($elements), count($this->playlists['bux']->getElements()));
    foreach ($elements as $element)
    {
      $this->playlists['bux']->haveElement($element);
    }
  }
  
  protected function removeAnElementFormBuxPlaylist($element)
  {
    $this->playlists['bux']->removeElement($element);
  }
  
  protected function bobPickBuxPlaylist()
  {
    $this->playlist_manager->addPickedPlaylistToUser($this->users['bob'], $this->playlists['bux']);
  }
  
  protected function checkBobPickedPlaylists($playlists)
  {
    $this->assertEquals(count($playlists), count($this->users['bob']->getPickedsPlaylists()));
    foreach ($playlists as $playlist)
    {
      $this->assertTrue($this->playlistIsInPlaylists($playlist, $this->users['bob']->getPickedsPlaylists()));
    }
  }
  
  protected function playlistIsInPlaylists($playlist_searched, $playlists)
  {
    foreach ($playlists as $playlist)
    {
      if ($playlist->getId() == $playlist_searched->getId())
      {
        return true;
      }
    }
    
    return false;
  }
  
  protected function bobRemoveBuxPickedPlaylist()
  {
    $this->playlist_manager->removePickedPlaylistToUser($this->users['bob'], $this->playlists['bux']);
  }
  
  protected function bobCopyBuxPlaylist()
  {
    $this->playlist_manager->copyPlaylist($this->users['bob'], $this->playlists['bux']);
  }
  
  protected function checkCopyedPlaylist()
  {
    $this->assertEquals(2, count($this->users['bob']->getPlaylistsOwneds()));
    $bob_playlists = $this->users['bob']->getPlaylistsOwneds();
    $bux_playlist_copys = $this->playlists['bux']->getCopys();
    
    $this->assertCount(1, $bux_playlist_copys);
    $this->assertEquals($bux_playlist_copys[0]->getName(), $bob_playlists[1]->getName());
    
    foreach (array($this->elements[1], $this->elements[2], $this->elements[4]) as $element)
    {
      $this->assertTrue($bob_playlists[1]->haveElement($element));
    }
  }
  
  //public function testPlaylistDeletion()
  //{
  //  $this->init();
  //  
  //  $this->bobPickBuxPlaylist();
  //  $this->checkBobPickedPlaylists(array($this->playlists['bux']));
  //  
  //  $this->playlist_manager->deletePlaylist($this->playlists['bux']);
  //  
  //  $this->checkBobPickedPlaylists(array());
  //  $this->checkBobCopyedPlaylist(array($this->playlists['bux']));
  //}
  //
  //protected function checkBobCopyedPlaylist($playlists)
  //{
  //  $this->assertEquals(count($playlists), count($this->users['bob']->getPlaylistsOwneds()));
  //  $bob_playlists = $this->users['bob']->getPlaylistsOwneds();
  //  
  //  foreach ($playlists as $playlist)
  //  {
  //    $this->assertTrue($this->playlistIsInPlaylists($playlist, $bob_playlists));
  //  }
  //}
  
}