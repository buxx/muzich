<?php

namespace Muzich\CoreBundle\lib;

class TagScorer
{
  
  public function scoreOrderedsTagsIds($ordered_tags)
  {
    $tags_ordered_by_score = array();
    $score_max = count($ordered_tags);
    
    foreach ($ordered_tags as $tag_id)
    {
      $tags_ordered_by_score[(int)$score_max] = $tag_id;
      $score_max -= 1;
    }
    
    return $tags_ordered_by_score;
  }
  
  public function scoreEntireOrderedTagsIds($ordereds_elements_tags_ids, $favoriteds_tags)
  {
    $tags_score = array();
    
    foreach ($ordereds_elements_tags_ids as $ordereds_tags_ids)
    {
      foreach ($ordereds_tags_ids as $score_tag => $tag_id)
      {
        if (!array_key_exists($tag_id, $tags_score))
          $tags_score[$tag_id] = 0;
        
        $tags_score[$tag_id] += $score_tag;
      }
    }
    
    foreach ($favoriteds_tags as $favorite_tag_id)
    {
      if (!array_key_exists($favorite_tag_id, $tags_score))
        $tags_score[$favorite_tag_id] = 0;
        
      $tags_score[$favorite_tag_id] += 5;
    }
        
    arsort($tags_score);
    
    $oredered_tags_ids_without_score = array();
    foreach ($tags_score as $tag_id => $tag_score)
    {
      $oredered_tags_ids_without_score[] = $tag_id;
    }
    
    return $oredered_tags_ids_without_score;
  }
  
}