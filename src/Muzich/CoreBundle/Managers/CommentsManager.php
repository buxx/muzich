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
   * Retourne le dernier enregistrement commentaire
   * 
   * @return array
   */
  public function getLast()
  {
    return $this->get(count($this->comments)-1);
  }
  
  /**
   *
   * @return array
   */
  public function get($index = null)
  {
    if ($index === null)
    {
      return $this->comments;
    }
    
    return $this->comments[$index];
  }
  
}
