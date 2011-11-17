<?php

namespace Muzich\FavoriteBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Muzich\CoreBundle\Entity\UsersElementsFavorites;
use Muzich\CoreBundle\Searcher\ElementSearcher;
//use Muzich\CoreBundle\Entity\Group;
//use Muzich\CoreBundle\Form\Group\GroupForm;
//use Symfony\Component\HttpFoundation\Request;
//use Muzich\CoreBundle\Managers\GroupManager;

class FavoriteController extends Controller
{
  
  /**
   * Ajoute comme favoris l'element en id
   * 
   * @param int $id
   * @param string $token 
   */
  public function addAction($id, $token)
  {
    $user = $this->getUser();
    $em = $this->getDoctrine()->getEntityManager();
    
    if ($user->getPersonalHash() != $token || !is_numeric($id)
      || !($element = $em->getRepository('MuzichCoreBundle:Element')->findOneById($id))
    )
    {
      throw $this->createNotFoundException();
    }
    
    if (!$em->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->findOneBy(array(
        'user'    => $user->getId(),
        'element' => $id
      )))
    {
      $favorite = new UsersElementsFavorites();
      $favorite->setUser($user);
      $favorite->setElement($element);
      $em->persist($favorite);
      $em->flush();
    }
    
    if ($this->getRequest()->isXmlHttpRequest())
    {
      
    }
    else
    {
      return $this->redirect($this->container->get('request')->headers->get('referer'));
    }
  }
  
  /**
   * Page affichant les elements favoris de l'utilisateur
   * 
   * @Template()
   */
  public function myListAction()
  {
    $search_object = $this->createSearchObject(array(
      'user_id'  => $this->getUserId(),
      'favorite' => true
    ));
    
    return array(
        'search_object' => $search_object
    );
  }
  
  /**
   * Affichage des elements favoris d'un utilisateur particulier.
   * 
   * @param type $slug 
   * @Template()
   */
  public function userListAction($slug)
  {
    $viewed_user = $this->findUserWithSlug($slug);
    
    $search_object = $this->createSearchObject(array(
      'user_id'  => $viewed_user->getId(),
      'favorite' => true
    ));
    
    return array(
      'viewed_user' => $viewed_user,
      'search_object' => $search_object
    );
  }
  
}
