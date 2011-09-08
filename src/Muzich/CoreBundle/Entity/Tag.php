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
   * @ORM\ManyToMany(targetEntity="Tag", mappedBy="tags")
   */
  protected $elements;
  
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
     * @param Muzich\CoreBundle\Entity\Tag $elements
     */
    public function addTag(\Muzich\CoreBundle\Entity\Tag $elements)
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