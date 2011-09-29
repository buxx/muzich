<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class GroupRepository extends EntityRepository
{
  
  /**
   * Retourne un tableau d'user correspondant a un chaine de caractère
   * La recherche est effectué sur le username.
   * 
   * @param type $string
   * @return Doctrine\ORM\Query
   */
  public function findByString($string)
  {
    return $this->getEntityManager()
      ->createQuery("
        SELECT g FROM MuzichCoreBundle:Group g
        WHERE UPPER(g.name) LIKE :str
        ORDER BY g.name ASC"
      )
      ->setParameters(array(
        'str' => '%'.strtoupper(trim($string)).'%'
      ))
    ;
  }
  
}
  