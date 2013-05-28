<?php

namespace Muzich\CoreBundle\Managers;

use Doctrine\ORM\EntityManager;
use Muzich\CoreBundle\Entity\Playlist;
use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Entity\UserPlaylistPicked;
use \Doctrine\Common\Collections\ArrayCollection;

class PlaylistManager
{
  
  protected $entity_manager;
  protected $user;
  
  public function __construct(EntityManager $entity_manager)
  {
    $this->entity_manager = $entity_manager;
  }
  
  public function findOnePlaylistWithId($playlist_id, User $user = null)
  {
    $query_builder = $this->entity_manager->createQueryBuilder()
      ->select('p')
      ->from('MuzichCoreBundle:Playlist', 'p')
      ->where('p.id = :playlist_id')
    ;
    
    if ($user)
    {
      $query_builder->andWhere('p.public = 1 OR p.owner = :user_id');
    }
    else
    {
      $query_builder->andWhere('p.public = 1');
    }
    
    return $query_builder->getWuery()->getResult();
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
  
}