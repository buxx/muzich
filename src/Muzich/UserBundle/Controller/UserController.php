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
  
  public function registerAction()
  {
    $form = $this->container->get('fos_user.registration.form');
    $formHandler = $this->container->get('fos_user.registration.form.handler');
    $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');
    
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

      $this->setFlash('success', 'Votre compte a bien été créé');
      $url = $this->generateUrl($route);

      return new RedirectResponse($url);
    }

    return $this->container->get('templating')->renderResponse(
      'MuzichIndexBundle:Index:index.html.twig',
      array(
        'form' => $form->createView(),
        'error' => null,
        'last_username' => null
      )
    );
  }
    
  public function changePasswordAction()
  {
    $user = $this->getUser();
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
      return $this->container->get('templating')->renderResponse(
        'MuzichUserBundle:User:account.html.twig',
        array(
          'form_password' => $form->createView(),
          'user' => $user
        )
      );
    }

    
  }
  
  /**
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
