<?php

namespace Muzich\MynetworkBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Muzich\CoreBundle\Searcher\UserAndGroupSearcher;

class MynetworkController extends Controller
{
  
  /**
   * 
   * @Template()
   */
  public function indexAction()
  {
    $user = $this->getUser(true, array('join' => array(
      'followeds_users', 'followers_users', 'followeds_groups'
    )));
    
    $followeds_users = $user->getFollowedsUsers();
    $followeds_groups = $user->getFollowedGroups();
    $followers_users = $user->getFollowersUsers();
    
    return array(
      'followeds_users' => $followeds_users,
      'followeds_groups' => $followeds_groups,
      'followers_users' => $followers_users
    );
  }
  
  /**
   * Action qui affiche la page de recherche, et efectue la recherche
   * d'utilisateurs et de groupes.
   * 
   * @Template()
   */
  public function searchAction()
  {
    $request = $this->getRequest();
    $search = new UserAndGroupSearcher();
    $results = array('users' => null, 'groups' => null);
    
    $search_form = $this->createFormBuilder($search)
      ->add('string', 'text')
    ->getForm();
    
    // Si l'utilisateur effectue la recherche
    if ($request->getMethod() == 'POST')
    {
      $search_form->bindRequest($request);
      if ($search_form->isValid())
      {
        $results = $this->doSearchAction($search);
      }
    }
    
    if ($this->getRequest()->isXmlHttpRequest())
    {
      
    }
    else
    {
      return array(
        'search_form' => $search_form->createView(),
        'results'     => $results,
        'search_done' => $search->getString() ? true : false
      );
    }
  }
  
  /**
   * Retourne le résultat de recherche sur les users et groupes.
   * 
   * @param string $search
   * @return array 
   */
  protected function doSearchAction($search)
  {
    $string = str_replace('%', '#', $search->getString());
    
    $users = $this->getDoctrine()
      ->getRepository('MuzichCoreBundle:User')
      ->findByString($string)
      ->execute()
    ;
    
    $groups = $this->getDoctrine()
      ->getRepository('MuzichCoreBundle:Group')
      ->findByString($string)
      ->execute()
    ;
    
    return array(
      'users'  => $users,
      'groups' => $groups
    );
  }
  
}