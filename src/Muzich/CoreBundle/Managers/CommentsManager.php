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
      $date = date('Y-m-d H:i:s u');
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
  
  public function update($user, $date, $comment_c)
  {
    $comments = array();
    foreach ($this->comments as $comment)
    {
      if ($comment['u']['i'] == $user->getId() && $comment['d'] == $date)
      {
        $comments[] = array(
          "u" => array(
            "i" => $user->getId(),
            "s" => $user->getSlug(),
            "n" => $user->getName()
          ),
          "e" => date('Y-m-d H:i:s u'),
          "d" => $date,
          "c" => $comment_c
        );
      }
      else
      {
        $comments[] = $comment;
      }
    }
    
    $this->comments = $comments;
  }
  
  /**
   *
   * @param int $user_id
   * @param string $date
   * @return boolean 
   */
  public function delete($user_id, $date)
  {
    $found = false;
    $comments = array();
    foreach ($this->comments as $comment)
    {
      if ($comment['u']['i'] != $user_id || $comment['d'] != $date)
      {
        $comments[] = $comment;
      }
      else
      {
        $found = true;
      }
    }
    $this->comments = $comments;
    return $found;
  }
  
  public function getIndex($user_id, $date)
  {
    foreach ($this->comments as $i => $comment)
    {
      if ($comment['u']['i'] == $user_id && $comment['d'] == $date)
      {
        return $i;
      }
    }
    return null;
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
