<?php

namespace Muzich\PlaylistBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Symfony\Component\HttpFoundation\Request;
use Muzich\CoreBundle\Security\Context as SecurityContext;
use Muzich\CoreBundle\Propagator\EventElement;
use Muzich\CoreBundle\Form\Playlist\PrivateLinksForm;
use Muzich\CoreBundle\Entity\Playlist;

class EditController extends Controller
{
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
  
  public function removeElementAction($playlist_id, $index)
  {
    if (($uncondition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_PLAYLIST_REMOVE_ELEMENT)) !== false)
      return $this->jsonResponseError($uncondition);
    
    $playlist_manager = $this->getPlaylistManager();
    if (!$this->tokenIsCorrect() || !($playlist = $playlist_manager->findOwnedPlaylistWithId($playlist_id, $this->getUser())))
      return $this->jsonNotFoundResponse();
    
    $element = $playlist_manager->getElementWithIndex($playlist, $index);
    
    if (!$element)
      return $this->jsonNotFoundResponse();
    
    $playlist_manager->removePlaylistElementWithIndex($playlist, $index);
    
    $event = new EventElement($this->container);
    $event->removedFromPlaylist($element, $this->getUser(), $playlist);
    
    $this->persist($element);
    $this->flush();
    return $this->jsonSuccessResponse(array(
      'element_remove_links' => $this->getRemoveLinksForPlaylist($playlist)
    ));
  }
  
  protected function getRemoveLinksForPlaylist(Playlist $playlist)
  {
    $element_remove_urls = array();
    foreach ($playlist->getElements() as $index => $element_data)
    {
      $element_remove_urls[] = $this->generateUrl('playlist_remove_element', array(
        'playlist_id' => $playlist->getId(),
        'index'       => $index,
        'token'       => $this->getToken()
      ));
    }
    
    return $element_remove_urls;
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
    
    $event = new EventElement($this->container);
    $event->addedToPlaylist($element, $this->getUser(), $playlist);
    
    $this->persist($element);
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
      
      $event = new EventElement($this->container);
      $event->addedToPlaylist($element, $this->getUser(), $form->getData());

      $this->persist($element);
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
    
    $event = new EventElement($this->container);
    $event->addedToPlaylist($element, $this->getUser(), $new_playlist);
    
    $this->persist($element);
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
  
  public function createAction(Request $request)
  {
    $playlist_form = $this->getPlaylistForm($this->getPlaylistManager()
      ->getNewPlaylist($this->getUser()));
    
    if ($request->getMethod() == 'POST')
    {
      $playlist_form->bind($request);
      if ($playlist_form->isValid())
      {
        $this->persist($playlist_form->getData());
        $this->flush();
        
        $this->setFlash('success', $this->trans('playlist.create.success', array(), 'flash'));
        return $this->redirect($this->generateUrl('playlist', array(
          'user_slug'   => $this->getUser()->getSlug(),
          'playlist_id' => $playlist_form->getData()->getId()
        )));
      }
    }
    
    return $this->render('MuzichPlaylistBundle:Edit:create.html.twig', array(
      'form'          => $playlist_form->createView()
    ));
  }
  
  public function editAction($playlist_id)
  {
    if (!($playlist = $this->getPlaylistManager()->findOwnedPlaylistWithId($playlist_id, $this->getUser())))
      throw $this->createNotFoundException();
    
    return $this->render('MuzichPlaylistBundle:Edit:edit.html.twig', array(
      'form'          => $this->getPlaylistForm($playlist)->createView(),
      'playlist'      => $playlist,
      'playlist_name' => $playlist->getName()
    ));
  }
  
  public function updateAction(Request $request, $playlist_id)
  {
    if (!($playlist = $this->getPlaylistManager()->findOwnedPlaylistWithId($playlist_id, $this->getUser())))
      throw $this->createNotFoundException();
    
    $playlist_name = $playlist->getName();
    $playlist_public = $playlist->isPublic();
    $form = $this->getPlaylistForm($playlist);
    $form->bind($request);
    if ($form->isValid())
    {
      if ($playlist_public && !$playlist->isPublic())
      {
        $this->getPlaylistManager()->privatizePlaylist($playlist);
      }
      
      $this->persist($form->getData());
      $this->flush();
      
      $this->setFlash('success', 'playlist.update.success');
      return $this->redirect($this->generateUrl('playlist', array(
        'user_slug'   => $playlist->getOwner()->getSlug(),
        'playlist_id' => $playlist->getId()
      )));
    }
    
    return $this->render('MuzichPlaylistBundle:Edit:edit.html.twig', array(
      'form'          => $form->createView(),
      'playlist'      => $playlist,
      'playlist_name' => $playlist_name
    ));
  }
  
  public function addPrivateLinksAction(Request $request, $playlist_id)
  {
    if (!($playlist = $this->getPlaylistManager()->findOneAccessiblePlaylistWithId($playlist_id, $this->getUser())))
      throw $this->createNotFoundException();
    
    $form = $this->createForm(new PrivateLinksForm());
    $form->bind($request);
    $data = $form->getData();
    
    if (!$data['links'])
    {
      $this->setFlash('warning', $this->trans('playlist.no_links_added', array(), 'elements'));
      return $this->redirect($this->generateUrl('playlist', array(
        'user_slug'   => $this->getUser()->getSlug(),
        'playlist_id' => $playlist_id
      )));
    }
    
    $count_added = $this->getPlaylistManager()->addPrivateLinks($playlist, $this->getUser(), explode("\n", $data['links']), $this->container);
    
    if ($count_added == count(explode("\n", $data['links'])))
    {
      $this->setFlash('success', $this->trans('playlist.links_added', array(), 'elements'));
    }
    else
    {
      $this->setFlash('warning', $this->trans('playlist.links_added_witherr', array(), 'elements'));
    }
    
    return $this->redirect($this->generateUrl('playlist', array(
      'user_slug'   => $this->getUser()->getSlug(),
      'playlist_id' => $playlist_id
    )));
  }
  
}