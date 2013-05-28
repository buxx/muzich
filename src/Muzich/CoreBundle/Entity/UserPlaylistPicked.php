<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class UserPlaylistPicked
{
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;
  
  /**
   * @ORM\ManyToOne(targetEntity="User", inversedBy="user_playlists_pickeds")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  protected $user;
  
  /**
   * @ORM\ManyToOne(targetEntity="Playlist", inversedBy="users_favorites")
   * @ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE")
   */
  protected $playlist;
  
  
  /**  @return integer */
  public function getId()
  {
      return $this->id;
  }

  public function setUser(User $user)
  {
    $this->user = $user;
  }

  /** @return User */
  public function getUser()
  {
    return $this->user;
  }
  
  public function setPlaylist(Playlist $playlist)
  {
    $this->playlist = $playlist;
  }

  /** @return Playlist */
  public function getPlaylist()
  {
    return $this->playlist;
  }
  
  public function init(User $user, Playlist $playlist)
  {
    $this->setUser($user);
    $this->setPlaylist($playlist);
  }
  
}