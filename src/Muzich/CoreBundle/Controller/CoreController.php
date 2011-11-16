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
use Symfony\Component\HttpFoundation\RedirectResponse;

class CoreController extends Controller
{
  
  public function changeLanguageAction($language, $redirect)
  {
    if($language != null)
    {
      $old = $this->get('session')->getLocale();
      $this->get('session')->setLocale($language);
    }
    
    $url_referer = $this->container->get('request')->headers->get('referer');
    
    // On effectue un contrôl un peu sépcial:
    // Si la page de demande était la page de connexion (hello)
    if (
      $this->generateUrl('index', array('_locale' => $old), true) == $url_referer
      || $this->generateUrl('index', array('_locale' => null), true) == $url_referer
    )
    {
      // On construit l'url
      $url = $this->generateUrl('index', array('_locale' => $language));
    }
    else
    {
      
      // Sinon on doit rediriger l'utilisateur vers son url d'origine
      
      if (preg_match('/user/', $url_referer))
      {
        $search = "/$old/user/";
        $replace = "/$language/user/";
      }
      elseif (preg_match('/group/', $url_referer))
      {
        $search = "/$old/group/";
        $replace = "/$language/group/";
      }
      else
      {
        $search = "/$old";
        $replace = "/$language";
      }
      
      $url = str_replace($search, $replace, $url_referer);
    }
    
    return new RedirectResponse($url);
  }
  
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
      array(
       'tags'   => $this->getTagsArray(),
       'groups' => $this->getGroupsArray()
      )
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
        
        if ($this->getRequest()->isXmlHttpRequest())
        {

        }
        else
        {
          return $this->redirect($this->generateUrl('home'));
        }
        
      }
      else
      {
        if ($this->getRequest()->isXmlHttpRequest())
        {

        }
        else
        {
          $this->setFlash('error', 'element.add.error');
          return $this->redirect($this->generateUrl('home'));
        }
        
      }
      
    }
    
  }
  
//  protected function proceedElement(Element $element)
//  {
//    $factory = new ElementFactory();
//    $factory->proceed($element, $form->getData());
//  }
  
}
