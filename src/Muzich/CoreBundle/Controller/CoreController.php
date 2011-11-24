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

  /**
   * Action permettant de changer le language
   *
   * @param string $language
   * @return RedirectResponse
   */
  public function changeLanguageAction($language)
  {
    if($language != null)
    {
      $old = $this->get('session')->getLocale();
      $this->get('session')->setLocale($language);
    }
    
    $url_referer = $this->container->get('request')->headers->get('referer');
    $url_referer = str_replace(
      $siteurl = $this->container->getParameter('siteurl'), 
      '', 
      $url_referer
    );
    
    $params = $this->get('router')->match($url_referer);
    $params['_locale'] = $language;
    $route = $params['_route'];
    unset($params['_route'], $params['_controller']);
    $new_url = $this->generateUrl($route, $params);
    
    return new RedirectResponse($new_url);
  }
  
  /**
   * Cette action permet a un utilisateur de suivre ou de ne plus suivre
   * un utilisateur ou un groupe.
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

    // On tente de récupérer l'enregistrement FollowUser / FollowGroup
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

    // Si il existe déjà c'est qu'il ne veut plus suivre
    if ($Follow)
    {
      // L'utilisateur suis déjà, on doit détruire l'entité
      $em->remove($Follow);
      $em->flush();
    }
    // Sinon, c'est qu'il veut le suivre
    else
    {
      // On récupére l'entité a suivre
      $followed = $em->getRepository('MuzichCoreBundle:'.ucfirst($type))->find($id);

      if (!$followed) {
          throw $this->createNotFoundException('No '.$type.' found for id '.$id);
      }
      
      // On instancie te renseigne l'objet Follow****
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

  /**
   *  Procédure d'ajout d'un element
   */
  public function elementAddAction($group_slug)
  {    
    $user = $this->getUser();
    $em = $this->getDoctrine()->getEntityManager();
    
    $form = $this->createForm(
      new ElementAddForm(),
      array(),
      array(
       'tags'   => $this->getTagsArray(),
        // Ligne non obligatoire (cf. verif du contenu du form -> ticket)
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

        // On utilise le gestionnaire d'élément
        $factory = new ElementManager($element, $em, $this->container);
        $factory->proceedFill($data, $user);
        
        // Si on a précisé un groupe
        if ($group_slug)
        {
          $group = $this->findGroupWithSlug($group_slug);
          if ($group->userCanAddElement($this->getUserId()))
          {
            $element->setGroup($group);
          }
          else
          {
            throw $this->createNotFoundException('Vous ne pouvez ajouter d\'element a ce groupe.');
          }
          $redirect_url = $this->generateUrl('show_group', array('slug' => $group->getSlug()));
        }
        else
        {
          $redirect_url = $this->generateUrl('home');
        }
        
        $em->persist($element);
        $em->flush();
        
        if ($this->getRequest()->isXmlHttpRequest())
        {
          
        }
        else
        {
          return $this->redirect($redirect_url);
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
          return $this->redirect($redirect_url);
        }
        
      }
      
    }
    
  }
  
}
