<?php

namespace Muzich\GroupBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Muzich\CoreBundle\Entity\Group;
use Muzich\CoreBundle\Form\Group\GroupForm;
use Symfony\Component\HttpFoundation\Request;
use Muzich\CoreBundle\Managers\GroupManager;
use Muzich\CoreBundle\Security\Context as SecurityContext;

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
      'groups'         => $user->getGroupsOwned(),
      'form_new'       => $form_new->createView(),
      'form_new_name'  => $form_new->getName(),
      'open_add_group' => false
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
    
if (($non_condition = $this->userHaveNonConditionToMakeAction(SecurityContext::ACTION_GROUP_ADD)) !== false)
    {
      if ($request->isXmlHttpRequest())
      {
        return $this->jsonResponseError($non_condition);
      }
      
      throw $this->createNotFoundException();
    }
    
    $em = $this->getDoctrine()->getEntityManager();
    
    $new_group = new Group();
    $new_group->setOwner($user);
    $form_new = $this->getGroupForm($new_group);
    
    $form_new->bind($request);
    
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
           'groups'         => $user->getGroupsOwned(),
           'form_new'       => $form_new->createView(),
           'form_new_name'  => $form_new->getName(),
           'open_add_group' => true
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
        ->getEntityManager()->createQuery('SELECT g, t FROM MuzichCoreBundle:Group g
        LEFT JOIN g.tags t WHERE g.slug = :gslug')
        ->setParameter('gslug', $slug)
        ->getSingleResult()
      ;
      
    } catch (\Doctrine\ORM\NoResultException $e) {
        return $this->createNotFoundException();
    }
    
    if ($group->getOwner()->getId() != $user->getId())
    {
      return $this->createNotFoundException();
    }
    
    $prompt_tags = array();
    foreach ($group->getTags() as $tag)
    {
      $prompt_tags[$tag->getTag()->getId()] = $tag->getTag()->getName();
    }
    
    $group->setTags($group->getTagsIdsJson());
    $form = $this->getGroupForm($group);
    
    return array(
      'group'       => $group,
      'form'        => $form->createView(),
      'form_name'   => 'group',
      'search_tags' => $prompt_tags
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

    $prompt_tags = array();
    foreach ($group->getTags() as $tag)
    {
      $prompt_tags[$tag->getTag()->getId()] = $tag->getTag()->getName();
    }
    
    // Pour être compatible avec le formulaire
    $group->setTags($group->getTagsIdsJson());
    $form = $this->getGroupForm($group);
    
    $form->bind($request);
    
    if ($form->isValid())
    {
      $factory = new GroupManager($group, $em, $this->container);
      $factory->proceedTags(json_decode($group->getTags()));
      
      $em->persist($group);
      $em->flush();
      
      $this->setFlash('success', 'group.update.success');
      return $this->redirect($this->generateUrl('show_group', array('slug' => $group->getSlug())));
    }
    else
    {      
      return $this->render(
        'MuzichGroupBundle:Default:edit.html.twig', 
         array(
          'group'       => $group,
          'form'        => $form->createView(),
          'form_name'   => 'group',
          'search_tags' => $prompt_tags
         )
      );
    }
  }
  
  public function deleteAction($group_id, $token)
  {
    $user = $this->getUser();
    if ($user->getPersonalHash($group_id) != $token)
    {
      throw $this->createNotFoundException('Accès non autorisé.');
    }
    
    $group = $this->findGroupWithId($group_id);
    
    if ($user->getId() != $group->getOwner()->getId())
    {
      throw $this->createNotFoundException('Accès non autorisé.');
    }
    
    $em = $this->getDoctrine()->getEntityManager();
    
    // Il faudra le faire avec doctrine:
    $elements = $this->getDoctrine()->getRepository('MuzichCoreBundle:Element')
      ->findBy(array('group' => $group->getId()))
    ;
    
    foreach ($elements as $element)
    {
      $element->setGroup(null);
      $em->persist($element);
    }
      
    $em->remove($group);
    $em->flush();
    
    return $this->redirect($this->container->get('request')->headers->get('referer'));
  }
  
}