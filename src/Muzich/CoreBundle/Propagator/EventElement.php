<?php

namespace Muzich\CoreBundle\Propagator;

use Muzich\CoreBundle\Propagator\EventPropagator;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Actions\User\Event as UserEventAction;
use Muzich\CoreBundle\Actions\User\Reputation as UserReputation;
use Muzich\CoreBundle\Entity\Event;
use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Managers\CommentsManager;
use Muzich\CoreBundle\Entity\ElementTagsProposition;
use Muzich\CoreBundle\Managers\EventArchiveManager;
use Muzich\CoreBundle\Entity\EventArchive;

/**
 * Propagateur d'événement concernant les éléments
 *
 * @author bux
 */
class EventElement extends EventPropagator
{  
  
  /**
   * Cette procédure doit être appelé après l'ajout d'un commentaire sur un 
   * événement. Actuellement il:
   * * Met a jour ou créer un objet événement (nouveau commentaire) pour le
   *   propriétaire de l'élément.
   * * Met a jour ou créer un objet événement (nouveau commentaire) pour les
   *   utilisateurs qui follow cet élément.
   * 
   * @param Element $element 
   */
  public function commentAdded(Element $element, User $user)
  {
    // On avertis le propriétaire si ce n'est pas lui même qui vient de commenter
    if ($user->getId() != $element->getOwner()->getId())
    {
      $uea = new UserEventAction($element->getOwner(), $this->container);
      $event = $uea->proceed(Event::TYPE_COMMENT_ADDED_ELEMENT, $element->getId());
      $this->container->get('doctrine')->getEntityManager()->persist($event);
    }
    
    // Pour chaque utilisateur qui a demandé a être avertis d'un nouveau commentaire
    $cm = new CommentsManager($element->getComments());
    $uids = $cm->getFollowersIds();
    
    if (count($uids))
    {
      $users = $this->container->get('doctrine')->getEntityManager()
        ->getRepository('MuzichCoreBundle:User')
        ->getUsersWithIds($uids)
      ;
      if (count($users))
      {
        foreach ($users as $user_c)
        {
          // On n'avertis pas l'utilisateur de son propre commentaire
          if ($user->getId() != $user_c->getId())
          {
            $uea = new UserEventAction($user_c, $this->container);
            $event = $uea->proceed(Event::TYPE_COMMENT_ADDED_ELEMENT, $element->getId());
            $this->container->get('doctrine')->getEntityManager()->persist($event);
          }
        }
      }
    }
  }
  
  /**
   * Un point a été ajouté par quelqu'un a cet élément
   * Conséquences:
   *  * L'auteur du partage gagne x point de reputation
   *
   * @param Element $element 
   */
  public function onePointAdded(Element $element)
  {
    $ur = new UserReputation($element->getOwner());
    $ur->addPoints(
      $this->container->getParameter('reputation_element_point_value')
    );
  }
  
  /**
   * Un point a été retiré par quelqu'un a cet élément
   * Conséquences:
   *  * L'auteur du partage perd x point de reputation
   *
   * @param Element $element 
   */
  public function onePointRemoved(Element $element)
  {
    $ur = new UserReputation($element->getOwner());
    $ur->removePoints(
      $this->container->getParameter('reputation_element_point_value')
    );
  }
  
  /**
   * L'élément a été ajouté aux favoris d'un utilisateur
   * 
   * @param Element $element 
   */
  public function addedToFavorites(Element $element)
  {
    $ur = new UserReputation($element->getOwner());
    $ur->addPoints(
      $this->container->getParameter('reputation_element_favorite_value')
    );
    
    $uea = new UserEventAction($element->getOwner(), $this->container);
    $event = $uea->proceed(Event::TYPE_FAV_ADDED_ELEMENT, $element->getId());
    $this->container->get('doctrine')->getEntityManager()->persist($event);
  }
  
  /**
   * L'élément a été retiré des favoris d'un utilisateur
   * 
   * @param Element $element 
   */
  public function removedFromFavorites(Element $element)
  {
    $ur = new UserReputation($element->getOwner());
    $ur->removePoints(
      $this->container->getParameter('reputation_element_favorite_value')
    );
  }
  
  /**
   * Des tags viennent d'être proposé a un élément
   *
   * @param Element $element 
   */
  public function tagsProposed(Element $element)
  {
    $uea = new UserEventAction($element->getOwner(), $this->container);
    $event = $uea->proceed(Event::TYPE_TAGS_PROPOSED, $element->getId());
    $this->container->get('doctrine')->getEntityManager()->persist($event);
  }
  
  public function tagsAccepteds(ElementTagsProposition $proposition)
  {
    // On archive le fait que la proposition est été accepté
    $eam = new EventArchiveManager($this->container->get('doctrine')->getEntityManager());
    $eam->add($proposition->getUser(), EventArchive::PROP_TAGS_ELEMENT_ACCEPTED);
    
    // Et on donne des points a l'utilisateur
    $ur = new UserReputation($proposition->getUser());
    $ur->addPoints(
      $this->container->getParameter('reputation_element_tags_element_prop_value')
    );
    $this->container->get('doctrine')->getEntityManager()->persist($proposition->getUser());
  }
  
}