<?php

namespace Muzich\CoreBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

class GroupOwnedOrPublicValidator extends ConstraintValidator
{
  
  private $entityManager;
  private $security_context;

  public function __construct(EntityManager $entityManager, SecurityContext $security_context)
  {
    $this->entityManager = $entityManager;
    $this->security_context = $security_context;
  }
  
  /**
   * Ce n'est valide que si
   *  * Le groupe est open
   *  * Le groupe appartient a l'user
   *  * On a pas précisé de groupe
   * 
   * @param int $value
   * @param Constraint $constraint
   * @return boolean
   */
  public function isValid($value, Constraint $constraint)
  {
    if ($value)
    {
      $user = $user = $this->security_context->getToken()->getUser();
      $group = $this->entityManager->getRepository('MuzichCoreBundle:Group')
        ->findOneById($value)
      ;
      if (!$group)
      {
        //$this->setMessage('Le groupe est invalide'); UPGRADE 2.1
        $this->context->addViolation('group_invalid');
        return false;
      }
      
      if (!$group->userCanAddElement($user))
      {
        //$this->setMessage('Le groupe est invalide'); UPGRADE 2.1
        $this->context->addViolation('group_invalid');
        return false;
      }
    }
    return true;
  }
}