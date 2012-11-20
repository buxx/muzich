<?php

namespace Muzich\CoreBundle\Util;

use Symfony\Bundle\DoctrineBundle\Registry;
use Muzich\CoreBundle\Util\StrictCanonicalizer;

/**
 * Cette classe permet d'aider a la recherche de mot similaires a un mot
 * 
 * 
 */
class TagLike
{
  
  protected $registry;
  
  public function __construct(Registry $registry)
  {
    $this->registry = $registry;
  }
  
  /**
   * Ajoute le tag au début du tableau passé en paramètre si celui-ci
   * n'est pas a l'intérieur.
   * 
   * @param array $array
   * @param Tag $tag
   * @return array 
   */
  private function sort_addtop_if_isnt_in($array, $tag)
  {
    $in = false;
    for ($x=0;$x<=sizeof($array)-1;$x++)
    {
      if ($array[$x]['id'] == $tag['id'])
      {
        $in = true;
        break;
      }
    }
    
    if (!$in)
    {
      array_unshift($array, $tag);
      return $array;
    }
    return $array;
  }
  
  /**
   * Ajoute le tag a al fin du tableau passé en paramètre si celui-ci
   * n'est pas a l'intérieur.
   * 
   * @param array $array
   * @param Tag $tag
   * @return array 
   */
  private function sort_addbottom_if_isnt_in($array, $tag)
  {
    $in = false;
    for ($x=0;$x<=sizeof($array)-1;$x++)
    {
      if ($array[$x]['id'] == $tag['id'])
      {
        $in = true;
        break;
      }
    }
    
    if (!$in)
    {
      $array[] = $tag;
      return $array;
    }
    return $array;
  }
  
  /**
   * Construit un tableau de mot a partir du terme de recherche. Ces mots 
   * permettrons la recherche de tags en base et le trie des tags trouvé.
   * 
   * La déduction de ces mot est basé sur:
   *  * Le remplacement des espaces par des tirets et inversement (virgules aussi)
   * 
   * Et chaque mot doit dépasser deux caractères
   * 
   * @param string $search
   * @return array 
   */
  protected function getSearchWordsReplacing($search)
  {
    // En base les tags sont composé de mot a '-' ou a ' '.
    $words = array_unique(array(
      str_replace(' ', '-', $search),
      str_replace('-', ' ', $search),
      str_replace(',', ' ', $search),
      str_replace(', ', ' ', $search),
      str_replace(',', '-', $search),
      str_replace(', ', '-', $search),
      str_replace(' & ', ' AND ', $search)
    ));
    
    return $words;
  }
  
  /**
   * Construit un tableau de mot a partir du terme de recherche. Ces mots 
   * permettrons la recherche de tags en base et le trie des tags trouvé.
   * 
   * La déduction de ces mot est basé sur:
   *  * La découpe du terme par espaces, tirets et virgules
   * 
   * Et chaque mot doit dépasser deux caractères
   * 
   * @param string $search 
   */
  protected function getSearchWordsExploding($search)
  {
    $words = array_unique(array_merge(
      explode(' ', $search),
      explode('-', $search),
      explode('- ', $search),
      explode(' -', $search),
      explode(' - ', $search),
      explode(',', $search),
      explode(', ', $search),
      explode(' ,', $search),
      explode(' , ', $search)
    ));
    
    $words_filetereds = array();
    foreach ($words as $i => $word)
    {
      if (trim(strlen($word)) > 1)
      {
        $words_filetereds[] = trim($word);
      }
    }
    
    return $words_filetereds;
  }
  
  /**
   * Recherche en base les tags ressemblant a la recherche.
   * 
   * @param array $words
   * @param int $user_id
   * @return array 
   */
  protected function searchTags($words, $user_id)
  {
    $where = '';
    $params = array();
    
    foreach ($words as $i => $word)
    {
      if ($where == '')
      {
        $where .= 'WHERE UPPER(t.slug) LIKE :str'.$i;
      }
      else
      {
        $where .= ' OR UPPER(t.slug) LIKE :str'.$i;
      }

      $params['str'.$i] = '%'.$word.'%';
    }

    $params['uid'] = '%"'.$user_id.'"%';
    $tags_query = $this->registry->getEntityManager()->createQuery("
      SELECT t.name, t.slug, t.id FROM MuzichCoreBundle:Tag t
      $where

      AND (t.tomoderate = '0' OR t.tomoderate IS NULL
      OR t.privateids LIKE :uid)

      ORDER BY t.name ASC"
    )
      ->setParameters($params)
      ->getScalarResult()
    ;
    
    $tags = array();
    foreach ($tags_query as $tag)
    {
      $tags[] = array(
        'name' => $tag['name'], 
        'id'   => $tag['id'],
        'slug' => $tag['slug']
      );
    }
    
    return $tags;
  }
  
  /**
   * Trie les tags de manière a ce que les tags les plus ressemblant a la 
   * recherche soient au début du tableau.
   * 
   * @param array $tags
   * @param array $search
   * @param array $words
   * @param array $words_replacing
   * @param array $words_exploding
   * @return array 
   */
  protected function sortTags($tags, $search, $words, $words_replacing, $words_exploding)
  {
    $same_found = false;
    $tag_sorted = array();
    
    /*
     * Première passe: Pas plus de trois caractères différents de la recherche
     * On se base ici sur le recherche non découpé
     */
    foreach ($tags as $i => $tag)
    {
      foreach ($words_replacing as $word)
      {
        $word = trim($word);
        if (strlen($word) > 1)
        {
          if (
            strlen(str_replace($word, '', strtoupper($tag['slug']))) < 4
            // Si on tombe sur le même nom, il ne faut pas le mettre en haut maintenant
            // ou il se fera décallé vers le bas
            && $word != $search
          )
          {
            $tag_sorted = $this->sort_addtop_if_isnt_in($tag_sorted, $tag);
          }
        }
      }
      
    }
    
    /*
     * Pour une recherche en plusieurs mots, on cherche la similitude avec 
     * des tags. De façon a ce que "dark psytrance" soit reconnue comme proche de
     * "psytrance dark".
     */
    if (count($words_exploding) > 1)
    {
    
      $tags_counteds = array();
      /*
       * Pour chaque mot qui compose la recherche on regarde si ce mot
       * est compris dans un des tags
       */
      foreach ($tags as $i => $tag)
      {
        foreach ($words_exploding as $word)
        {
          if (strpos(strtoupper($tag['slug']), $word) !== false)
          {
            $count = 1;
            if (array_key_exists($tag['id'], $tags_counteds))
            {
              $count = ($tags_counteds[$tag['id']]['count'])+1;
            }
            $tags_counteds[$tag['id']] = array(
              'count' => $count,
              'tag'   => $tag
            );
          }
        }
      }

      /*
       * Maintenant que l'on a un tableau contenant les tags comportant au moins
       * un mot identique a recherche
       */
      foreach ($tags_counteds as $id => $counted)
      {
        // Si le tag a eu plus d'une fois le même mot
        if ($counted['count'] > 1)
        {
          // Ci-dessous on va chercher a voir si le tag et la recherche on le 
          // même nombre de mots, si c'est le cas on pourra considérer cette 
          // recherche comme lié a un tag connu.

          if (count($words_exploding) == count($this->getSearchWordsExploding($counted['tag']['slug'])))
          {
            $same_found = true;
          }

          // Cette verif permet de ne pas ajouter les tags qui n'ont qu'un mot
          // Si on ajouté ce tag maintenant il ne serais pas ajouté au controle en dessous
          // (nom identique) et donc pas au dessus.
          $tag_sorted = $this->sort_addtop_if_isnt_in($tag_sorted, $counted['tag']);

        }
      }
    
    }
    
    /*
     * On recherche maintenant les noms de tags identique a la recherche
     */
    foreach ($tags as $i => $tag)
    {      
      foreach ($words_replacing as $word)
      {
        if ($word == strtoupper($tag['slug']))
        {
          // Ci-dessous on déduit si le mot est identique au tag . 
          // De façon a ce que si c'est le cas, pouvoir dire:
          // oui le terme recherché est connu.
          if (in_array($word, $words_replacing))
          { 
            $same_found = true;
          }
          $tag_sorted = $this->sort_addtop_if_isnt_in($tag_sorted, $tag);
        }
      }
    }
    
    foreach ($tags as $i => $tag)
    {
      $tag_sorted = $this->sort_addbottom_if_isnt_in($tag_sorted, $tag);
    }
    
    return array(
      'tags'       => $tag_sorted,
      'same_found' => $same_found
    );
  }
  
  public function getSimilarTags($search, $user_id)
  {
    $canonicalizer = new StrictCanonicalizer();
    $search = strtoupper($canonicalizer->canonicalize(trim($search)));
    
    if (strlen($search) < 2)
    {
      // Le terme de recherche doit faire au moins 2 caractéres
      return array('tags' => array(), 'same_found' => false);
    }
    
    $words_e = $this->getSearchWordsExploding($search);
    $words_r = $this->getSearchWordsReplacing($search);
    $words   = array_unique(array_merge($words_e, $words_r));
    $tags    = $this->searchTags($words, $user_id);
    
    return $this->sortTags($tags, $search, $words, $words_r, $words_e);
  }
  
}