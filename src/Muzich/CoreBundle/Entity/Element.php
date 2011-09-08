<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="element")
 */
class Element
{
  
  /**
   * @ORM\ManyToOne(targetEntity="ElementType", inversedBy="elements")
   * @ORM\JoinColumn(name="element_type_id", referencedColumnName="id")
   */
  protected $type;
  
  /**
   * @ORM\ManyToMany(targetEntity="Tag", inversedBy="elements")
   * @ORM\JoinTable(name="elements_tag")
   */
  private $tags;
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;

  /**
   * @ORM\Column(type="string", length=1024)
   * @var type string
   */
  protected $url;
  
  /**
   * @ORM\Column(type="string", length=128)
   * @var type string
   */
  protected $name;
  
  /**
   * @ORM\Column(type="datetime")
   * @var type string
   */
  protected $date_added;
  

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
   * Set url
   *
   * @param string $url
   */
  public function setUrl($url)
  {
      $this->url = $url;
  }

  /**
   * Get url
   *
   * @return string 
   */
  public function getUrl()
  {
      return $this->url;
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
   * Set date_added
   *
   * @param datetime $dateAdded
   */
  public function setDateAdded($dateAdded)
  {
      $this->date_added = $dateAdded;
  }

  /**
   * Get date_added
   *
   * @return datetime 
   */
  public function getDateAdded()
  {
      return $this->date_added;
  }
  
  /**
   * Set type
   *
   * @param Muzich\CoreBundle\Entity\ElementType $type
   */
  public function setType(\Muzich\CoreBundle\Entity\ElementType $type)
  {
      $this->type = $type;
  }

  /**
   * Get type
   *
   * @return Muzich\CoreBundle\Entity\ElementType 
   */
  public function getType()
  {
      return $this->type;
  }
  
  
  public function __construct()
  {
    $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
  }
  

    /**
     * Add tags
     *
     * @param Muzich\CoreBundle\Entity\Tag $tags
     */
    public function addTag(\Muzich\CoreBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;
    }

    /**
     * Get tags
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }
}