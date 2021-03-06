<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Mapping as ORM;

class UserRepository extends EntityRepository
{
  
  /**
   * Retourne une Query construite selon les paramètres fournis.
   * 
   * @param int $user_id
   * @param array $join
   * @return  Doctrine\ORM\Query
   */
  public function findOneById($user_id, $join_list = array())
  {
    $select = 'u';
    $join = '';
    $where = '';
    $order = '';
    $parameters = array('uid' => $user_id);
    
    if (in_array('followeds_users', $join_list))
    {
      $select .= ', fdu, fdu_u';
      $join   .= ' LEFT JOIN u.followeds_users fdu LEFT JOIN fdu.followed fdu_u';
      $order = $this->updateOrderBy($order, 'fdu_u.username ASC');
    }
    
    if (in_array('followers_users', $join_list))
    {
      $select .= ', fru, fru_u';
      $join   .= ' LEFT JOIN u.followers_users fru LEFT JOIN fru.follower fru_u';
      $order = $this->updateOrderBy($order, 'fru_u.username ASC');
    }
    
    if (in_array('followeds_groups', $join_list))
    {
      $select .= ', fdg, fdg_g';
      $join   .= ' LEFT JOIN u.followed_groups fdg LEFT JOIN fdg.group fdg_g';
      $order = $this->updateOrderBy($order, 'fdg_g.name ASC');
    }
    
    if (in_array('favorites_tags', $join_list))
    {
      $select .= ', tf, tf_t';
      $join   .= ' LEFT JOIN u.tags_favorites tf LEFT JOIN tf.tag tf_t';
    }
    
    if (in_array('groups_owned', $join_list))
    {
      $select .= ', og';
      $join   .= ' LEFT JOIN u.groups_owned og';
    }
    
    if (in_array('groups_owned_groups_tags', $join_list))
    {
      $select .= ', og, ogt';
      $join   .= ' LEFT JOIN u.groups_owned og LEFT JOIN og.tags ogt';
    }
    
    return $this->getEntityManager()
      ->createQuery("
        SELECT $select FROM MuzichCoreBundle:User u
        $join
        WHERE u.id = :uid
        $where
        $order
      ")
      ->setParameters($parameters)
    ;
  }
  
  protected function updateOrderBy($orderBy, $add)
  {
    if (strpos($orderBy, 'ORDER BY') === false)
    {
      $orderBy .= 'ORDER BY ';
    }
    
    if ($orderBy !== 'ORDER BY ')
    {
      $orderBy .= ', ';
    }
    
    return $orderBy .= $add;
  }
  
  /**
   * Retourne les tag id préférés de l'user.
   * 
   * @param int $user_id
   * @param int $limit
   * @return array 
   */
  public function getTagsFavorites($user_id, $limit = null)
  {
    $tags = array();
    foreach ($this->getEntityManager()
      ->createQuery('
        SELECT t.id, t.name FROM MuzichCoreBundle:Tag t 
        JOIN t.users_favorites uf
        JOIN uf.user u
        WHERE u.id = :uid
        ORDER BY uf.position ASC'
      )
      ->setParameter('uid', $user_id)
      ->setMaxResults($limit)
      ->getArrayResult() as $tag)
    {
      $tags[$tag['id']] = $tag['name'];
    }
    return $tags;
  }
  
  /**
   * Retourne la requete selectionnant les user correspondant a une 
   * chaine de caractère
   * La recherche est effectué sur le username.
   * 
   * @param type $string
   * @return Doctrine\ORM\Query
   */
  public function findByString($string)
  {
    return $this->getEntityManager()
      ->createQuery("
        SELECT u FROM MuzichCoreBundle:User u
        WHERE UPPER(u.username) LIKE :str
        OR UPPER(u.usernameCanonical) LIKE :str
        ORDER BY u.username ASC"
      )
      ->setParameters(array(
        'str' => '%'.strtoupper(trim($string)).'%'
      ))
    ;
  }
  
  /**
   * Retourne une Query sleectionnant un User par son slug
   * 
   * @param type string 
   * @return Doctrine\ORM\Query
   */
  public function findOneBySlug($slug)
  {
    return $this->getEntityManager()
      ->createQuery("
        SELECT u FROM MuzichCoreBundle:User u
        WHERE u.slug = :str
      ")
      ->setParameters(array(
        'str' => $slug
      ))
    ;
  }

  /**
   * Retourne vrai si follower suis followed
   *
   * @param int $follower_id
   * @param int $followed_id
   * @return boolean
   */
  public function isFollowingUser($follower_id, $followed_id)
  {
    $result = $this->getEntityManager()
      ->createQuery("
        SELECT COUNT(fu.id) FROM MuzichCoreBundle:FollowUser fu
        WHERE fu.follower = :frid AND fu.followed = :fdid
      ")
      ->setParameters(array(
        'frid' => $follower_id,
        'fdid' => $followed_id
      ))
      ->getSingleResult(Query::HYDRATE_ARRAY)
    ;
    
    return $result[1];
  }

  /**
   * Retourne vrai si follower suis le groupe
   *
   * @param int $follower_id
   * @param int $group_id
   * @return boolean
   */
  public function isFollowingGroup($follower_id, $group_id)
  {
    $result = $this->getEntityManager()
      ->createQuery("
        SELECT COUNT(fg.id) FROM MuzichCoreBundle:FollowGroup fg
        WHERE fg.follower = :frid AND fg.group = :fdgid
      ")
      ->setParameters(array(
        'frid' => $follower_id,
        'fdgid' => $group_id
      ))
      ->getSingleResult(Query::HYDRATE_ARRAY)
    ;
    
    return $result[1];
  }
  
  /**
   * Retourne tous les tags des elements postés
   * 
   * @return doctrine_collection
   */
  public function getElementsTags($user_id, $current_user_id)
  {
    $parameters = array('uid' => $user_id);
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
        WHERE e.owner = :uid
        AND (t.tomoderate = \'FALSE\' OR t.tomoderate IS NULL
          '.$current_user_sql.')
        ORDER BY t.name ASC'
      )
      ->setParameters($parameters)
      ->getResult()
    ;
  }
 
  public function getElementIdsOwned($user_id)
  {
    $ids = array();
    foreach ($this->getEntityManager()
      ->createQuery('SELECT e.id FROM MuzichCoreBundle:Element e
        WHERE e.owner = :uid')
      ->setParameter('uid', $user_id)
      ->getScalarResult() as $row)
    {
      $ids[] = $row['id'];
    }
    return $ids;
  }
  
  public function countFollowers($user_id)
  {
    $data = $this->getEntityManager()
      ->createQuery('SELECT COUNT(f) FROM MuzichCoreBundle:FollowUser f
        WHERE f.followed = :uid')
      ->setParameter('uid', $user_id)
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
  
  /**
   * Retourne les objets utilisateurs correspondant aux ids transmis.
   *
   * @param array $ids
   * @return Collection 
   */
  public function getUsersWithIds($ids)
  {
    return $this->getEntityManager()
      ->createQuery('SELECT u FROM MuzichCoreBundle:User u'
        .' WHERE u.id IN (:uids)')
      ->setParameter('uids', $ids)
      ->getResult()
    ;
  }
  
  public function countUsers()
  {
    return $this->getEntityManager()
      ->createQuery("SELECT COUNT(u.id) FROM MuzichCoreBundle:User u")
      ->getSingleScalarResult()
    ;
  }
  
}  