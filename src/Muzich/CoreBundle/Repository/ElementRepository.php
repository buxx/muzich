<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Muzich\CoreBundle\Searcher\ElementSearcher;
use Symfony\Component\Config\Definition\Exception\Exception;
use Muzich\CoreBundle\Searcher\ElementSearcherQueryBuilder;
use Doctrine\ORM\Mapping as ORM;

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
        SELECT e FROM MuzichCoreBundle:Element e 
        WHERE e.private = 0
        ORDER BY e.name ASC'
      )
      ->getResult()
    ;
  }
  
  
  public function findBySearch(ElementSearcher $searcher, $user_id, $exec_type = 'execute', $params = array())
  {
    // Cas exeptionel: Si on demande les "nouveaux" éléments alors que l'on impose
    // la liste d'élément consultable. Dans ce cas c'est une demande superflu:
    // il ne peut y avoir de nouveaux élements puisque la liste des éléments est déjà fixé.
    if ($searcher->isSearchingNew() && $searcher->hasIds())
    {
      return $query = $this->getEntityManager()
        ->createQuery("SELECT e FROM MuzichCoreBundle:Element e WHERE 1 = 2")
      ;
    }
    
    $esqb = new ElementSearcherQueryBuilder($this->getEntityManager(), $searcher, $user_id, $params);
    
    // Si on demande une comptabilisation, on retourne juste la requete qui selectionne les ids
    if ($exec_type == 'count')
    {
      return $esqb->getIdsQuery(true);
    }
    
    // Sinon on retourne la requete sur les éléments
    return $esqb->getElementsQuery();
  }
  
  protected function getSelectElementForSearchQuery($params_select, $user_id, $ids, $id_limit = null, $count_limit = null, $ids_display = null)
  {
    $where = "";
    if ($id_limit)
    {
      $where = "AND e.id < :id_limit";
      $params_select['id_limit'] = $id_limit;
    }
    
    $select = '';
    $left_join = '';
    if ($ids_display)
    {
      $select = ', tp, tpu, tpt';
      $left_join = 'LEFT JOIN e.tags_propositions tp LEFT JOIN tp.user tpu LEFT JOIN tp.tags tpt';
    }
      
    // C'est la requête qui récupérera les données element avec ses jointures.
    $query_select = "SELECT e, t, o, g, fav $select
      FROM MuzichCoreBundle:Element e 
      LEFT JOIN e.group g 
      LEFT JOIN e.tags t WITH (t.tomoderate = 'FALSE' OR t.tomoderate IS NULL
        OR t.privateids LIKE :uidt)
      LEFT JOIN e.elements_favorites fav WITH fav.user = :uid
      $left_join
      JOIN e.owner o
      WHERE e.id IN (:ids) $where
      AND WHERE e.private = 0
      ORDER BY e.created DESC, e.id DESC"
    ;

    $params_select['ids'] = $ids;
    $params_select['uidt'] = '%"'.$user_id.'"%';
    $query = $this->getEntityManager()
      ->createQuery($query_select)
      ->setParameters($params_select)
    ;
    
    if ($count_limit)
    {
      $query->setMaxResults($count_limit);
    }

    return $query;
  }
  
  /**
   * Retourne une requete selectionnant les Elements en fonction du
   * propriétaire.
   * 
   * @param int $user_id
   * @param int $limit
   * @return type Doctrine\ORM\Query
   */
  public function findByUser($user_id, $limit)
  {
    return $this->getEntityManager()
      ->createQuery('
        SELECT e, u, g, t FROM MuzichCoreBundle:Element e
        JOIN e.owner u
        JOIN e.group g
        JOIN e.tags t
        WHERE u.id = :uid
        AND WHERE e.private = 0
        ORDER BY e.created DESC'
      )
      ->setParameter('uid', $user_id)
      ->setMaxResults($limit)
    ;
  }
  
  /**
   * Retourne une requete selectionnant les Elements en fonction du
   * groupe.
   * 
   * @param int $user_id
   * @param int $limit
   * @return type Doctrine\ORM\Query
   */
  public function findByGroup($group_id, $limit)
  {
    return $this->getEntityManager()
      ->createQuery('
        SELECT e, u, g, t FROM MuzichCoreBundle:Element e
        JOIN e.owner u
        JOIN e.group g
        JOIN e.tags t
        WHERE g.id = :gid
        AND WHERE e.private = 0
        ORDER BY e.created DESC'
      )
      ->setParameter('gid', $group_id)
      ->setMaxResults($limit)
    ;
  }  
  
  public function countToModerate()
  {
    return $this->getEntityManager()
      ->createQuery("SELECT COUNT(e.id) FROM MuzichCoreBundle:Element e
        WHERE e.count_report IS NOT NULL"
      )->getSingleScalarResult()
    ;
  }
  
  public function getToModerate()
  {
    return $this->getEntityManager()
      ->createQuery("SELECT e FROM MuzichCoreBundle:Element e
        WHERE e.count_report  IS NOT NULL"
      )->getResult()
    ;
  }  
  
  public function countForCommentToModerate()
  {
    return $this->getEntityManager()
      ->createQuery("SELECT COUNT(e.id) FROM MuzichCoreBundle:Element e
        WHERE e.count_comment_report IS NOT NULL AND e.count_comment_report != 0"
      )->getSingleScalarResult()
    ;
  }
  
  public function getForCommentToModerate()
  {
    return $this->getEntityManager()
      ->createQuery("SELECT e FROM MuzichCoreBundle:Element e
        WHERE e.count_comment_report IS NOT NULL AND e.count_comment_report != 0"
      )->getResult()
    ;
  }
  
  /**
   * WARNING: Seulement compatibel avec MySQL !!
   */
  public function getElementsWithIdsOrderingQueryBuilder($element_ids, $show_privates = false, $user_id = true)
  {
    $doctrineConfig = $this->getEntityManager()->getConfiguration();
    $doctrineConfig->addCustomStringFunction('FIELD', 'Muzich\CoreBundle\DoctrineExtensions\Query\Mysql\Field');
    
    if (count($element_ids))
    {
      $qb =  $this->getEntityManager()->createQueryBuilder()
        ->select('e, field(e.id, ' . implode(', ', $element_ids) . ') as HIDDEN field')
        ->from('MuzichCoreBundle:Element', 'e')
        ->where('e.id IN (:element_ids)')
        ->setParameter('element_ids', $element_ids)
        ->orderBy('field')
      ;
      
      if (!$show_privates)
      {
        $qb->andWhere('e.private = 0');
      }
      else if ($user_id)
      {
        $qb->andWhere('(e.private = 0 OR (e.private = 1 AND e.owner = :owner_id))');
        $qb->setParameter('owner_id', $user_id);
      }
      
      return $qb;
    }
    
    return $this->getEntityManager()->createQueryBuilder()
      ->select('e')
      ->from('MuzichCoreBundle:Element', 'e')
      ->where('1 = 2')
    ;
  }
  
}