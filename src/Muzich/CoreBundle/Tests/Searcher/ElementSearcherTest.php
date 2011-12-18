<?php

namespace Muzich\CoreBundle\Tests\Searcher;

use Muzich\CoreBundle\lib\UnitTest;
use Muzich\CoreBundle\Searcher\ElementSearcher;

class ElementSearcherTest extends UnitTest
{  
  public function testInit()
  {
    $es = new ElementSearcher();
    $es->init($ia = array(
        'network'   => ElementSearcher::NETWORK_PERSONAL, 
        'tags'      => array(1, 2, 6), 
        'count'     => 20, 
        'user_id'   => 185, 
        'group_id'  => null, 
        'favorite'  => false
    ));

    $this->assertEquals($ia, $es->getParams());
  }
  
  public function testUpdate()
  {
    $es = new ElementSearcher();
    $es->init($ia = array(
        'network'   => ElementSearcher::NETWORK_PERSONAL, 
        'tags'      => array(1, 2, 6), 
        'count'     => 20, 
        'user_id'   => 185, 
        'group_id'  => null, 
        'favorite'  => false
    ));
    $es->init($ua = array(
        'network'   => ElementSearcher::NETWORK_PUBLIC, 
        'tags'      => array(5, 8, 123), 
        'count'     => 21, 
        'user_id'   => 115, 
        'group_id'  => null, 
        'favorite'  => false
    ));

    $this->assertEquals($ua, $es->getParams());
  }
  
  public function testGetElements()
  {
    $r = $this->getDoctrine();
    $bux = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bux')
    ;
    
    /*
     * Première passe: On check que la recherche nous retourne bien les 
     * elements de jean
     */
    $jean = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('jean')
    ;
    $es = new ElementSearcher();
    $es->init(array(
      'user_id'   => $jean->getId(),
      'count'     => 20
    ));
    
    // On récupére avec un requte standart ce que devra retourner l'objet de
    // recherche
    $query_results = $r->getEntityManager()
      ->createQuery("SELECT e
      FROM MuzichCoreBundle:Element e
      WHERE e.owner = :suid
      ORDER BY e.created DESC, e.id DESC")
      ->setParameter('suid', $jean->getId())
      ->setMaxResults(20)
      ->getResult()
    ;
    
    // Les résultats de la recherche
    $searcher_results = $es->getElements($r, $bux->getId());
    
    // Maintenant on compare
    $this->assertEquals($query_results, $searcher_results);
    
    /*
     * Contrôle de sortie des favoris d'un user (paul)
     */
    
    $paul = $r->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('paul')
    ;
    $es = new ElementSearcher();
    $es->init(array(
      'user_id'   => $paul->getId(),
      'favorite'  => true,
      'count'     => 20
    ));
    
    $query_results = $r->getEntityManager()
      ->createQuery("SELECT e
      FROM MuzichCoreBundle:Element e
      JOIN e.elements_favorites fav2
      WHERE fav2.user = :fuid
      ORDER BY e.created DESC, e.id DESC")
      ->setParameter('fuid', $paul->getId())
      ->setMaxResults(20)
      ->getResult()
    ;
    
    // Les résultats de la recherche
    $searcher_results = $es->getElements($r, $paul->getId());
    
    // Maintenant on compare
    $this->assertEquals($query_results, $searcher_results);
    
    /*
     * Contrôle de sortie d'un affichage public, avec tags
     */
    
    $hardtek = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
    $tribe = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
    
    $es = new ElementSearcher();
    $es->init(array(
      'network'   => ElementSearcher::NETWORK_PUBLIC,
      'tags'      => array(
        $hardtek->getId(),
        $tribe->getId()
      ),
      'count'     => 20
    ));
        
    $query_results = $r->getEntityManager()
      ->createQuery("SELECT e
      FROM MuzichCoreBundle:Element e 
      LEFT JOIN e.tags t 
      WHERE (t.id = :tidHardtek OR t.id = :tidTribe)
      ORDER BY e.created DESC, e.id DESC")
      ->setParameters(array(
        'tidHardtek' => $hardtek->getId(),
        'tidTribe'   => $tribe->getId()
      ))
      ->setMaxResults(20)
      ->getResult()
    ;
    
    // Les résultats de la recherche
    $searcher_results = $es->getElements($r, $paul->getId());
    
    // Maintenant on compare
    $this->assertEquals($query_results, $searcher_results);
    
//    /*
//     * Contrôle de sortie d'un affichage réseau personel, avec tags
//     */
//    
//    $hardtek = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek');
//    $tribe = $r->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe');
//    
//    $es = new ElementSearcher();
//    $es->init(array(
//      'network'   => ElementSearcher::NETWORK_PERSONAL,
//      'tags'      => array(
//        $hardtek->getId(),
//        $tribe->getId()
//      ),
//      'count'     => 20
//    ));
//    
//    $query_results = $r->getEntityManager()
//      ->createQuery("SELECT e, et, t2, eu, g
//      FROM MuzichCoreBundle:Element e 
//      LEFT JOIN e.group g 
//      LEFT JOIN e.type et 
//      LEFT JOIN e.tags t 
//      LEFT JOIN e.tags t2 
//      JOIN e.owner eu  LEFT JOIN eu.followers_users f LEFT JOIN g.followers gf
//      WHERE (t.id = :tidHardtek OR t.id = :tidTribe)
//       AND (f.follower = :userid OR gf.follower = :useridg)
//      ORDER BY e.created DESC, e.id DESC")
//      ->setParameters(array(
//        'tidHardtek' => $hardtek->getId(),
//        'tidTribe'   => $tribe->getId(),
//        'userid'     => $bux->getId(),
//        'useridg'    => $bux->getId()
//      ))
//      ->setMaxResults(20)
//      ->getResult()
//    ;
//    
//    // Les résultats de la recherche
//    $searcher_results = $es->getElements($r, $paul->getId());
//    
//    // Maintenant on compare
//    $this->assertEquals($query_results, $searcher_results);
  }
}