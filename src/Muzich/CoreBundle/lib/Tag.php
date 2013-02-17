<?php

namespace Muzich\CoreBundle\lib;

/**
 *  Boite a outils pour les Tags. 
 */
class Tag
{
  
  /**
   *
   * @param array $elements Tableau d'éléments
   * @return array Tableau de tags [0 => id_tag, 1 => id_tag] rangé du tag le 
   * plus utilisé au tag le moins utilisé
   */
  public function getOrderedTagsWithElements($elements)
  {
    $tags_count = array();
    $tags_ordered = array();
    
    foreach ($elements as $element)
    {
      foreach ($element->getTags() as $tag)
      {
        // Si on a déjà un compteur pour ce tag
        if (array_key_exists($tag->getId(), $tags_count))
        {
          // On incrémente
          $tags_count[$tag->getId()] = $tags_count[$tag->getId()] + 1;
        }
        else
        {
          // On commence le compteur
          $tags_count[$tag->getId()] = 1;
        }
      }
    }
    
    // On trie le tableau avec les valeurs décroissantes
    // Comme les valeurs sont les count, on aura les tags les plus
    // utilisés en haut du tableau
    arsort($tags_count);
    
    foreach ($tags_count as $tag_id => $count)
    {
      $tags_ordered[] = $tag_id;
    }
    
    return $tags_ordered;
  }
  
  /**
   * Range une liste de tags [0 => Tag] en fonction d'une
   * réfèrence sous la forme [0 => tag_id, 1 => tag_id]
   * 
   * @param array $tags
   * @param array $reference 
   * @return array
   */
  public function sortTagWithOrderedReference($tags, $reference)
  {
    // tableau des tags rangés
    $tag_ordered = array();
    // tableau des tags pas encore dans la référence
    $tag_not_fond = array();
    
    foreach ($tags as $tag)
    {
      $position = array_search($tag->getId(), $reference);
      // Si on l'a trouvé dans la réference
      if ($position !== false)
      {
        // On le met dans le tableau des tags rangé, avec la clé de la réference
        $tag_ordered[$position] = $tag;
      }
      else
      {
        // Si il n'étais pas dans la réference on le met en attente dans 
        // ce tableau
        $tag_not_fond[] = $tag;
      }
    }
    
    // Une fois les tags réferencés dans le tableau ordonné, on ajoute ceux qui
    // ne l'était pas
    foreach ($tag_not_fond as $tag)
    {
      $tag_ordered[] = $tag;
    }
    
    // On trie le tableau en fonction des clés.
    ksort($tag_ordered);
    
    return $tag_ordered;
  }
  
}