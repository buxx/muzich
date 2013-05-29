<?php

namespace Muzich\PlaylistBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Muzich\CoreBundle\Entity\Playlist;
use Symfony\Component\HttpFoundation\Request;

class EditController extends Controller
{
  
  // TODO: Cette méthode ET les autres: Mettre à jour avec le gestionnaire d'accès (Security)
  public function updateOrderAction(Request $request, $playlist_id)
  {
    $playlist_manager = $this->getPlaylistManager();
    if (!($playlist = $playlist_manager->findOwnedPlaylistWithId($playlist_id, $this->getUser())) || !$request->get('elements'))
      return $this->jsonNotFoundResponse();
    
    $playlist_manager->updatePlaylistElementsOrder($playlist, $request->get('elements'));
    $this->flush();
    return $this->jsonSuccessResponse();
  }
  
  public function removeElementAction($playlist_id, $element_id)
  {
    $playlist_manager = $this->getPlaylistManager();
    if (!($playlist = $playlist_manager->findOwnedPlaylistWithId($playlist_id, $this->getUser())))
      return $this->jsonNotFoundResponse();
    
    $playlist_manager->removePlaylistElementWithId($playlist, $element_id);
    $this->flush();
    return $this->jsonSuccessResponse();
  }
  
  public function addElementAction($playlist_id, $element_id)
  {
    $playlist_manager = $this->getPlaylistManager();
    if (!($playlist = $playlist_manager->findOwnedPlaylistWithId($playlist_id, $this->getUser()))
        || !($element = $this->getElementWithId($element_id)))
      return $this->jsonNotFoundResponse();
    
    $playlist_manager->addElementToPlaylist($element, $playlist);
    $this->flush();
    return $this->jsonSuccessResponse();
  }
  
}