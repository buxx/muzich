<?php

namespace Muzich\IndexBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MuzichIndexBundle extends Bundle
{
  
  public function getParent()
  {
    return 'FOSUserBundle';
  }
  
}