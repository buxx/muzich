<?php

namespace Muzich\CoreBundle\Factory\Elements;

use Muzich\CoreBundle\Factory\Elements\Youtubecom;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Factory\UrlMatchs;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\EntityManager;

class Youtube extends Youtubecom
{
  
  public function __construct(Element $element, Container $container, EntityManager $entity_manager)
  {
    parent::__construct($element, $container, $entity_manager);
    $this->setUrlAnalyzer(UrlMatchs::$youtu);
  }
  
}
