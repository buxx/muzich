<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ElementTagsPropositionRepository extends EntityRepository
{
  
  public function findByElement($element_id)
  {
    return $this->getEntityManager()
      ->createQuery('SELECT p FROM MuzichCoreBundle:ElementTagsProposition p 
        JOIN p.user u
        JOIN p.tags t
        WHERE p.element = :eid')
      ->setParameter('eid', $element_id)
      ->getResult()
    ;
  }
  
  public function findOneById($id)
  {
    return $this->getEntityManager()
      ->createQuery('SELECT p FROM MuzichCoreBundle:ElementTagsProposition p 
        JOIN p.element e
        JOIN p.tags t
        WHERE p.id = :id')
      ->setParameter('id', $id)
      ->getSingleResult()
    ;
  }
  
}