<?php

namespace Muzich\CoreBundle\Managers;

use Muzich\CoreBundle\Entity\Tag;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Bundle\DoctrineBundle\Registry;
use Muzich\CoreBundle\Util\StrictCanonicalizer;
use Muzich\CoreBundle\Entity\UsersTagsFavorites;
use Muzich\CoreBundle\Entity\GroupsTagsFavorites;

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
      $tag->setSlug($name_canonicalized);
      $tag->setTomoderate(true);
      $tag->setPrivateids(json_encode(array((string)$user->getId())));
      $tag->setArguments(" ****" . $user->getName()."****: " .$arguments);
      
      $doctrine->getEntityManager()->persist($tag);
      $doctrine->getEntityManager()->flush();
      
      return $tag;
    }
  }
  
  protected function replaceTagByAnother(EntityManager $em, $tag, $new_tag)
  {
    /*
     * Trois cas de figures ou sont utilisés les tags
     *  * Sur un élément
     *  * Tag favori
     *  * Tag d'un groupe
     */
    
    // Sur un élément
    foreach ($elements = $em->createQuery("
      SELECT e, t FROM MuzichCoreBundle:Element e
      JOIN e.tags t
      WHERE t.id  = :tid
    ")
      ->setParameter('tid', $tag->getId())
      ->getResult() as $element)
    {
      // Pour chaque elements lié a ce tag
      // On ajoute un lien vers le nouveau tag si il n'en n'a pas déjà
      if (!$element->hasTag($new_tag))
      {
        $element->addTag($new_tag);
        $em->persist($element);
      }
    }
    
    // Tag favoris
    foreach ($favorites = $em->createQuery("
      SELECT f FROM MuzichCoreBundle:UsersTagsFavorites f
      WHERE f.tag  = :tid
    ")
      ->setParameter('tid', $tag->getId())
      ->getResult() as $fav)
    {
      // Pour chaque favoris utilisant ce tag on regarde si l'utilisateur
      // n'a pas déjà le nouveau tag en favoris.
      if (!$em->createQuery("
        SELECT COUNT(f.id) FROM MuzichCoreBundle:UsersTagsFavorites f
        WHERE f.tag  = :tid AND f.user = :uid
      ")
      ->setParameters(array('tid' => $new_tag->getId(), 'uid' => $fav->getUser()->getId()))
      ->getSingleScalarResult())
      {
        $new_fav = new UsersTagsFavorites();
        $new_fav->setTag($new_tag);
        $new_fav->setUser($fav->getUser());
        $new_fav->setPosition($fav->getPosition());
        $em->persist($new_fav);
      }
      $em->remove($fav);
    }
    
    // groupe
    foreach ($em->createQuery("
      SELECT f FROM MuzichCoreBundle:GroupsTagsFavorites f
      WHERE f.tag  = :tid
    ")
      ->setParameter('tid', $tag->getId())
      ->getResult() as $fav)
    {
      // Pour chaque favoris utilisant ce tag on regarde si le groupe
      // n'a pas déjà le nouveau tag en favoris.
      if (!$em->createQuery("
        SELECT COUNT(f.id) FROM MuzichCoreBundle:GroupsTagsFavorites f
        WHERE f.tag  = :tid AND f.group = :gid
      ")
      ->setParameters(array('tid' => $new_tag->getId(), 'gid' => $fav->getGroup()->getId()))
      ->getSingleScalarResult())
      {
        $new_fav = new GroupsTagsFavorites();
        $new_fav->setTag($new_tag);
        $new_fav->setGroup($fav->getGroup());
        $new_fav->setPosition($fav->getPosition());
        $em->persist($new_fav);
      }
      $em->remove($fav);
    }
    
    $em->remove($tag);
    $em->flush();
  }
  
  public function moderateTag(Registry $doctrine, $tag_id, $accept, $replacing_id = null)
  {
    if (($tag = $doctrine->getRepository('MuzichCoreBundle:Tag')->findOneBy(array(
      'id'         => $tag_id,
      'tomoderate' => true
    ))))
    {
      if ($accept)
      {
        $tag->setTomoderate(false);
        $tag->setPrivateids(null);
        $doctrine->getEntityManager()->persist($tag);
        $doctrine->getEntityManager()->flush();
      }
      else
      {
        if ($replacing_id)
        {
          // Si c'est un remplacement on envoit la sauce
          if (!($new_tag = $doctrine->getRepository('MuzichCoreBundle:Tag')->findOneById($replacing_id)))
          {
            return false;
          }
          $this->replaceTagByAnother($doctrine->getEntityManager(), $tag, $new_tag);
        }
        else
        {
          $doctrine->getEntityManager()->remove($tag);
          $doctrine->getEntityManager()->flush();
        }
        
      }
      return true;
    }
    else
    {
      return false;
    }
  }
  
}