<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Type d'element
 *  * Youtube
 *  * Jamendo
 *  * ...
 * 
 * @ORM\Entity
 * @ORM\Table(name="element_type")
 */
class ElementType
{
  
  /**
   * @ORM\Id
   * @ORM\Column(type="string", length=12)
   * @var type string
   */
  protected $id;
  
  /**
   * Liste des elements de ce type
   * 
   * @ORM\OneToMany(targetEntity="Element", mappedBy="element_type")
   */
  protected $elements;
  
  /**
   * Libelle du type
   * 
   * @ORM\Column(type="string", length=128)
   * @var type string
   */
  protected $name;
  
  /**
   * 
   */
  public function __construct()
  {
    $this->elements = new ArrayCollection();
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
   * Set id
   *
   * @param string $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * Get id
   *
   * @return string 
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Add elements
   *
   * @param Muzich\CoreBundle\Entity\Element $elements
   */
  public function addElement(\Muzich\CoreBundle\Entity\Element $elements)
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
}