<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class UserRepository extends EntityRepository
{
  
  /**
   * Retourne une Query construite selon les paramètres fournis.
   * 
   * @param int $user_id
   * @param array $join
   * @return  Doctrine\ORM\Query
   */
  public function findOneById($user_id, $join_list)
  {
    $select = 'u';
    $join = '';
    $where = '';
    $parameters = array('uid' => $user_id);
    
    if (in_array('followeds_users', $join_list))
    {
      $select .= ', fdu, fdu_u';
      $join   .= ' JOIN u.followeds_users fdu JOIN fdu.followed fdu_u';
    }
    
    if (in_array('followers_users', $join_list))
    {
      $select .= ', fru, fru_u';
      $join   .= ' JOIN u.followers_users fru JOIN fru.follower fru_u';
    }
    
    if (in_array('followeds_groups', $join_list))
    {
      $select .= ', fdg, fdg_g';
      $join   .= ' JOIN u.followed_groups fdg JOIN fdg.group fdg_g';
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
    
//    if (array_key_exists('followed_user_id', $join_list))
//    {
//      $select .= ', fu, fu_u';
//      $join   .= ' LEFT JOIN u.followeds_users fu WITH fu.followed = :fuid LEFT JOIN fu.followed fu_u';
//      $parameters = array_merge($parameters, array(
//        'fuid' => $join_list['followed_user_id']
//      ));
//    }
    
    return $this->getEntityManager()
      ->createQuery("
        SELECT $select FROM MuzichCoreBundle:User u
        $join
        WHERE u.id = :uid
        $where
      ")
      ->setParameters($parameters)
    ;
  }
  
  /**
   * Retourne les tag id préférés de l'user.
   * 
   * @param int $user_id
   * @param int $limit
   * @return array 
   */
  public function getTagIdsFavorites($user_id, $limit = null)
  {
    $tag_ids = array();
    foreach ($this->getEntityManager()
      ->createQuery('
        SELECT t.id FROM MuzichCoreBundle:Tag t 
        JOIN t.users_favorites uf
        JOIN uf.user u
        WHERE u.id = :uid
        ORDER BY uf.position ASC'
      )
      ->setParameter('uid', $user_id)
      ->setMaxResults($limit)
      ->getArrayResult() as $tag)
    {
      $tag_ids[] = $tag['id'];
    }
    return $tag_ids;
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
  
}
  