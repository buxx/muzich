<?php
// AlwaysFail.php
namespace Muzich\CoreBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class GroupOwnedOrPublic extends Constraint
{
  public $message = 'Le groupe est mal choisis';
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