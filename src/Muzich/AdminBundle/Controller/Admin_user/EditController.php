<?php

namespace Muzich\AdminBundle\Controller\Admin_user;

use Admingenerated\MuzichAdminBundle\BaseAdmin_userController\EditController as BaseEditController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Form;
use Muzich\CoreBundle\Entity\User;

class EditController extends BaseEditController
{
  public function preSave(Form $form, User $User)
  {
    $this->container->get('fos_user.user_manager')->updateUser($User);
  }
}
