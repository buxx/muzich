<?php

namespace Muzich\PlaylistBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Muzich\CoreBundle\Entity\Playlist;
use Muzich\CoreBundle\lib\AutoplayManager;

class ShowController extends Controller
{
  
  public function userAction($user_slug)
  {
    if (!($viewed_user = $this->findUserWithSlug($user_slug)))
    {
      throw $this->createNotFoundException();
    }
    
    return $this->render('MuzichPlaylistBundle:Show:user.html.twig', array(
      'viewed_user' => $viewed_user,
      'playlists'   => $this->getPlaylistManager()->getUserPublicsOrOwnedPlaylists($viewed_user, $this->getUser(true))
    ));
  }
  
  public function showAction($user_slug, $playlist_id)
  {
    if (!($playlist = $this->getPlaylistManager()->findOneAccessiblePlaylistWithId($playlist_id, $this->getUser(true))))
      throw $this->createNotFoundException();
    
    return $this->render('MuzichPlaylistBundle:Show:show.html.twig', array(
      'playlist' => $playlist
    ));
  }
  
  public function getAutoplayDataAction($playlist_id, $offset = null)
  {
    $playlist_manager = $this->getPlaylistManager();
    
    if (!($playlist = $playlist_manager->findOneAccessiblePlaylistWithId($playlist_id, $this->getUser(true))))
      throw $this->createNotFoundException();
    
    $autoplaym = new AutoplayManager($playlist_manager->getPlaylistElements($playlist, $offset), $this->container);
    
    return $this->jsonResponse(array(
      'status'    => 'success',
      'data'      => $autoplaym->getList()
    ));
  }
  
}