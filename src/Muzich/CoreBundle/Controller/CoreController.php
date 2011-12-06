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
use Muzich\CoreBundle\Form\Search\ElementSearchForm;

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
    
    $element = new Element();
    $form = $this->createForm(
      new ElementAddForm(),
      $element,
      array(
       'tags'   => $this->getTagsArray(),
        // Ligne non obligatoire (cf. verif du contenu du form -> ticket)
       //'groups' => $this->getGroupsArray()
      )
    );
    
    if ($this->getRequest()->getMethod() == 'POST')
    {
      $form->bindRequest($this->getRequest());
      if ($form->isValid())
      {
        
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
        
        // On utilise le gestionnaire d'élément
        $factory = new ElementManager($element, $em, $this->container);
        $factory->proceedFill($user);
        
        // Si on a précisé un groupe dans lequel mettre l'element
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
          
          $search_object = $this->getElementSearcher();
          $user = $this->getUser(true, array('join' => array(
            'groups_owned'
          )), true);

          $search_form = $this->createForm(
            new ElementSearchForm(), 
            $search_object->getParams(),
            array(
              'tags' => $tags = $this->getTagsArray()
            )
          );

          return $this->render('MuzichHomeBundle:Home:index.html.twig', array(
            'user'        => $this->getUser(),
            'add_form'    => $form->createView(),
            'search_form' => $search_form->createView(),
            'elements'    => $search_object->getElements($this->getDoctrine(), $this->getUserId())
          ));
          
        }
        
      }
      
    }
    
  }
  
}
