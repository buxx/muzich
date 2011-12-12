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
    
    /**
     * Bug lors des tests: L'user n'est pas 'lié' a celui en base par doctrine.
     * Docrine le voit si on faire une requete directe.
     */
    if ($this->container->getParameter('env') == 'test')
    {
      $user = $this->getDoctrine()->getRepository('MuzichCoreBundle:User')->findOneById(
        $this->container->get('security.context')->getToken()->getUser()->getId(),
        array()
      )->getSingleResult();
    }
    
    $em = $this->getDoctrine()->getEntityManager();
    
    if ($user->getPersonalHash() != $token || !is_numeric($id)
      || !($element = $em->getRepository('MuzichCoreBundle:Element')->findOneById($id))
    )
    {
      throw $this->createNotFoundException();
    }

    // Si l'élément n'est pas déjà en favoris
    if (!$em->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->findOneBy(array(
        'user'    => $user->getId(),
        'element' => $id
      )))
    {
      // On créer un objet 
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
   * Retire comme favoris l'element en id
   * 
   * @param int $id
   * @param string $token 
   */
  public function removeAction($id, $token)
  {
    $user = $this->getUser();
    $em = $this->getDoctrine()->getEntityManager();
    
    if ($user->getPersonalHash() != $token || !is_numeric($id)
      || !($element = $em->getRepository('MuzichCoreBundle:Element')->findOneById($id))
    )
    {
      throw $this->createNotFoundException();
    }

    // Si l'élément est déjà en favoris, ce qui est cencé être le cas
    if (($fav = $em->getRepository('MuzichCoreBundle:UsersElementsFavorites')
      ->findOneBy(array(
        'user'    => $user->getId(),
        'element' => $id
      ))))
    {
      $em->remove($fav);
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
      'user'     => $this->getUser(),
      'elements' => $search_object->getElements($this->getDoctrine(), $this->getUserId())
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
      'user'        => $this->getUser(),
      'viewed_user' => $viewed_user,
      'elements'    => $search_object->getElements($this->getDoctrine(), $this->getUserId())
    );
  }
  
}
