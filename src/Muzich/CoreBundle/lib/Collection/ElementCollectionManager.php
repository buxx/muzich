<?php

namespace Muzich\CoreBundle\lib\Collection;

class ElementCollectionManager extends CollectionManager
{
  
  const ATTRIBUTE_ID   = 'Id';
  const ATTRIBUTE_NAME = 'Name';
  const ATTRIBUTE_TYPE = 'Type';
  const ATTRIBUTE_PRIVATE = 'Private';
  
  protected $allow_duplicates = true;
  protected $schema = array(
    self::ATTRIBUTE_ID,
    self::ATTRIBUTE_NAME,
    self::ATTRIBUTE_TYPE,
    self::ATTRIBUTE_PRIVATE
  );
  
  protected $object_reference_attribute = self::ATTRIBUTE_ID;
  
}