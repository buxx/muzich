<?php

namespace Muzich\CoreBundle\lib\Test;

use Symfony\Bundle\FrameworkBundle\Client as BaseClient;

class Client extends BaseClient
{
  static protected $connection;
  protected $requested;

  protected function doRequest($request)
  {
    if ($this->requested) {
      $this->kernel->shutdown();
      $this->kernel->boot();
    }

    $this->injectConnection();
    $this->requested = true;

    return $this->kernel->handle($request);
  }

  protected function injectConnection()
  {
    if (null === self::$connection) {
      self::$connection = $this->getContainer()->get('doctrine.dbal.default_connection');
    } else {
      if (! $this->requested) {
          self::$connection->rollback();
      }
      $this->getContainer()->set('doctrine.dbal.default_connection', self::$connection);
    }

    if (! $this->requested) {
      self::$connection->beginTransaction();
    }
  }
}