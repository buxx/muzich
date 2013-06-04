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
      'playlists'   => $this->getPlaylistManager()->getUserPublicsOrOwnedorPickedPlaylists($viewed_user, $this->getUserOrNullIfVisitor())
    ));
  }
  
  public function showAction($user_slug, $playlist_id)
  {
    if (!($viewed_user = $this->findUserWithSlug($user_slug)))
      throw $this->createNotFoundException();
    
    if (!($playlist = $this->getPlaylistManager()->findOneAccessiblePlaylistWithId($playlist_id, $this->getUserOrNullIfVisitor())))
      return $this->redirect($this->generateUrl('playlists_user', array('user_slug' => $user_slug)));
    
    return $this->render('MuzichPlaylistBundle:Show:show.html.twig', array(
      'playlist'    => $playlist,
      'viewed_user' => $viewed_user
    ));
  }
  
  public function getAutoplayDataAction($playlist_id, $offset = null)
  {
    $playlist_manager = $this->getPlaylistManager();
    
    if (!($playlist = $playlist_manager->findOneAccessiblePlaylistWithId($playlist_id, $this->getUserOrNullIfVisitor())))
      throw $this->createNotFoundException();
    
    $autoplaym = new AutoplayManager($playlist_manager->getPlaylistElements($playlist, $offset), $this->container);
    
    return $this->jsonResponse(array(
      'status'    => 'success',
      'data'      => $autoplaym->getList()
    ));
  }
  
  public function getAddElementPromptAction($element_id)
  {
    return $this->jsonSuccessResponse(
      $this->render('MuzichPlaylistBundle:Show:prompt.html.twig', array(
        'form'       => $this->getPlaylistForm()->createView(),
        'element_id' => $element_id,
        'playlists'  => (!$this->isVisitor())?$this->getPlaylistManager()->getOwnedsOrPickedsPlaylists($this->getUser()):array()
      ))->getContent()
    );
  }
  
}