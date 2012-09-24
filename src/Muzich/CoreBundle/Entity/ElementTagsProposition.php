<?php

namespace Muzich\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Muzich\CoreBundle\Validator as MuzichAssert;

/**
 * Cette table permet aux utilisateurs de proposer des tags sur des éléments
 * ne leurs appartenant pas.
 * 
 * @ORM\Entity
 * @ORM\Table(name="element_tags_proposition")
 * @ORM\Entity(repositoryClass="Muzich\CoreBundle\Repository\ElementTagsPropositionRepository")
 * 
 */
class ElementTagsProposition
{
  
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   * @var type int
   */
  protected $id;

  /**
   * Propriétaire de la proposition
   * 
   * @ORM\ManyToOne(targetEntity="User", inversedBy="element_tags_propositions")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  protected $user;

  /**
   * Propriétaire de la proposition
   * 
   * @ORM\ManyToOne(targetEntity="Element", inversedBy="tags_propositions")
   * @ORM\JoinColumn(name="element_id", referencedColumnName="id")
   */
  protected $element;
  
  /**
   * Cet attribut stocke la liste des tags liés a cette proposition.
   * 
   * @ORM\ManyToMany(targetEntity="Tag", inversedBy="propositions")
   * @ORM\JoinTable(name="element_tags_proposition_tags_rel")
   * @MuzichAssert\Tags()
   */
  protected $tags;
  
  /**
   * @var datetime $created
   *
   * @ORM\Column(type="datetime")
   */
  private $created;
//  /**
//   * @var datetime $created
//   *
//   * @Gedmo\Timestampable(on="create")
//   * @ORM\Column(type="datetime")
//   */
//  private $created;
//
//  /**
//   * @var datetime $updated
//   *
//   * @ORM\Column(type="datetime")
//   * @Gedmo\Timestampable(on="update")
//   */
//  private $updated;
  
    
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
   * Set created
   *
   * @param datetime $created
   */
  public function setCreated($created)
  {
    $this->created = $created;
  }

  /**
   * Get created
   *
   * @return datetime 
   */
  public function getCreated()
  {
    return $this->created;
  }

//  /**
//   * Set updated
//   *
//   * @param datetime $updated
//   */
//  public function setUpdated($updated)
//  {
//    $this->updated = $updated;
//  }
//
//  /**
//   * Get updated
//   *
//   * @return datetime 
//   */
//  public function getUpdated()
//  {
//    return $this->updated;
//  }

  /**
   * Set user
   *
   * @param Muzich\CoreBundle\Entity\User $user
   */
  public function setUser(\Muzich\CoreBundle\Entity\User $user)
  {
    $this->user = $user;
  }

  /**
   * Get user
   *
   * @return Muzich\CoreBundle\Entity\User 
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * Set element
   *
   * @param Muzich\CoreBundle\Entity\Element $element
   */
  public function setElement(\Muzich\CoreBundle\Entity\Element $element)
  {
    $this->element = $element;
  }

  /**
   * Get element
   *
   * @return Muzich\CoreBundle\Entity\Element 
   */
  public function getElement()
  {
    return $this->element;
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