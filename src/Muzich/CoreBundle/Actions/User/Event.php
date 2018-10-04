<?php

namespace Muzich\CoreBundle\Actions\User;

use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Entity\Event as EventEntity;
use Symfony\Component\DependencyInjection\Container;

/**
 * Refactorisation d'actions lié aux événement de l'utilisateur
 *
 * @author bux
 */
class Event
{
  
  /**
   *
   * @var User 
   */
  protected $user;
  protected $event;
  protected $new = false;
  protected $container;
  
  /**
   *
   * @param User $user L'utilisateur concerné par l'événement
   * @param Container $container 
   */
  public function __construct(User $user, Container $container)
  {
    $this->user = $user;
    $this->container = $container;
  }
  
  /**
   * Cette méthode récupére (si elle existe) en base l'objet Event
   * correspondant a cet événement.
   * 
   * @param string $type 
   */
  protected function initialize($type)
  {
    $em = $this->container->get('doctrine')->getManager();
    try
    {
      $this->event = $em->createQuery(
        'SELECT e FROM MuzichCoreBundle:Event e
        WHERE e.user = :uid AND e.type = :type'
      )->setParameters(array(
        'uid'  => $this->user->getId(),
        'type' => $type
      ))->getSingleResult()
      ;
      $this->new = false;
    } 
    catch (\Doctrine\ORM\NoResultException $e)
    {
      $this->event = new EventEntity();
      $this->new = true;
    }
  }
  
  /**
   * Procéde a l'ajout de l'événement a la liste de l'utilisateur.
   * 
   * @param string $type
   * @param int $element_id
   * @return Event 
   */
  public function proceed($type, $element_id)
  {
    $this->initialize($type);
    if ($this->new)
    {
      $this->initEvent($type, $element_id);
    }
    else
    {
      $this->updateEvent($element_id);
    }
    
    return $this->event;
  }
  
  /**
   * 
   * 
   * @param int $element_id 
   */
  protected function updateEvent($element_id)
  {
    $this->event->addId($element_id);
  }
  
  /**
   * Création d'un objet Event
   * 
   * @param string $type
   * @param int $element_id 
   */
  protected function initEvent($type, $element_id)
  {
    $this->event->addId($element_id);
    $this->event->setType($type);
    $this->event->setUser($this->user);
  }
  
}
