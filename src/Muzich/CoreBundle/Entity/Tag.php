<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tag")
 */
class Tag
{
  
  /**
   * @ManyToMany(targetEntity="Tag", mappedBy="tags")
   */
  private $elements;
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;
  
  /**
   * @ORM\Column(type="string", length=32)
   * @var type string
   */
  protected $name;
  
  public function __construct()
  {
    $this->elements = new \Doctrine\Common\Collections\ArrayCollection();
  }
  
}