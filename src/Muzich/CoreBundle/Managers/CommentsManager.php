<?php

namespace Muzich\CoreBundle\Managers;

/**
 * @author bux
 */
class CommentsManager
{
  
  protected $comments;
  
  public function __construct($comments = array())
  {
    $this->comments = $comments;
  }
  
  /**
   *
   * @param \Muzich\CoreBundle\Entity\User $user
   * @param String $comment
   * @param String $date 
   */
  public function add($user, $comment, $date = null)
  {
    if (!$date)
    {
      $date = date('Y-m-d H:i:s');
    }
    
    $this->comments[] = array(
      "u" => array(
        "i" => $user->getId(),
        "s" => $user->getSlug(),
        "n" => $user->getName()
      ),
      "d" => $date,
      "c" => $comment
    );
  }
  
  /**
   *
   * @return array
   */
  public function get()
  {
    return $this->comments;
  }
  
}
