<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class GroupRepository extends EntityRepository
{
  
  /**
   * Retourne un tableau de groupe correspondant a un chaine de caractère
   * La recherche est effectué sur le name.
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
  
  /**
   * Retourne une Query sleectionnant un Group par son slug
   * 
   * @param type string 
   * @return Doctrine\ORM\Query
   */
  public function findOneBySlug($slug)
  {
    return $this->getEntityManager()
      ->createQuery("
        SELECT g FROM MuzichCoreBundle:Group g
        LEFT JOIN g.tags tr 
        LEFT JOIN tr.tag t
        WHERE g.slug = :str
      ")
      ->setParameters(array(
        'str' => $slug
      ))
    ;
  }
  
  /**
   * Retourne un tableau de groupes étant publics, ou possédé par l'user
   * 
   * @param int $user_id
   * @return array id => name
   */
  public function getPublicAndOwnedArray($user_id)
  {
    $group_array = array();
    foreach ($this->getEntityManager()
      ->createQuery('
        SELECT g.id, g.name FROM MuzichCoreBundle:Group g
        LEFT JOIN g.owner o 
        WHERE g.open = \'1\' OR o.id = :uid'
      )->setParameter('uid', $user_id)
      ->getArrayResult() as $group)
    {
      $group_array[$group['id']] = $group['name'];
    }
    return $group_array;
  }
  
  /**
   * Retourne tous les tags des elements postés
   * 
   * @return doctrine_collection
   */
  public function getElementsTags($group_id, $current_user_id)
  {
    return $this->getEntityManager()
      ->createQuery('
        SELECT t FROM MuzichCoreBundle:Tag t
        LEFT JOIN t.elements e
        WHERE e.group = :gid
        AND (t.tomoderate = \'FALSE\' OR t.tomoderate IS NULL
          OR t.privateids LIKE :uidt)
        ORDER BY t.name ASC'
      )
      ->setParameters(array('gid' => $group_id, 'uidt' => '%"'.$current_user_id.'"%'))
      ->getResult()
    ;
  }
  
}
  