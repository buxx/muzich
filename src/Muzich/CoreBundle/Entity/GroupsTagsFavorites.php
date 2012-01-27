<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cette classe représente la relation porteuse entre Group et Tag, 
 * en tant que Tags favoris du groupe.
 * 
 * @ORM\Entity
 * @ORM\Table(name="groups_tags_favorites")
 */
class GroupsTagsFavorites
{
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;
  
  /**
   * Cet attribut contient l'objet Group lié
   * 
   * @ORM\ManyToOne(targetEntity="Group", inversedBy="tags")
   * @ORM\JoinColumn(name="group_id", referencedColumnName="id", onDelete="CASCADE")
   */
  protected $group;
  
  /**
   * Cet attribut contient l'objet Tag lié
   * 
   * @ORM\ManyToOne(targetEntity="Tag", inversedBy="groups_favorites")
   * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
   */
  protected $tag;
  
  /**
   * L'attribut position permet de connaitre l'ordre de préfèrence du 
   * groupe.
   * 
   * @ORM\Column(type="integer")
   * @var type int
   */
  protected $position;
    
  public function __toString()
  {
    return $this->getTag()->getName();
  }

  /**
   * Set position
   *
   * @param integer $position
   */
  public function setPosition($position)
  {
      $this->position = $position;
  }

  /**
   * Get position
   *
   * @return integer 
   */
  public function getPosition()
  {
      return $this->position;
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
   * Set group
   *
   * @param Group $group
   */
  public function setGroup(Group $group)
  {
    $this->group = $group;
  }

  /**
   * Get group
   *
   * @return Group 
   */
  public function getGroup()
  {
    return $this->group;
  }

  /**
   * Set tag
   *
   * @param Tag $tag
   */
  public function setTag(Tag $tag)
  {
    $this->tag = $tag;
  }

  /**
   * Get tag
   *
   * @return Tag 
   */
  public function getTag()
  {
    return $this->tag;
  }
}