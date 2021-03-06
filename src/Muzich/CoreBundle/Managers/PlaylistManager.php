<?php

namespace Muzich\CoreBundle\Managers;

use Doctrine\ORM\EntityManager;
use Muzich\CoreBundle\Entity\Playlist;
use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Entity\UserPlaylistPicked;
use \Doctrine\Common\Collections\ArrayCollection;
use Muzich\CoreBundle\lib\Tag as TagLib;
use Muzich\CoreBundle\Managers\ElementManager;
use Symfony\Component\DependencyInjection\Container;

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
  
  public function getUserPublicsOrOwnedOrPickedPlaylists(User $user_viewed, User $user = null)
  {
    return $this->entity_manager->getRepository('MuzichCoreBundle:Playlist')
      ->getUserPublicPlaylistsOrOwnedOrPickedQueryBuilder($user_viewed, $user)
      ->getQuery()->getResult()
    ;
  }
  
  public function getOwnedsPlaylists(User $user)
  {
    return $this->getUserPublicsOrOwnedPlaylists($user, $user);
  }
  
  public function getOwnedsOrPickedsPlaylists(User $user)
  {
    return $this->getUserPublicsOrOwnedOrPickedPlaylists($user, $user);
  }
  
  /** @return Playlist */
  public function findOneAccessiblePlaylistWithId($playlist_id, User $user = null)
  {
    return $this->entity_manager->getRepository('MuzichCoreBundle:Playlist')
      ->findOnePlaylistOwnedOrPublic($playlist_id, $user)
      ->getQuery()->getOneOrNullResult()
    ;
  }
  
  /** @return Playlist */
  public function findOwnedPlaylistWithId($playlist_id, User $user)
  {
    return $this->entity_manager->getRepository('MuzichCoreBundle:Playlist')
      ->findOnePlaylistOwned($playlist_id, $user)
      ->getQuery()->getOneOrNullResult()
    ;
  }
  
  /** @return Playlist */
  public function findPlaylistWithId($playlist_id, User $user)
  {
    return $this->entity_manager->getRepository('MuzichCoreBundle:Playlist')
      ->findOneById($playlist_id)
    ;
  }
  
  public function getPlaylistElements(Playlist $playlist, $offset = null, $user_id = null)
  {
    $element_ids = $playlist->getElementsIds();
    $query_builder = $this->entity_manager->getRepository('MuzichCoreBundle:Element')
      ->getElementsWithIdsOrderingQueryBuilder($element_ids, true, $user_id)
    ;
    
    if ($offset)
    {
      $query_builder->setFirstResult( $offset )
        ->setMaxResults( count($element_ids) );
    }
    
    return  $query_builder->getQuery()->getResult();
  }
  
  public function getNewPlaylist(User $owner = null)
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
  
  /** @return Playlist */
  public function copyPlaylist(User $user, Playlist $playlist)
  {
    $playlist_copied = new Playlist();
    $playlist_copied->setOwner($user);
    $playlist_copied->setName($playlist->getName());
    $playlist_copied->setPublic(false);
    $playlist_copied->setTags($playlist->getTags());
    $playlist_copied->setElements($playlist->getElements());
    $playlist_copied->setCopied($playlist);
    $playlist->addCopy($playlist_copied);
    $user->getPlaylistsOwneds()->add($playlist_copied);
    
    $this->entity_manager->persist($playlist_copied);
    $this->entity_manager->persist($user);
    
    return $playlist_copied;
  }
  
  public function addElementToPlaylist(Element $element, Playlist $playlist)
  {
    $playlist->addElement($element);
    $this->actualizePlaylistTags($playlist);
    $this->entity_manager->persist($playlist);
  }
  
  public function addElementsToPlaylist($elements, Playlist $playlist)
  {
    foreach ($elements as $element)
    {
      $playlist->addElement($element);
    }
    $this->actualizePlaylistTags($playlist);
  }
  
  public function removePlaylistElementWithId(Playlist $playlist, $element_id)
  {
    $playlist->removeElementWithId($element_id);
    $this->actualizePlaylistTags($playlist);
    $this->entity_manager->persist($playlist);
  }
  
  public function removePlaylistElementWithIndex(Playlist $playlist, $index)
  {
    $playlist->removeElementWithIndex($index);
    $this->actualizePlaylistTags($playlist);
    $this->entity_manager->persist($playlist);
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
  
  public function updatePlaylistElementsOrder(Playlist $playlist, $elements_ids_ordereds)
  {
    $elements_origin_order = $playlist->getElements();
    $elements_ordereds = array();
    foreach ($elements_ids_ordereds as $element_id)
    {
      if (($element_record_match = $this->findElementRecordWithId($elements_origin_order, $element_id)))
      {
        $elements_ordereds[] = $element_record_match;
      }
    }
    
    $playlist->setElements($elements_ordereds);
    $this->entity_manager->persist($playlist);
  }
  
  protected function findElementRecordWithId($elements, $searched_id)
  {
    foreach ($elements as $element_record)
    {
      if ($element_record['id'] == $searched_id)
      {
        return $element_record;
      }
    }
    
    return null;
  }
  
  public function deletePlaylist(Playlist $playlist)
  {
    $this->copyPlaylistForPickedUsers($playlist);
    $this->entity_manager->remove($playlist);
  }
  
  protected function copyPlaylistForPickedUsers(Playlist $playlist)
  {
    foreach ($playlist->getPickedsUsers() as $user)
    {
      $this->entity_manager->persist($this->copyPlaylist($user, $playlist));
    }
  }
  
  public function privatizePlaylist(Playlist $playlist)
  {
    $this->copyPlaylistForPickedUsers($playlist);
    $playlist->setPublic(false);
    $this->entity_manager->persist($playlist);
  }
  
  /** @return Element */
  public function getElementWithIndex(Playlist $playlist, $index)
  {
    $element_data = $playlist->getElementDataWithIndex($index);
    return $this->entity_manager->getRepository('MuzichCoreBundle:Element')
      ->findOneById($element_data['id']);
  }
  
  public function getElementsObjects(Playlist $playlist)
  {
    return $this->entity_manager->getRepository('MuzichCoreBundle:Element')
      ->findById($playlist->getElementsIds());
  }
  
  public function getElementsOfPlaylists($playlists)
  {
    $elements_ids = array();
    
    foreach ($playlists as $playlist)
    {
      foreach ($playlist->getElementsIds() as $element_id)
      {
        $elements_ids[] = $element_id;
      }
    }
    $elements_ids = array_unique($elements_ids);
    if ($elements_ids)
      return $this->entity_manager->getRepository('MuzichCoreBundle:Element')
      ->findById($elements_ids);
    
    return array();
  }
  
  public function addPrivateLinks(Playlist $playlist, User $user, $links, Container $container)
  {
    // Pour le moment on le fait ici car le ElementManager est mal pensé.
    $count_added = 0;
    if (count($links))
    {
      foreach ($links as $link)
      {
        $link = trim($link);
        if (filter_var($link, FILTER_VALIDATE_URL) !== false)
        {
          $element = new Element();
          $element->setUrl($link);
          $element->setType('none');
          $element->setPrivate(true);

          $factory = new ElementManager($element, $this->entity_manager, $container);
          $factory->proceedFill($user);
          
          $element->setNameWithData(true);
          
          $this->entity_manager->persist($element);
          $this->entity_manager->flush();
          $this->addElementToPlaylist($element, $playlist);
          
          $count_added += 1;
        }
      }
    }
    
    $this->entity_manager->persist($playlist);
    $this->entity_manager->flush();
    
    return $count_added;
  }
  
}