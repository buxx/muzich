<?php

namespace Muzich\AdminBundle\Controller\Admin_user;

use Admingenerated\MuzichAdminBundle\BaseAdmin_userController\NewController as BaseNewController;
use Symfony\Component\Form\Form;
use Muzich\CoreBundle\Entity\User;

class NewController extends BaseNewController
{
  
  public function preSave(Form $form, User $User)
  {
    $User->setPlainPassword('k?F69*Cmh35nnK63~%KVDc^');
    $this->container->get('fos_user.user_manager')->updateUser($User);
  }
  
}
