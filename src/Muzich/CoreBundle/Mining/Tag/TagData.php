<?php
namespace Muzich\CoreBundle\Mining\Tag;

use Muzich\CoreBundle\Mining\Tag\Tag as Base;
use Muzich\CoreBundle\Entity\User;

class TagData extends Base
{
  
  public function getTagOrderForFavorites(User $user)
  {
    $user_tags = $this->getUserTagsTags($user, 'element_favorite_tags');
    
   if (count($tags_ordereds = $user_tags->getElementFavoriteTags()))
     return $tags_ordereds;
   
   return array();
  }
  
  public function getTagOrderForDiffusions(User $user)
  {
    $user_tags = $this->getUserTagsTags($user, 'element_diffusion_tags');
    $user_tags = null;
    if ($user_tags)
    {
      if (count($tags_ordereds = $user_tags->getElementDiffusionTags()))
      {
        return $tags_ordereds;
      }
    }
    return array();
  }
  
  protected function getUserTagsTags(User $user, $field)
  {
    return $this->getMongoManager()->createQueryBuilder('MuzichCoreBundle:UserTags')
      ->select($field)
      ->field('ref')->equals((int)$user->getId())
      ->getQuery()->getSingleResult()
    ;
  }
  
}