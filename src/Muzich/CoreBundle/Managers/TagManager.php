<?php

namespace Muzich\CoreBundle\Managers;

use Muzich\CoreBundle\Entity\Tag;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use FOS\UserBundle\Util\CanonicalizerInterface;

/**
 * 
 *
 * @author bux
 */
class TagManager
{
  
  protected $nameCanonicalizer;
  
  public function __construct(CanonicalizerInterface $nameCanonicalizer)
  {
    $this->nameCanonicalizer = $nameCanonicalizer;
  }
  
  public function updateSlug(Tag $tag)
  {
    $tag->setSlug($this->nameCanonicalizer->canonicalize($tag->getName()));
  }
  
}