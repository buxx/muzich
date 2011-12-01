<?php
// AlwaysFail.php
namespace Muzich\CoreBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Tags extends Constraint
{
  public $message = 'Les tags sont mal configurés';
  public $entity;
  public $property;

  public function validatedBy()
  {
      return 'validator.tags';
  }

  public function requiredOptions()
  {
      return array('entity', 'property');
  }
  
  public function getTargets()
  {
    return Constraint::PROPERTY_CONSTRAINT;
  }
}