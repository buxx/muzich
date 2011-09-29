<?php

namespace Muzich\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
  
  /**
   * Retourne les tag id préférés de l'user.
   * 
   * @param int $user_id
   * @param int $limit
   * @return array 
   */
  public function getTagIdsFavorites($user_id, $limit)
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
  
}
  