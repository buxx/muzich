<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ElementRepository extends EntityRepository
{
  
  /**
   * Méthode "exemple" pour la suite.
   * 
   * @return array 
   */
  public function findAllOrderedByName()
    {
        return $this->getEntityManager()
          ->createQuery('
            SELECT e, t FROM MuzichCoreBundle:Element e 
            JOIN e.type t
            ORDER BY e.name ASC'
          )
          ->getResult()
        ;
    }
}