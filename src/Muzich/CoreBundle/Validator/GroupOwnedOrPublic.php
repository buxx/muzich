<?php
// AlwaysFail.php
namespace Muzich\CoreBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class GroupOwnedOrPublic extends Constraint
{
  public $message = 'group_malchoisis';
  public $entity;
  public $property;

  public function validatedBy()
  {
      return 'validator.groupownedorpublic';
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