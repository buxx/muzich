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
  
}