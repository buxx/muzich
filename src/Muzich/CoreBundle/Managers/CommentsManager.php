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
    if ($comments == null)
    {
      $comments = array();
    }
    
    $this->comments = $comments;
  }
  
  /**
   * Ajouter un commentaire au tableau.
   * 
   * @param \Muzich\CoreBundle\Entity\User $user
   * @param String $comment Contenu du commentaire
   * @param boolean $follow L'utilisateur désire être avertis des nouveaux commentaires
   * @param String $date date de l'envoi
   */
  public function add($user, $comment, $follow = false, $date = null)
  {
    if (!$date)
    {
      $date = date('Y-m-d H:i:s').' '.substr(microtime(), 0, 10);
    }
    
    $this->comments[] = array(
      "u" => array(
        "i" => $user->getId(),
        "s" => $user->getSlug(),
        "n" => $user->getName()
      ),
      "a" => array(),
      "f" => $follow,
      "d" => $date,
      "c" => $comment
    );
    
    if (!$follow)
    {
      $this->userSetFollowToFalse($user->getId());
    }
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
   * @param boolean $follow
   */
  public function update($user, $date, $comment_c, $follow)
  {
    $comments = array();
    $found = false;
    foreach ($this->comments as $comment)
    {
      if ($comment['u']['i'] == $user->getId() && $comment['d'] == $date)
      {
        $found = true;
        $comments[] = array(
          "u" => array(
            "i" => $user->getId(),
            "s" => $user->getSlug(),
            "n" => $user->getName()
          ),
          "a" => $comment['a'],
          "e" => date('Y-m-d H:i:s').' '.substr(microtime(), 0, 10),
          "f" => $follow,
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
    
    if (!$follow && $found)
    {
      $this->userSetFollowToFalse($user->getId());
    }
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
  
  public function deleteWithDate($date)
  {
    foreach ($this->comments as $i => $comment)
    {
      if ($comment['d'] == $date)
      {
        unset($this->comments[$i]);
        return true;
      }
    }
    return false;
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
   * Permet de récupérer l'index d'un commentaire dans le tableau de commentaires.
   * Si le commentaire n'est pas trouvé on retourne null.
   * 
   * @param  string $date (Y-m-d H:i:s u)
   * @return int 
   */
  public function getIndexWithDate($date)
  {
    foreach ($this->comments as $i => $comment)
    {
      if ($comment['d'] == $date)
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
  
  /**
   * Regarde si il existe un commentaire pour cet utilisateur où il demande
   * a suivre les commentaires.
   * 
   * @param int $user_id
   * @return boolean 
   */
  public function userFollow($user_id)
  {
    foreach ($this->comments as $i => $comment)
    {
      if ($comment['u']['i'] == $user_id && $comment['f'] == true)
      {
        return true;
      }
    }
    return false;
  }
  
  /**
   * Passe tout les commentaire de cet utilisateur en follow = false
   * 
   * @param int $user_id 
   */
  private function userSetFollowToFalse($user_id)
  {
    $comments = array();
    foreach ($this->comments as $comment)
    {
      if ($comment['u']['i'] == $user_id)
      {
        $comment['f'] = false;
      }
      $comments[] = $comment;
    }
    $this->comments = $comments;
  }
  
  /**
   *  Retourne les ids utilisateurs ayant demandé a être avertis des nouveaux
   * commentaires.
   * 
   * @return array ids des utilisateurs
   */
  public function getFollowersIds()
  {
    $ids = array();
    foreach ($this->comments as $comment)
    {
      if ($comment['f'] == true)
      {
        if (!in_array($comment['u']['i'], $ids))
        {
          $ids[] = $comment['u']['i'];
        }
      }
    }
    return $ids;
  }
  
  /**
   * Signalement d'un commentaire par un utilisateur
   *
   * @param int $user_id id de l'user faisant le signalement
   * @param date $date   date du commentaire
   * 
   * @return boolean
   */
  public function alertComment($user_id, $date)
  {
    foreach ($this->comments as $i => $comment)
    {
      if ($comment['d'] == $date)
      {
        if (!in_array($user_id, $comment['a']))
        {
          $comment['a'][] = $user_id;
          $this->comments[$i] = $comment;
          return true;
        }
      }
    }
    return false;
  }
  
  /**
   * Retrait du signalement d'un commentaire de la part d'un user.
   * 
   * @param type $user_id  id de l'user retirant son signalement
   * @param date $date     date du commentaire
   * @return boolean 
   */
  public function unAlertComment($user_id, $date)
  {
    foreach ($this->comments as $i => $comment)
    {
      if ($comment['d'] == $date)
      {
        if (in_array($user_id, $comment['a']))
        {
          foreach ($comment['a'] as $ii => $user_alert_id)
          {
            if ($user_alert_id == $user_id)
            {
              unset($comment['a'][$ii]);
              $this->comments[$i] = $comment;
              return true;
            }
          }
        }
        
      }
    }
    return false;
  }
  
  /**
   * Retourne le nombre de commentaires signalés.
   * 
   * @return boolean
   */
  public function countCommentAlert()
  {
    $count = 0;
    foreach ($this->comments as $i => $comment)
    {
      if (count($comment['a']))
      {
        $count = $count+1;
      }
    }
    return $count;
  }
  
  public function getAlertedComments()
  {
    $comments = array();
    foreach ($this->comments as $i => $comment)
    {
      if (count($comment['a']))
      {
        $comments[] = $comment;
      }
    }
    return $comments;
  }
  
  public function cleanAlertsOnComment($date)
  {
    foreach ($this->comments as $i => $comment)
    {
      if ($comment['d'] == $date)
      {
        $ids = $comment['a'];
        $this->comments[$i]['a'] = array();
        return $ids;
      }
    }
    return array();
  }
  
}
