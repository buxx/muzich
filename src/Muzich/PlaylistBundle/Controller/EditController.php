<?php

namespace Muzich\PlaylistBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Symfony\Component\HttpFoundation\Request;
use Muzich\CoreBundle\Security\Context as SecurityContext;

class EditController extends Controller
{
  
  // TODO: Cette méthode ET les autres: Mettre à jour avec le gestionnaire d'accès (Security)
  public function updateOrderAction(Request $request, $playlist_id)
  {
    if (($uncondition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_PLAYLIST_UPDATE_ORDER)) !== false)
      return $this->jsonResponseError($uncondition);
    
    $playlist_manager = $this->getPlaylistManager();
    if (!$this->tokenIsCorrect() || !($playlist = $playlist_manager->findOwnedPlaylistWithId($playlist_id, $this->getUser())) || !$request->get('elements'))
      return $this->jsonNotFoundResponse();
    
    $playlist_manager->updatePlaylistElementsOrder($playlist, $request->get('elements'));
    $this->flush();
    return $this->jsonSuccessResponse();
  }
  
  public function removeElementAction($playlist_id, $element_id)
  {
    if (($uncondition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_PLAYLIST_REMOVE_ELEMENT)) !== false)
      return $this->jsonResponseError($uncondition);
    
    $playlist_manager = $this->getPlaylistManager();
    if (!$this->tokenIsCorrect() || !($playlist = $playlist_manager->findOwnedPlaylistWithId($playlist_id, $this->getUser())))
      return $this->jsonNotFoundResponse();
    
    $playlist_manager->removePlaylistElementWithId($playlist, $element_id);
    $this->flush();
    return $this->jsonSuccessResponse();
  }
  
  public function addElementAction($playlist_id, $element_id)
  {
    if (($uncondition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_PLAYLIST_ADD_ELEMENT)) !== false)
      return $this->jsonResponseError($uncondition);
    
    $playlist_manager = $this->getPlaylistManager();
    if (!$this->tokenIsCorrect() || !($playlist = $playlist_manager->findOwnedPlaylistWithId($playlist_id, $this->getUser()))
        || !($element = $this->getElementWithId($element_id)))
      return $this->jsonNotFoundResponse();
    
    $playlist_manager->addElementToPlaylist($element, $playlist);
    $this->flush();
    return $this->jsonSuccessResponse();
  }
  
  public function addElementAndCreateAction(Request $request, $element_id)
  {
    if (($uncondition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_PLAYLIST_CREATE)) !== false)
      return $this->jsonResponseError($uncondition);
    
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
    if (($uncondition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_PLAYLIST_COPY)) !== false)
      return $this->jsonResponseError($uncondition);
    
    if (!$this->tokenIsCorrect() || !($element = $this->getElementWithId($element_id)))
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
    if (($uncondition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_PLAYLIST_DELETE)) !== false)
      throw $this->createNotFoundException();
    
    if (!$this->tokenIsCorrect() || !($playlist = $this->getPlaylistManager()->findOwnedPlaylistWithId($playlist_id, $this->getUser())))
      throw $this->createNotFoundException();
    
    $this->getPlaylistManager()->deletePlaylist($playlist);
    $this->flush();
    $this->setFlash('success', 'playlist.delete.success');
    
    return $this->redirect($this->generateUrl('playlists_user', array('user_slug' => $this->getUser()->getSlug())));
  }
  
  public function unpickAction($playlist_id, $redirect_owner = false)
  {
    if (($uncondition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_PLAYLIST_UNPICK)) !== false)
      throw $this->createNotFoundException();
    
    $playlist_manager = $this->getPlaylistManager();
    
    if (!$this->tokenIsCorrect() || !($playlist = $playlist_manager->findPlaylistWithId($playlist_id, $this->getUser())))
      throw $this->createNotFoundException();
    
    $playlist_manager->removePickedPlaylistToUser($this->getUser(), $playlist);
    $this->flush();
    $this->setFlash('success', 'playlist.delete.success');
    
    if ($redirect_owner)
      return $this->redirect($this->generateUrl('playlists_user', array('user_slug' => $playlist->getOwner()->getSlug())));
    
    return $this->redirect($this->generateUrl('playlists_user', array('user_slug' => $this->getUser()->getSlug())));
  }
  
  public function pickAction($playlist_id, $redirect_owner = false)
  {
    if (($uncondition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_PLAYLIST_PICK)) !== false)
    {
      if ($this->getRequest()->isXmlHttpRequest())
        return $this->jsonResponseError($uncondition);
      throw $this->createNotFoundException();
    }
    if (!$this->tokenIsCorrect() || !($playlist = $this->getPlaylistManager()->findOneAccessiblePlaylistWithId($playlist_id)))
    {
      if ($this->getRequest()->isXmlHttpRequest())
        return $this->jsonNotFoundResponse();
      throw $this->createNotFoundException();
    }
    $this->getPlaylistManager()->addPickedPlaylistToUser($this->getUser(), $playlist);
    $this->flush();
    
    if ($this->getRequest()->isXmlHttpRequest())
      return $this->jsonSuccessResponse();
    
    if ($redirect_owner)
      return $this->redirect($this->generateUrl('playlists_user', array('user_slug' => $playlist->getOwner()->getSlug())));
    
    return $this->redirect($this->generateUrl('playlists_user', array('user_slug' => $this->getUser()->getSlug())));
  }
  
}