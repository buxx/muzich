<?php

namespace Muzich\GroupBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Muzich\CoreBundle\Entity\Group;
use Muzich\CoreBundle\Form\Group\GroupForm;
use Symfony\Component\HttpFoundation\Request;
use Muzich\CoreBundle\Managers\GroupManager;

class DefaultController extends Controller
{
  
  protected function getGroupForm($group)
  {
    return $this->createForm(
      new GroupForm(), 
      $group
    );
  }
  
  /**
   * Page listant les groupes possédés par l'utilisateur. Comporte egallement
   * un formulaire pour ajouter un groupe.
   *
   * @Template()
   */
  public function myListAction()
  {
    $user = $this->getUser(true, array('join' => array(
      'groups_owned'
    )));
    
    $new_group = new Group();
    $form_new = $this->getGroupForm($new_group);
    
    return array(
      'groups'        => $user->getGroupsOwned(),
      'form_new'      => $form_new->createView(),
      'form_new_name' => $form_new->getName()
    );
  }
  
  /**
   * Procédure d'ajout d'un groupe
   *
   * @param Request $request
   * @return redirect|template
   */
  public function addAction(Request $request)
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
    
    $new_group = new Group();
    $new_group->setOwner($user);
    $form_new = $this->getGroupForm($new_group);
    
    $form_new->bindRequest($request);
    
    if ($form_new->isValid())
    {
      $factory = new GroupManager($new_group, $em, $this->container);
      $factory->proceedTags(json_decode($new_group->getTags()));
      
      $em->persist($new_group);
      $em->flush();
      
      $this->setFlash('success', 'group.create.success');
      return $this->redirect($this->generateUrl('groups_own_list'));
    }
    else
    {
      $user = $this->getUser(true, array('join' => array(
        'groups_owned'
      )));
      
      //$this->setFlash('error', 'group.create.failure');
      
      return $this->render(
        'MuzichGroupBundle:Default:myList.html.twig', 
         array(
           'groups'        => $user->getGroupsOwned(),
           'form_new'      => $form_new->createView(),
           'form_new_name' => $form_new->getName()
         )
      );
    }
  }
  
  /**
   * Modification d'un groupe
   *
   * @Template()
   * @param Request $request
   * @param string $slug
   * @return array
   */
  public function editAction(Request $request, $slug)
  {
    $user = $this->getUser();
    
    try {
      
      $group = $this->getDoctrine()
        ->getRepository('MuzichCoreBundle:Group')
        ->findOneBySlug($slug)
        ->getSingleResult()
      ;
      
    } catch (\Doctrine\ORM\NoResultException $e) {
        throw $this->createNotFoundException('Groupe introuvable.');
    }
    
    if ($group->getOwner()->getId() != $user->getId())
    {
      throw $this->createNotFoundException('Vous n\'ête pas le créateur de ce groupe.');
    }
    
    $group->setTagsToIds();
    $form = $this->getGroupForm($group);
    
    return array(
      'group'     => $group,
      'form'      => $form->createView()  ,
      'form_name' => $form->getName()      
    );
  }
  
  public function updateAction(Request $request, $slug)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $group = $this->findGroupWithSlug($slug);
    
    if ($group->getOwner()->getId() != $this->getUserId())
    {
      throw $this->createNotFoundException('Vous n\'ête pas le créateur de ce groupe.');
    }

    // Pour être compatible avec le formulaire, la collection de tags dois être
    // une collection d'id
    $group->setTagsToIds();
    $form = $this->getGroupForm($group);
    
    $form->bindRequest($request);
    
    if ($form->isValid())
    {
      $factory = new GroupManager($group, $em, $this->container);
      $factory->proceedTags($group->getTags());
      
      $em->persist($group);
      $em->flush();
      
      $this->setFlash('success', 'group.update.success');
      return $this->redirect($this->generateUrl('show_group', array('slug' => $group->getSlug())));
    }
    else
    {
      $this->setFlash('error', 'group.update.failure');
      
      return $this->render(
        'GroupBundle:Default:edit.html.twig', 
         array(
           'form_new'      => $form->createView(),
           'form_new_name' => $form_new->getName()
         )
      );
    }
  }
  
}
