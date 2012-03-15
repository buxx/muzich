<?php

namespace Muzich\CoreBundle\Managers;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Entity\User;

/**
 * @author bux
 */
class ElementReportManager
{
  
  protected $element;
  
  public function __construct(Element $element)
  {
    $this->element = $element;
  }
  
  /**
   *
   * @param \Muzich\CoreBundle\Entity\User $user
   * @param String $comment
   * @param String $date 
   */
  public function add(User $user)
  {
    $ids = $this->element->getReportIds();
    
    if ($ids === null){$ids = array();}
    
    if (!in_array($user->getId(), $ids))
    {
      $ids[] = (string)$user->getId();
    }
    $this->element->setCountReport(count($ids));
    $this->element->setReportIds($ids);
  }
  
}
