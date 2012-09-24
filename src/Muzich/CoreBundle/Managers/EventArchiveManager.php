<?php

namespace Muzich\CoreBundle\Managers;

use Doctrine\ORM\EntityManager;
use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Entity\EventArchive;

/**
 * 
 * 
 * @author bux
 */
class EventArchiveManager
{
  
  /**
   *
   * @var EntityManager 
   */
  private $em;
  /**
   *
   * @var EventArchive 
   */
  private $archive;
  /**
   *
   * @var boolean 
   */
  private $new;
  
  public function __construct(EntityManager $em)
  {
    $this->em = $em;
  }
  
  private function determineArchive(User $user, $type)
  {
    try
    {
      $this->archive = $this->em->createQuery(
        'SELECT a FROM MuzichCoreBundle:EventArchive a
        WHERE a.user = :uid AND a.type = :type'
      )->setParameters(array(
        'uid'  => $user->getId(),
        'type' => $type
      ))->getSingleResult()
      ;
      $this->new = false;
    } 
    catch (\Doctrine\ORM\NoResultException $e)
    {
      $this->archive = new EventArchive();
      $this->new = true;
    }
  }
  
  private function initArchive(User $user, $type)
  {
    $this->archive->setUser($user);
    $this->archive->setCount(1);
    $this->archive->setType($type);
  }
  
  private function incrementArchive()
  {
    $this->archive->setCount($this->archive->getCount()+1);
  }
  
  public function add(User $user, $type)
  {
    $this->determineArchive($user, $type);
    if ($this->new)
    {
      $this->initArchive($user, $type);
    }
    else
    {
      $this->incrementArchive();
    }
    $this->em->persist($this->archive);
  }
  
}
