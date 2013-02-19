<?php

namespace Muzich\AdminBundle\Controller\Admin_element;

use Admingenerated\MuzichAdminBundle\BaseAdmin_elementController\EditController as BaseEditController;
use Symfony\Component\Form\Form;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Managers\ElementManager;

class EditController extends BaseEditController
{
  
  public function preSave(Form $form, Element $Element)
  {
    //$em = $this->get('doctrine')->getEntityManager();
    //$factory = new ElementManager($Element, $em, $this->container);
    //$factory->proceedFill($Element->getOwner(), false);
  }
  
}
