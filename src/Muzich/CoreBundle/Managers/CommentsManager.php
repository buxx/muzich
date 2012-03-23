<?php

namespace Muzich\CoreBundle\Managers;

/**
 * Gestionnaire de commentaires.
 * Les commentaires sont stocké en base sous forme de tableau json afin d'économiser
 * relations entre tables. Ce menager per de gérer le tableau de commentaire
 * plus facilement.
 * 
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
   * Ajouter un commentaire au tableau.
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
  
  /**
   * Mise a jour d'un commentaire parmis le tableau. On l'identifie ici par son
   * auteur et sa date de publication.
   * 
   * @param \Muzich\CoreBundle\Entity\User $user
   * @param date $date (Y-m-d H:i:s u)
   * @param string $comment_c 
   */
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
   * Suppression d'un commentaire. Si il a été trouvé on retourne vrai.
   * 
   * @param int $user_id
   * @param string $date (Y-m-d H:i:s u)
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
  
  /**
   * Permet de récupérer l'index d'un commentaire dans le tableau de commentaires.
   * Si le commentaire n'est pas trouvé on retourne null.
   * 
   * @param int $user_id
   * @param  string $date (Y-m-d H:i:s u)
   * @return int 
   */
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
   * Retourne un commentaire en fonction de son index dans le tableau.
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
