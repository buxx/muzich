<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Muzich\CoreBundle\Entity\User;

class PlaylistRepository extends EntityRepository
{
  
  protected function getPlaylistsQueryBuilder()
  {
    return $this->getEntityManager()->createQueryBuilder()
      ->select('p')
      ->from('MuzichCoreBundle:Playlist', 'p')
    ;
  }
  
  public function getPublicPlaylistsQueryBuilder()
  {
    return $this->getPlaylistsQueryBuilder()
      ->andWhere('p.public = 1')
    ;
  }
  
  public function getUserPublicPlaylistsQueryBuilder(User $user)
  {
    return $this->getPublicPlaylistsQueryBuilder()
      ->andWhere('p.owner = :owner_id')
      ->setParameter('owner_id', $user->getId())
    ;
  }
  
  public function getUserPublicPlaylistsOrOwnedQueryBuilder(User $viewed_user, User $current_user = null)
  {
    if (!$current_user)
    {
      return $this->getUserPublicPlaylistsQueryBuilder($viewed_user);
    }
    
    if ($viewed_user->getId() != $current_user->getId())
    {
      return $this->getUserPublicPlaylistsQueryBuilder($viewed_user);
    }
    
    return $this->getPlaylistsQueryBuilder()
      ->where('p.owner = :owner_id')
      ->setParameter('owner_id', $current_user->getId())
    ;
  }
  
  public function findOnePlaylistOwned($playlist_id, User $user)
  {
    return $this->getPlaylistsQueryBuilder()
      ->andWhere('p.owner = :owner_id AND p.id = :playlist_id')
      ->setParameters(array(
        'owner_id'    => $user->getId(),
        'playlist_id' => $playlist_id
      ))
    ;
  }
  
  public function findOnePlaylistOwnedOrPublic($playlist_id, User $user = null)
  {
    $query_builder = $this->getPlaylistsQueryBuilder()
      ->andWhere('p.id = :playlist_id');
      
    if ($user)
    {
      $query_builder->andWhere('p.public = 1 OR p.owner = :owner_id');
      $query_builder->setParameters(array(
        'playlist_id' => $playlist_id,
        'owner_id'    => $user->getId()
      ));
    }
    else
    {
      $query_builder->andWhere('p.public = 1');
      $query_builder->setParameters(array(
        'playlist_id' => $playlist_id
      ));
    }
    
    return $query_builder;
  }
  
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
  