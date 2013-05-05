<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;

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
    $parameters = array('gid' => $group_id);
    $current_user_sql = '';
    if ($current_user_id)
    {
      $parameters['uidt'] = '%"'.$current_user_id.'"%';
      $current_user_sql = 'OR t.privateids LIKE :uidt';
    }
    
    return $this->getEntityManager()
      ->createQuery('
        SELECT t FROM MuzichCoreBundle:Tag t
        LEFT JOIN t.elements e
        WHERE e.group = :gid
        AND (t.tomoderate = \'FALSE\' OR t.tomoderate IS NULL
          '.$current_user_sql.')
        ORDER BY t.name ASC'
      )
      ->setParameters($parameters)
      ->getResult()
    ;
  }
 
  public function getElementIdsOwned($group_id)
  {
    $ids = array();
    foreach ($this->getEntityManager()
      ->createQuery('SELECT e.id FROM MuzichCoreBundle:Element e
        WHERE e.group = :gid')
      ->setParameter('gid', $group_id)
      ->getScalarResult() as $row)
    {
      $ids[] = $row['id'];
    }
    return $ids;
  }
  
  public function countFollowers($group_id)
  {
    $data = $this->getEntityManager()
      ->createQuery('SELECT COUNT(f) FROM MuzichCoreBundle:FollowGroup f
        WHERE f.group = :gid')
      ->setParameter('gid', $group_id)
      ->getScalarResult()
    ;
    
    if (count($data))
    {
      if (count($data[0]))
      {
        return $data[0][1];
      }
    }
    
    return 0;
  }
  
}
  