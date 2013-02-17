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
    $value = json_decode($value);
    
    if (count($value))
    {
      if (array_diff($value, array_unique($value)))
      {
        //$this->setMessage('Tags saisies incorrects');UPGRADE 2.1
        $this->context->addViolation('tags_saisinco');
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
        //$this->setMessage('Tags saisies incorrects');UPGRADE 2.1
        $this->context->addViolation('tags_saisinco');
        return false;
      }
    }
    return true;
  }
}