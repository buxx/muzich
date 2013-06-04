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
  
  public function addElementAndCreateAction(Request $request, $element_id)
  {
    if (!($element = $this->getElementWithId($element_id)))
      return $this->jsonNotFoundResponse();
    
    $form = $this->getPlaylistForm();
    $form->bind($request);
    if ($form->isValid())
    {
      $this->getPlaylistManager()->addElementToPlaylist($element, $form->getData());
      $this->flush();
      return $this->jsonSuccessResponse();
    }
    
    return $this->jsonResponseError('form_error',
      $this->render('MuzichPlaylistBundle:Show:form.html.twig', array(
        'form'       => $form->createView(),
        'element_id' => $element_id
      ))->getContent()
    );
  }
  
  public function addElementAndCopyAction($playlist_id, $element_id)
  {
    if (!($element = $this->getElementWithId($element_id)))
      return $this->jsonNotFoundResponse();
    
    if (!($playlist = $this->getPlaylistManager()->findOneAccessiblePlaylistWithId($playlist_id, $this->getUser())))
      return $this->jsonNotFoundResponse();
    
    $new_playlist = $this->getPlaylistManager()->copyPlaylist($this->getUser(), $playlist);
    $this->getPlaylistManager()->addElementToPlaylist($element, $new_playlist);
    $this->getPlaylistManager()->removePickedPlaylistToUser($this->getUser(), $playlist);
    $this->flush();
    
    return $this->jsonSuccessResponse();
  }
  
  public function deleteAction($playlist_id)
  {
    if (!($playlist = $this->getPlaylistManager()->findOwnedPlaylistWithId($playlist_id, $this->getUser())))
      throw $this->createNotFoundException();
    
    $this->getPlaylistManager()->deletePlaylist($playlist);
    $this->flush();
    $this->setFlash('success', 'playlist.delete.success');
    return $this->redirect($this->generateUrl('playlists_user', array('user_slug' => $this->getUser()->getSlug())));
  }
  
  public function unpickAction($playlist_id)
  {
    $playlist_manager = $this->getPlaylistManager();
    
    if (!($playlist = $playlist_manager->findPlaylistWithId($playlist_id, $this->getUser())))
      throw $this->createNotFoundException();
    
    $playlist_manager->removePickedPlaylistToUser($this->getUser(), $playlist);
    $this->flush();
    $this->setFlash('success', 'playlist.delete.success');
    return $this->redirect($this->generateUrl('playlists_user', array('user_slug' => $this->getUser()->getSlug())));
  }
  
}