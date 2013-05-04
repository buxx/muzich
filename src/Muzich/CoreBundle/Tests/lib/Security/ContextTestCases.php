<?php

namespace Muzich\CoreBundle\Tests\lib\Security;

use Muzich\CoreBundle\lib\Test\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Muzich\CoreBundle\Security\Context as SecurityContext;

class ContextTestCases
{
  
  protected $client;
  protected $test;
  
  public function __construct(Client $client, WebTestCase $test)
  {
    $this->client = $client;
    $this->test = $test;
  }
  
  private function responseSatisfyConditions($response, $success, $condition)
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
    return $this->responseSatisfyConditions(
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
    return $this->responseSatisfyConditions(
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
    return $this->responseSatisfyConditions(
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
    return $this->responseSatisfyConditions(
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
    return $this->responseSatisfyConditions(
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
    return $this->responseSatisfyConditions(
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
    
    if ($this->test->getClient()->getResponse()->getStatusCode() == 200 && $success)
    {
      return true;
    }
    
    if ($this->test->getClient()->getResponse()->getStatusCode() != 200 && !$success)
    {
      $security_context = new SecurityContext($this->test->getUser());
      if ($condition && !$security_context->userIsInThisCondition($condition))
      {
        return false;
      }
      
      return true;
    }
  }
  
  public function addCommentResponseIs($success, $condition)
  {
    return $this->responseSatisfyConditions(
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
    return $this->responseSatisfyConditions(
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
    return $this->responseSatisfyConditions(
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
  
}