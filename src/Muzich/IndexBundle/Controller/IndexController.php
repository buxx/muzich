<?php

namespace Muzich\IndexBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class IndexController
{
  public function indexAction()
  {
    return new Response('<html><body>Hello!</body></html>');
  }
}