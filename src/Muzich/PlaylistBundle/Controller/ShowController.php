<?php

namespace Muzich\PlaylistBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Muzich\CoreBundle\lib\AutoplayManager;
use Muzich\CoreBundle\Security\Context as SecurityContext;
use Muzich\CoreBundle\Form\Playlist\PrivateLinksForm;

class ShowController extends Controller
{
  
  public function userAction($user_slug)
  {
    if (($uncondition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_PLAYLIST_SHOW)) !== false)
      return $this->jsonResponseError($uncondition);
    
    if (!($viewed_user = $this->findUserWithSlug($user_slug)))
      throw $this->createNotFoundException();
    
    return $this->render('MuzichPlaylistBundle:Show:user.html.twig', array(
      'viewed_user' => $viewed_user,
      'playlists'   => $this->getPlaylistManager()->getUserPublicsOrOwnedorPickedPlaylists($viewed_user, $this->getUserOrNullIfVisitor())
    ));
  }
  
  public function showAction($user_slug, $playlist_id)
  {
    if (($uncondition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_PLAYLIST_SHOW)) !== false)
      return $this->jsonResponseError($uncondition);
    
    if (!($viewed_user = $this->findUserWithSlug($user_slug)))
      throw $this->createNotFoundException();
    
    if (!($playlist = $this->getPlaylistManager()->findOneAccessiblePlaylistWithId($playlist_id, $this->getUserOrNullIfVisitor())))
      return $this->redirect($this->generateUrl('playlists_user', array('user_slug' => $user_slug)));
    
    return $this->render('MuzichPlaylistBundle:Show:show.html.twig', array(
      'playlist'    => $playlist,
      'viewed_user' => $viewed_user,
      'links_form'  => $this->createForm(new PrivateLinksForm())->createView()
    ));
  }
  
  public function getAutoplayDataAction($playlist_id, $offset = null, $shuffle = false)
  {
    if (($uncondition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_PLAYLIST_DATA_AUTOPLAY)) !== false)
      return $this->jsonResponseError($uncondition);
    
    $playlist_manager = $this->getPlaylistManager();
    if (!($playlist = $playlist_manager->findOneAccessiblePlaylistWithId($playlist_id, $this->getUserOrNullIfVisitor())))
      return $this->jsonNotFoundResponse();
    
    $autoplaym = new AutoplayManager($playlist_manager->getPlaylistElements($playlist, $offset, $this->getUserId(true)), $this->container);
    
    if ($shuffle)
      $autoplaym->shuffle();
    
    return $this->jsonResponse(array(
      'status'    => 'success',
      'data'      => $autoplaym->getList()
    ));
  }
  
  public function getAddElementPromptAction($element_id)
  {
    if (($uncondition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_PLAYLIST_ADD_PROMPT)) !== false)
      return $this->jsonResponseError($uncondition);
    
    if (!($element = $this->getElementWithId($element_id)))
      return $this->jsonNotFoundResponse();
    
    
    return $this->jsonSuccessResponse(
      $this->render('MuzichPlaylistBundle:Show:prompt.html.twig', array(
        'form'       => $this->getPlaylistForm()->createView(),
        'element'    => $element,
        'playlists'  => (!$this->isVisitor())?$this->getPlaylistManager()->getOwnedsOrPickedsPlaylists($this->getUser()):array()
      ))->getContent()
    );
  }
  
}