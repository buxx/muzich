<?php

namespace Muzich\UserBundle\Controller;

use Muzich\CoreBundle\lib\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Muzich\CoreBundle\Form\Tag\TagFavoritesForm;

class UserController extends Controller
{
  /**
   * Page de configuration de son compte
   *
   * @Template()
   */
  public function accountAction()
  {
    $user = $this->getUser();

    $form_password = $this->container->get('fos_user.change_password.form');
    
    $form_tags_favorites = $this->createForm(
      new TagFavoritesForm(), 
      array('tags' => $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
        ->getTagIdsFavorites($user->getId())
      ),
      array('tags' => $this->getTagsArray())
    );

      return array(
        'user' => $user,
        'form_password' => $form_password->createView(),
        'form_tags_favorites' => $form_tags_favorites->createView()
      );
  }
  
  /**
   * Un bug étrange empêche la mise ne place de contraintes sur le formulaire
   * d'inscription. On effectue alors les vérifications ici.
   * 
   * @return array of string errors
   */
  protected function checkRegistrationInformations($form)
  {
    $errors = array();
    $form->bindRequest($this->getRequest());
    $user = $form->getData();
    
    /*
     * Contrôle de la taille du pseudo
     * min: 3
     * max: 32
     */
    if (strlen($user->getUsername()) < 3)
    {
      $errors[] = $this->get('translator')->trans(
        'error.registration.username.min', 
        array('%limit%' => 3),
        'validators'
      );
    }
    
    if (strlen($user->getUsername()) > 32)
    {
      $errors[] = $this->get('translator')->trans(
        'error.registration.username.max', 
        array('%limit%' => 32),
        'validators'
      );
    }
    
    return $errors;
  }
  
  public function registerAction()
  {
    $form = $this->container->get('fos_user.registration.form');
    $formHandler = $this->container->get('fos_user.registration.form.handler');
    $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');
    
    // Pour palier bug, verif interne
    if (count(($errors = $this->checkRegistrationInformations($form))) < 1)
    {
      $process = $formHandler->process($confirmationEnabled);
      if ($process) {
        $user = $form->getData();

        if ($confirmationEnabled) {
          $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
          $route = 'fos_user_registration_check_email';
        } else {
          $this->authenticateUser($user);
          $route = 'start';
        }

        $this->setFlash('fos_user_success', 'registration.flash.user_created');
        $url = $this->generateUrl($route);

        return new RedirectResponse($url);
      }
    }

    return $this->container->get('templating')->renderResponse(
      'MuzichIndexBundle:Index:index.html.twig',
      array(
        'form' => $form->createView(),
        'error' => null,
        'registration_errors' => $form->getErrors(),
        'registration_errors_pers' => $errors,
        'last_username' => null
      )
    );
  }
    
  public function changePasswordAction()
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
    
    if (!is_object($user) || !$user instanceof UserInterface) {
        throw new AccessDeniedException('This user does not have access to this section.');
    }

    $form = $this->container->get('fos_user.change_password.form');
    $formHandler = $this->container->get('fos_user.change_password.form.handler');
    
    $process = $formHandler->process($user);
    if ($process)
    {
      $this->container->get('session')->setFlash('fos_user_success', 'change_password.flash.success');
      return new RedirectResponse($this->generateUrl('my_account'));
    }
    else
    {
      $form_tags_favorites = $this->createForm(
        new TagFavoritesForm(), 
        array('tags' => $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
          ->getTagIdsFavorites($user->getId())
        ),
        array('tags' => $this->getTagsArray())
      );
      
      return $this->container->get('templating')->renderResponse(
        'MuzichUserBundle:User:account.html.twig',
        array(
          'form_password' => $form->createView(),
          'user' => $user,
          'form_tags_favorites' => $form_tags_favorites->createView()
        )
      );
    }

    
  }
  
  /**
   * Page ouverte après l'inscription sur laquelle on propose de saisir ses
   * tags favoris.
   * 
   * @Template()
   */
  public function startAction()
  {
    $user = $this->getUser();
    
    $form = $this->createForm(
      new TagFavoritesForm(), 
      array('tags' => $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
        ->getTagIdsFavorites($user->getId())
      ),
      array('tags' => $this->getTagsArray())
    );
    
    return array(
      'form' => $form->createView()
    );
  }
  
  /**
   *
   * @param string $redirect 
   */
  public function updateTagFavoritesAction($redirect)
  {
    $request = $this->getRequest();
    $user = $this->getUser(true, array('join' => array('favorites_tags')));
    
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
    
    $form = $this->createForm(
      new TagFavoritesForm(), 
      array('tags' => $this->getDoctrine()->getRepository('MuzichCoreBundle:User')
        ->getTagIdsFavorites($user->getId())
      ),
      array('tags' => $this->getTagsArray())
    );
    
    if ($request->getMethod() == 'POST')
    {
      $form->bindRequest($request);
      if ($form->isValid())
      {
        $data = $form->getData();
        $user->updateTagsFavoritesById($this->getDoctrine()->getEntityManager(), $data['tags']);
        
        $this->container->get('session')->setFlash('success', 'Vos tags péférés ont correctements été mis a jour.');
      }
      else
      {
        return $this->container->get('templating')->renderResponse(
          'MuzichUserBundle:User:start.html.twig',
          array(
            'form' => $form->createView()
          )
        );
      }
    }
    
    // (Il y aura aussi une redirection vers "mon compte / tags")
    if ($redirect == 'home')
    {
      return $this->redirect($this->generateUrl('home'));
    }
    else
    {
      return $this->redirect($this->generateUrl('my_account'));
    }
  }
    
}
