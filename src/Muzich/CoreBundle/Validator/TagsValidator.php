<?php

namespace Muzich\CoreBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Doctrine\ORM\EntityManager;

class TagsValidator extends ConstraintValidator
{
  
  private $entityManager;

  public function __construct(EntityManager $entityManager)
  {
    $this->entityManager = $entityManager;
  }
  
  public function isValid($value, Constraint $constraint)
  {
    if (array_diff($value, array_unique($value)))
    {
      $this->setMessage('Tags saisies incorrects');
      return false;
    }
    
    $count = $this->entityManager
      ->createQuery("SELECT COUNT(t)
        FROM MuzichCoreBundle:Tag t
        WHERE t IN (:tids)")
      ->setParameter('tids', $value)
    ->getSingleScalarResult();
    
    if ($count != count ($value))
    {
      $this->setMessage('Tags saisies incorrects');
      return false;
    }
    
    return true;
  }
}