<?php

namespace Muzich\CoreBundle\Tests\lib\Security;

use Muzich\CoreBundle\lib\Test\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Muzich\CoreBundle\Security\Context as SecurityContext;
use Symfony\Component\DomCrawler\Crawler;

class ContextTestCases
{
  
  protected $client;
  protected $test;
  
  public function __construct(Client $client, WebTestCase $test)
  {
    $this->client = $client;
    $this->test = $test;
  }
  
  private function ajaxResponseSatisfyConditions($response, $success, $condition)
  {
    $response = json_decode($response, true);
    
    if ($response['status'] === 'success' && $success)
    {
      return true;
    }
    
    if ($response['status'] === 'error' && !$success)
    {
      if ($condition && !array_key_exists('error', $response))
      {
        return false;
      }
      
      if ($condition && $response['error'] !== $condition)
      {
        return false;
      }
      
      return true;
    }
    
    return false;
  }
  
  private function responseSatisfyConditions($response, $success, $condition, $user)
  {
    if (($response->getStatusCode() == 200 || $response->getStatusCode() == 302) && $success)
    {
      return true;
    }
    
    if (($response->getStatusCode() != 302 && $response->getStatusCode() != 302) && !$success)
    {
      $security_context = new SecurityContext($user);
      if ($condition && !$security_context->userIsInThisCondition($condition))
      {
        return false;
      }
      
      return true;
    }
  }
  
  public function getAjaxRequestContentResponse($method, $url, $parameters = array())
  {
    $this->test->getClient()->request(
      $method, $url, $parameters, array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
    return $this->test->getClient()->getResponse()->getContent();
  }
  
  public function addElementResponseIs($success, $condition)
  {
    return $this->ajaxResponseSatisfyConditions(
      $this->getAjaxRequestContentResponse(
        'POST',
        $this->test->generateUrl('element_add', array('_locale' => 'fr'))
      ), 
      $success, 
      $condition
    );
  }
  
  public function noteElementResponseIs($success, $condition)
  {
    return $this->ajaxResponseSatisfyConditions(
      $this->getAjaxRequestContentResponse(
        'GET',
        $this->test->generateUrl('ajax_element_add_vote_good', array(
          'element_id' => 0,
          'token' => 'notoken'
        ))
      ), 
      $success, 
      $condition
    );
  }
  
  public function alertCommentResponseIs($success, $condition)
  {
    return $this->ajaxResponseSatisfyConditions(
      $this->getAjaxRequestContentResponse(
        'GET',
        $this->test->generateUrl('ajax_alert_comment', array(
          'element_id' => 0,
          'date'       => 0,
          'token'      => 'notoken'
        ))
      ), 
      $success, 
      $condition
    );
  }
  
  public function alertElementResponseIs($success, $condition)
  {
    return $this->ajaxResponseSatisfyConditions(
      $this->getAjaxRequestContentResponse(
        'GET',
        $this->test->generateUrl('ajax_report_element', array(
          'element_id' => 0,
          'token'      => 'notoken'
        ))
      ), 
      $success, 
      $condition
    );
  }
  
  public function addTagResponseIs($success, $condition)
  {
    return $this->ajaxResponseSatisfyConditions(
      $this->getAjaxRequestContentResponse(
        'POST',
        $this->test->generateUrl('ajax_add_tag'),
        array('tag_name' => 'Mon Beau Tag !1245ddregfz')
      ), 
      $success, 
      $condition
    );
  }
  
  public function proposeElementTagsResponseIs($success, $condition)
  {
    return $this->ajaxResponseSatisfyConditions(
      $this->getAjaxRequestContentResponse(
        'POST',
        $this->test->generateUrl('ajax_element_propose_tags_proceed', 
          array('element_id' => 0, 'token' => 'notoken')
        ),
        array(
          'element_tag_proposition_0' => array(
            'tags' => json_encode(array(0, 0))
          )
        )
      ), 
      $success, 
      $condition
    );
  }
  
  public function addGroupResponseIs($success, $condition)
  {
    $this->test->getClient()->request(
      'POST', 
      $this->test->generateUrl('group_add'), 
      array(
        'group' => array(
          'name' => 'Un groupe lala45f4rgb1e',
          'description' => 'description d45fqs4cq6',
          'tags' => array(),
          '_token' => 'notoken'
        )
      ), 
      array(), 
      array()
    );
    
    return $this->responseSatisfyConditions(
      $this->test->getClient()->getResponse(), 
      $success, 
      $condition, 
      $this->test->getUser()
    );
  }
  
  public function addCommentResponseIs($success, $condition)
  {
    return $this->ajaxResponseSatisfyConditions(
      $this->getAjaxRequestContentResponse(
        'POST',
        $this->test->generateUrl('ajax_add_comment', array(
          'element_id' => 0,
          'token'      => 'notoken'
        ))
      ), 
      $success, 
      $condition
    );
  }
  
  public function addElementToFavoriteResponseIs($success, $condition)
  {
    return $this->ajaxResponseSatisfyConditions(
      $this->getAjaxRequestContentResponse(
        'GET',
        $this->test->generateUrl('favorite_add', array(
          'id'    => 0,
          'token' => 'notoken'
        ))
      ), 
      $success, 
      $condition
    );
  }
  
  public function followUserResponseIs($success, $condition)
  {
    return $this->ajaxResponseSatisfyConditions(
      $this->getAjaxRequestContentResponse(
        'GET',
        $this->test->generateUrl('follow', array(
          'type' => 'user', 
          'id' => 0,
          'token' => 'notoken'
        ))
      ), 
      $success, 
      $condition
    );
  }
  
  public function getFavoritesTagsResponseIs($success, $condition)
  {
    return $this->ajaxResponseSatisfyConditions(
      $this->getAjaxRequestContentResponse(
        'GET',
        $this->test->generateUrl('ajax_get_favorites_tags', array(
          'favorites' => true
        ))
      ), 
      $success, 
      $condition
    );
  }
  
  public function playlistAddElementResponseIs($success, $condition)
  {
    $this->playlistAddElement(0, 0);
    return $this->ajaxResponseSatisfyConditions(
      $this->test->getClient()->getResponse()->getContent(), 
      $success, 
      $condition
    );
  }
  
  public function playlistAddElement($playlist_id, $element_id)
  {
    return $this->getAjaxRequestContentResponse(
      'GET',
      $this->test->generateUrl('playlists_add_element', array(
        'playlist_id' => $playlist_id,
        'element_id'  => $element_id,
        '_locale'     => 'fr',
        'token'       => $this->test->getToken()
      ))
    );
  }
  
  public function playlistUpdateOrderResponseIs($success, $condition)
  {
    $this->playlistUpdateOrder(0, array());
    return $this->ajaxResponseSatisfyConditions(
      $this->test->getClient()->getResponse()->getContent(), 
      $success, 
      $condition
    );
  }
  
  public function playlistUpdateOrder($playlist_id, $elements)
  {
    $elements_ids = array();
    foreach ($elements as $element)
    {
      $elements_ids[] = $element->getId();
    }
    
    return $this->getAjaxRequestContentResponse(
      'GET',
      $this->test->generateUrl('playlist_update_order', array(
        'playlist_id' => $playlist_id,
        '_locale'     => 'fr',
        'token'       => $this->test->getToken()
      )),
      array(
        'elements' => $elements_ids
      )
    );
  }
  
  public function playlistRemoveElementResponseIs($success, $condition)
  {
    $this->playlistRemoveElement(0, 0);
    return $this->ajaxResponseSatisfyConditions(
      $this->test->getClient()->getResponse()->getContent(),
      $success, 
      $condition
    );
  }
  
  public function playlistRemoveElement($playlist_id, $index)
  {
    return $this->getAjaxRequestContentResponse(
      'GET',
      $this->test->generateUrl('playlist_remove_element', array(
        'playlist_id' => $playlist_id,
        'index'       => $index,
        '_locale'     => 'fr',
        'token'       => $this->test->getToken()
      ))
    );
  }
  
  public function playlistAddElementAndCopy($playlist_id, $element_id)
  {
    return $this->getAjaxRequestContentResponse(
      'GET',
      $this->test->generateUrl('playlists_add_element_and_copy', array(
        'playlist_id' => $playlist_id,
        'element_id'  => $element_id,
        '_locale'     => 'fr',
        'token'       => $this->test->getToken()
      ))
    );
  }
  
  public function playlistCreateResponseIs($success, $condition)
  {
    $this->playlistCreate(0, 'my_super_playlist');
    return $this->ajaxResponseSatisfyConditions(
      $this->test->getClient()->getResponse()->getContent(),
      $success, 
      $condition
    );
  }
  
  public function playlistCreate($element_id, $playlist_name)
  {
    $this->test->goToPage($this->test->generateUrl('playlists_add_element_prompt', array(
      'element_id'  => $element_id,
      '_locale'     => 'fr'
    )));
    
    $response = json_decode($this->test->client->getResponse()->getContent(), true);
    $crawler = new Crawler($response['data']);
    
    $extract = $crawler->filter('input[name="playlist[_token]"]')
      ->extract(array('value'));
    $csrf = $extract[0];
    
    $this->test->crawler = $this->test->client->request(
      'POST', 
      $this->test->generateUrl('playlist_add_element_and_create', array(
        'element_id'  => $element_id,
        '_locale'     => 'fr'
      )), 
      array(
        'playlist' => array(
          'name'   => $playlist_name,
          '_token' => $csrf
        )
      ),
      array(), 
      array('HTTP_X-Requested-With' => 'XMLHttpRequest')
    );
  }


  public function playlistCopyResponseIs($success, $condition)
  {
    return $this->ajaxResponseSatisfyConditions(
      $this->getAjaxRequestContentResponse(
        'GET',
        $this->test->generateUrl('playlists_add_element_and_copy', array(
          'playlist_id' => 0,
          'element_id'  => 0,
          '_locale'     => 'fr',
          'token'       => $this->test->getToken()
        ))
      ), 
      $success, 
      $condition
    );
  }
  
  public function playlistDeleteResponseIs($success, $condition)
  {
    $this->playlistDelete(0);
    return $this->responseSatisfyConditions(
      $this->test->getClient()->getResponse(), 
      $success, 
      $condition, 
      $this->test->getUser()
    );
  }
  
  public function playlistDelete($playlist_id)
  {
    $this->test->getClient()->request(
      'GET', 
      $this->test->generateUrl('playlist_delete', array(
          'playlist_id' => $playlist_id,
          '_locale'     => 'fr',
          'token'       => $this->test->getToken()
        )), 
      array(), 
      array(), 
      array()
    );
  }
  
  public function playlistUnpickResponseIs($success, $condition)
  {
    $this->playlistUnPick(0);
    return $this->responseSatisfyConditions(
      $this->test->getClient()->getResponse(), 
      $success, 
      $condition, 
      $this->test->getUser()
    );
  }
  
  public function playlistUnPick($playlist_id)
  {
    $this->test->goToPage($this->test->generateUrl('playlist_unpick', array(
      'playlist_id' => $playlist_id,
      '_locale'     => 'fr',
      'token'       => $this->test->getToken()
    )));
  }
  
  public function playlistPickResponseIs($success, $condition)
  {
    $this->playlistPick(0);
    return $this->ajaxResponseSatisfyConditions(
      $this->test->getClient()->getResponse()->getContent(), 
      $success, 
      $condition
    );
  }
  
  public function playlistPick($playlist_id)
  {
    return $this->getAjaxRequestContentResponse(
      'GET',
      $this->test->generateUrl('playlist_pick', array(
        'playlist_id' => $playlist_id,
        '_locale'     => 'fr',
        'token'       => $this->test->getToken()
      ))
    );
  }
  
  public function playlistShowResponseIs($success, $condition)
  {
    $this->playlistShow('bux', 0);
    
    return $this->responseSatisfyConditions(
      $this->test->getClient()->getResponse(), 
      $success, 
      $condition, 
      $this->test->getUser()
    );
  }
  
  public function playlistsShow($user_slug)
  {
    $this->test->goToPage($this->test->generateUrl('playlists_user', array(
      'user_slug'   => $user_slug,
      '_locale'     => 'fr'
    )));
  }
  
  public function playlistShow($user_slug, $playlist_id)
  {
    $this->test->goToPage($this->test->generateUrl('playlist', array(
      'user_slug'   => $user_slug,
      'playlist_id' => $playlist_id,
      '_locale'     => 'fr'
    )));
  }
  
  public function playlistAutoplayResponseIs($success, $condition)
  {
    $this->playlistAutoplay(0);
    return $this->ajaxResponseSatisfyConditions(
      $this->test->getClient()->getResponse()->getContent(),
      $success, 
      $condition
    );
  }
  
  public function playlistAutoplay($playlist_id)
  {
    return $this->getAjaxRequestContentResponse(
      'GET',
      $this->test->generateUrl('playlist_datas_for_autoplay', array(
        'playlist_id' => $playlist_id,
        '_locale'     => 'fr'
      ))
    );
  }
  
  public function playlistPromptResponseIs($success, $condition)
  {
    $this->playlistPrompt(0);
    return $this->ajaxResponseSatisfyConditions(
      $this->test->getClient()->getResponse()->getContent(),
      $success, 
      $condition
    );
  }
  
  public function playlistPrompt($element_id)
  {
    return $this->getAjaxRequestContentResponse(
      'GET',
      $this->test->generateUrl('playlists_add_element_prompt', array(
        'element_id' => $element_id,
        '_locale'    => 'fr'
      ))
    );
  }
  
}