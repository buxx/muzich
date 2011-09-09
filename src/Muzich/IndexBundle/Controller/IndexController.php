<?php

namespace Muzich\IndexBundle\Controller;

//use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Muzich\CoreBundle\Entity\Tag;
use Muzich\CoreBundle\Entity\UsersTagsFavorites;
use Muzich\CoreBundle\Entity\User;

class IndexController extends Controller
{
  /**
   *
   * @Template()
   */
  public function indexAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    
    $bux = $em
      ->getRepository('MuzichCoreBundle:User')
      ->findOneByUsername('bux')
    ;
    
    $tag_hardtek = $em
      ->getRepository('MuzichCoreBundle:Tag')
      ->findOneByName('Hardtek')
    ;
    
    if (!$tag_hardtek)
    {
      $tag_hardtek = new Tag();
      $tag_hardtek->setName('Hardtek');
      $em->persist($tag_hardtek);
    }
    
    $tag_tribe = $em
      ->getRepository('MuzichCoreBundle:Tag')
      ->findOneByName('Tribe')
    ;
    
    if (!$tag_tribe)
    {
      $tag_tribe = new Tag();
      $tag_tribe->setName('Tribe');
      $em->persist($tag_tribe);
    }
    
    //
    
    $user_tag_favorite_bux_hardtek = $em
      ->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findOneBy(array(
        'tag' => $tag_hardtek->getId(),
        'user' => $bux->getId()
      ))
    ;
    
    if (!$user_tag_favorite_bux_hardtek)
    {
      $user_tag_favorite_bux_hardtek = new UsersTagsFavorites();
      $user_tag_favorite_bux_hardtek->setTag($tag_hardtek);
      $user_tag_favorite_bux_hardtek->setUser($bux);
      $user_tag_favorite_bux_hardtek->setPosition(0);
      $em->persist($user_tag_favorite_bux_hardtek);
    }
    
    $user_tag_favorite_bux_tribe = $em
      ->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findOneBy(array(
        'tag' => $tag_tribe->getId(),
        'user' => $bux->getId()
      ))
    ;
    
    if (!$user_tag_favorite_bux_tribe)
    {
      $user_tag_favorite_bux_tribe = new UsersTagsFavorites();
      $user_tag_favorite_bux_tribe->setTag($tag_tribe);
      $user_tag_favorite_bux_tribe->setUser($bux);
      $user_tag_favorite_bux_tribe->setPosition(0);
      $em->persist($user_tag_favorite_bux_tribe);
    }
    
    
    $em->flush();
    
    //$bux = new User();
    //var_dump($bux->getTagsFavorites()->get(0)->getTag()->getName());
    
    foreach ($bux->getTagsFavorites() as $UserTagFavorite)
    {
      echo $UserTagFavorite->getTag()->getName().'<br />';
    }
    
    die();
        
    return array('bux' => $bux);
  }
}