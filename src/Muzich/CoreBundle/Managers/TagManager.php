<?php

namespace Muzich\CoreBundle\Managers;

use Muzich\CoreBundle\Entity\Tag;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Bundle\DoctrineBundle\Registry;
use Muzich\CoreBundle\Util\StrictCanonicalizer;

/**
 * 
 *
 * @author bux
 */
class TagManager
{
  
  protected $nameCanonicalizer;
  
  public function __construct(CanonicalizerInterface $nameCanonicalizer = null)
  {
    if ($nameCanonicalizer)
    {
      $this->nameCanonicalizer = $nameCanonicalizer;
    }
    else
    {
      $this->nameCanonicalizer = new StrictCanonicalizer();
    }
  }
  
  public function updateSlug(Tag $tag)
  {
    $tag->setSlug($this->nameCanonicalizer->canonicalize($tag->getName()));
  }
  
  public function addTag(Registry $doctrine, $name, $user, $arguments = null)
  {
    $name_canonicalized = $this->nameCanonicalizer->canonicalize(trim($name));
    
    // Check avant de commencer: On regarde si ce tag n'existe pas déjà en tag
    // public (en cas de gruge)
    if (($tag = $doctrine->getRepository('MuzichCoreBundle:Tag')
      ->findOneBy(array(
        'slug'       => $name_canonicalized,
        'tomoderate' => false
      ))))
    {
      // Si il existe déjà pas besoin de l'ajouter
      return $tag;
    }
    
    // Première étape, on regarde en base si quelqu'un a pas déjà ajouté ce tag
    if (($tag = $doctrine->getRepository('MuzichCoreBundle:Tag')
      ->findOneBy(array(
        'slug'       => $name_canonicalized,
        'tomoderate' => true
      ))))
    {
      // Si il existe déjà pas besoin de l'ajouter ou le retourne
      // après avoir ajouté cet utilisateurs a la liste de ceux pouvant le voir
      $privatesids = json_decode($tag->getPrivateids());
      if (!in_array($user->getId(), $privatesids))
      {
        $privatesids[] = (string)$user->getId();
      }
      $tag->setPrivateids(json_encode($privatesids));
      $tag->setArguments($tag->getArguments(). " ****" . $user->getName()."****: " .$arguments);
      
      $doctrine->getEntityManager()->persist($tag);
      $doctrine->getEntityManager()->flush();
      
      return $tag;
    }
    else
    {
      // Sinon on l'ajoute en base
      $tag = new Tag();
      $tag->setName(ucfirst(strtolower($name)));
      $tag->setTomoderate(true);
      $tag->setPrivateids(json_encode(array((string)$user->getId())));
      $tag->setArguments(" ****" . $user->getName()."****: " .$arguments);
      
      $doctrine->getEntityManager()->persist($tag);
      $doctrine->getEntityManager()->flush();
      
      return $tag;
    }
  }
  
}