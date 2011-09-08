<?php

namespace Muzich\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * Cet entité est l'utilisateur ayant effectué la requête.
 * 
 * @ORM\Entity
 * @ORM\Table(name="muzich_user")
 */
class User extends BaseUser
{
  
 /**
  * @ORM\Id
  * @ORM\Column(type="integer")
  * @ORM\generatedValue(strategy="AUTO")
  */
  protected $id;
  
  /**
   * Cet attribut contient les enregistrements UsersTagsFavorites lié 
   * a cet utilisateur dans le cadre des Tags Favoris.
   * 
   * @ORM\OneToMany(targetEntity="Muzich\CoreBundle\Entity\UsersTagsFavorites", mappedBy="user")
   */
  private $tags_favorites;

  /**
   * 
   */
  public function __construct()
  {
    $this->tags_favorites = new ArrayCollection();
    parent::__construct();
  }

  /**
   * Get id
   *
   * @return integer 
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Get tags_favorites
   *
   * @return Doctrine\Common\Collections\Collection 
   */
  public function getTagsFavorites()
  {
    return $this->tags_favorites;
  }
  
  /**
   * Add tags_favorites
   *
   * @param Muzich\CoreBundle\Entity\UsersTagsFavorites $tagsFavorites
   */
  public function addUsersTagsFavorites(Muzich\CoreBundle\Entity\UsersTagsFavorites $tagsFavorites)
  {
    $this->tags_favorites[] = $tagsFavorites;
  }
}