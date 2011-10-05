<?php

namespace Muzich\CoreBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Muzich\CoreBundle\Entity\FollowUser;
use Muzich\CoreBundle\Entity\FollowGroup;
//use Doctrine\ORM\Query;
use Muzich\CoreBundle\Form\Element\ElementAddForm;
use Muzich\CoreBundle\ElementFactory\ElementManager;
use Muzich\CoreBundle\Entity\Element;

class CoreController extends Controller
{
  
  /**
   * 
   * @param string $type
   * @param int $id
   * @param string $salt 
   */
  public function followAction($type, $id, $token)
  {
    $user = $this->getUser();
    // Vérifications préléminaires
    if ($user->getPersonalHash() != $token || !in_array($type, array('user', 'group')) || !is_numeric($id))
    {
      throw $this->createNotFoundException();
    }
    
    $em = $this->getDoctrine()->getEntityManager();
    $Follow = $em
      ->getRepository('MuzichCoreBundle:Follow' . ucfirst($type))
      ->findOneBy(
        array(
          'follower' => $user->getId(),
          ($type == 'user') ? 'followed' : 'group' => $id
        )
      )
    ;
    
    if ($Follow)
    {
      // L'utilisateur suis déjà, on doit détruire l'entité
      $em->remove($Follow);
      $em->flush();
    }
    else
    {
      $followed = $em->getRepository('MuzichCoreBundle:'.ucfirst($type))->find($id);

      if (!$followed) {
          throw $this->createNotFoundException('No '.$type.' found for id '.$id);
      }
      
      
      if ($type == 'user') { $Follow = new FollowUser(); }
      else { $Follow = new FollowGroup(); }
      $Follow->setFollower($user);
      if ($type == 'user') { $Follow->setFollowed($followed); }
      else { $Follow->setGroup($followed); }
      
      
      $em->persist($Follow);
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
  
  public function elementAddAction()
  {
    $user = $this->getUser();
    $em = $this->getDoctrine()->getEntityManager();
    
    $form = $this->createForm(
      new ElementAddForm(),
      array(),
      array('tags' => $this->getTagsArray())
    );
    
    if ($this->getRequest()->getMethod() == 'POST')
    {
      $form->bindRequest($this->getRequest());
      if ($form->isValid())
      {
        $data = $form->getData();
        $element = new Element();
        
        $factory = new ElementManager($element, $em, $this->container);
        $factory->proceedFill($data, $user);
        
        $em->persist($element);
        $em->flush();
      }
      
    }
    
    if ($this->getRequest()->isXmlHttpRequest())
    {
      
    }
    else
    {
      return $this->redirect($this->generateUrl('home'));
    }
    
  }
  
//  protected function proceedElement(Element $element)
//  {
//    $factory = new ElementFactory();
//    $factory->proceed($element, $form->getData());
//  }
  
}
