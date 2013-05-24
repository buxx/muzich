<?php

namespace Muzich\CoreBundle\lib\Api;

use Muzich\CoreBundle\Entity\Element;

class Connector
{
  
  private $element;
  
  public function __construct(Element $element)
  {
    $this->element = $element;
  }
  
  public function getResponseForUrl($url)
  {
    $api_url = curl_init($url);
    
    $options = array(
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_HTTPHEADER => array('Content-type: application/json')
    );
      
    curl_setopt_array($api_url, $options);
    return new Response(json_decode(curl_exec($api_url), true));
  }
  
  public function setElementDatasWithResponse(Response $response, $parameters)
  {
    foreach ($parameters as $data_id => $searched)
    {
      if ($response->have($searched))
      {
        $this->element->setData($data_id, $response->get($searched));
      }
    }
  }
  
}