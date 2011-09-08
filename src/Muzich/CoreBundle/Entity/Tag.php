<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;

/**
 * Cet entité représente le Tag.
 * 
 * @ORM\Entity
 * @ORM\Table(name="tag")
 */
class Tag
{
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;
  
  /**
   * Cet attribu stocke la liste des élèments liés a ce tag.
   * 
   * @ORM\ManyToMany(targetEntity="Element", mappedBy="tags")
   */
  protected $elements;
  
  /**
   * Cet attribu stocke les enregistrements UsersTagsFavorites liés
   * a ce Tag dans le cadre des Tags favoris.
   * 
   * @ORM\OneToMany(targetEntity="UsersTagsFavorites", mappedBy="tag")
   */
  protected $users_favorites;
  /**
   * Nom du tag
   * 
   * @ORM\Column(type="string", length=32)
   * @var type string
   */
  protected $name;
  
  /**
   * 
   */
  public function __construct()
  {
    $this->users_favorites = new ArrayCollection();
    $this->elements = new ArrayCollection();
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
   * Set name
   *
   * @param string $name
   */
  public function setName($name)
  {
      $this->name = $name;
  }

  /**
   * Get name
   *
   * @return string 
   */
  public function getName()
  {
      return $this->name;
  }


    /**
     * Add elements
     *
     * @param Element $elements
     */
    public function addElement(Element $elements)
    {
        $this->elements[] = $elements;
    }

    /**
     * Get elements
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * Add users_favorites
     *
     * @param UsersTagsFavorites $usersFavorites
     */
    public function addUsersTagsFavorites(UsersTagsFavorites $usersFavorites)
    {
        $this->users_favorites[] = $usersFavorites;
    }

    /**
     * Get users_favorites
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUsersFavorites()
    {
        return $this->users_favorites;
    }
}