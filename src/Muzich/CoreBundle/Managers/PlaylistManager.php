<?php

namespace Muzich\CoreBundle\Managers;

use Doctrine\ORM\EntityManager;
use Muzich\CoreBundle\Entity\Playlist;
use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Entity\UserPlaylistPicked;
use \Doctrine\Common\Collections\ArrayCollection;
use Muzich\CoreBundle\lib\Tag as TagLib;

class PlaylistManager
{
  
  protected $entity_manager;
  protected $user;
  
  public function __construct(EntityManager $entity_manager)
  {
    $this->entity_manager = $entity_manager;
  }
  
  public function getUserPublicsOrOwnedPlaylists(User $user_viewed, User $user = null)
  {
    return $this->entity_manager->getRepository('MuzichCoreBundle:Playlist')
      ->getUserPublicPlaylistsOrOwnedQueryBuilder($user_viewed, $user)
      ->getQuery()->getResult()
    ;
  }
  
  public function findOneAccessiblePlaylistWithId($playlist_id, User $user = null)
  {
    return $this->entity_manager->getRepository('MuzichCoreBundle:Playlist')
      ->findOnePlaylistOwnedOrPublic($playlist_id, $user)
      ->getQuery()->getOneOrNullResult()
    ;
  }
  
  public function getPlaylistElements(Playlist $playlist)
  {
    return $this->entity_manager->getRepository('MuzichCoreBundle:Element')
      ->getElementsWithIdsOrderingQueryBuilder($playlist->getElementsIds())
      ->getQuery()->getResult()
    ;
  }
  
  public function getNewPlaylist(User $owner)
  {
    $playlist = new Playlist();
    $playlist->setOwner($owner);
    return $playlist;
  }
  
  public function addPickedPlaylistToUser(User $user, Playlist $playlist)
  {
    if (!$user->havePlaylistPicked($playlist))
    {
      $user_playlist_picked = new UserPlaylistPicked();
      $user_playlist_picked->init($user, $playlist);
      $user->getUserPlaylistsPickeds()->add($user_playlist_picked);
      $this->entity_manager->persist($user);
      $this->entity_manager->persist($user_playlist_picked);
    }
  }
  
  public function removePickedPlaylistToUser(User $user, Playlist $playlist)
  {
    if ($user->havePlaylistPicked($playlist))
    {
      $user_playlists_pickeds = new ArrayCollection();
      foreach ($user->getUserPlaylistsPickeds() as $user_playlist_picked)
      {
        if ($user_playlist_picked->getPlaylist()->getId() == $playlist->getId())
        {
          $this->entity_manager->remove($user_playlist_picked);
        }
        else
        {
          $user_playlists_pickeds->add($user_playlist_picked);
        }
      }
      $user->setUserPlaylistsPickeds($user_playlists_pickeds);
      $this->entity_manager->persist($user);
    }
  }
  
  public function copyPlaylist(User $user, Playlist $playlist)
  {
    $playlist_copied = new Playlist();
    $playlist_copied->setOwner($user);
    $playlist_copied->setTags($playlist->getTags());
    $playlist_copied->setElements($playlist->getElements());
    $playlist_copied->setCopied($playlist);
    $playlist->addCopy($playlist_copied);
    $user->getPlaylistsOwneds()->add($playlist_copied);
    $this->entity_manager->persist($playlist_copied);
    $this->entity_manager->persist($user);
  }
  
  public function addElementToPlaylist(Element $element, Playlist $playlist)
  {
    $playlist->addElement($element);
    $this->actualizePlaylistTags($playlist);
  }
  
  public function addElementsToPlaylist($elements, Playlist $playlist)
  {
    foreach ($elements as $element)
    {
      $playlist->addElement($element);
    }
    $this->actualizePlaylistTags($playlist);
  }
  
  public function removeElementFromPlaylist(Element $element, Playlist $playlist)
  {
    $playlist->removeElement($element);
    $this->actualizePlaylistTags($playlist);
  }
  
  protected function actualizePlaylistTags(Playlist $playlist)
  {
    $tag_lib = new TagLib();
    $playlist->cleanTags();
    foreach ($tag_lib->getOrderedEntityTagsWithElements($this->getPlaylistElements($playlist)) as $tag)
    {
      $playlist->addTag($tag);
    }
    $this->entity_manager->persist($playlist);
  }
  
}