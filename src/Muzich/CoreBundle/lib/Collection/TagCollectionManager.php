<?php

namespace Muzich\CoreBundle\lib\Collection;

use Muzich\CoreBundle\Entity\Tag;

class TagCollectionManager extends CollectionManager
{
  
  const ATTRIBUTE_ID   = 'Id';
  const ATTRIBUTE_NAME = 'Name';
  
  protected $schema = array(
    self::ATTRIBUTE_ID,
    self::ATTRIBUTE_NAME
  );
  
  protected $object_reference_attribute = self::ATTRIBUTE_ID;
  
}