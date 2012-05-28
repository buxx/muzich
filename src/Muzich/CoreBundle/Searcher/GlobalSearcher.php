<?php

namespace Muzich\CoreBundle\Searcher;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bundle\DoctrineBundle\Registry;
use Muzich\CoreBundle\Searcher\UserAndGroupSearcher;
use Muzich\CoreBundle\Searcher\ElementSearcher;

/**
 * 
 */
class GlobalSearcher extends Searcher implements SearcherInterface
{
  
  /**
   * Chaine de caractère représentant la recherche.
   * 
   * @var string
   * @Assert\NotBlank()
   * @Assert\Type("string")
   * @Assert\MinLength(3)
   */
  protected $string;
    
  public function setString($string)
  {
    $this->string = $string;
  }
  
  public function getString()
  {
    return $this->string;
  }
  
  /**
   * @see SearcherInterface
   * 
   * @return array 
   */
  public function getParams()
  {
    return array(
      'string' => $this->string
    );
  }
  
  /**
   * Retourne les user et groupes correspondant a la recherche
   *
   * @param Registry $doctrine
   * @return array
   */
  public function getResults(Registry $doctrine, $user_id, $search_elements_count, $min_word_length = null)
  {
    // On remplace le caratcère '%' au cas ou un malin l'insére.
    $string = str_replace('%', '#', $this->string);
    // instancier objet SearchUser and groups;
    $ugs = new UserAndGroupSearcher();
    $ugs->setString($this->string);
    
    // puis on fait recherche sur elements
    $es = new ElementSearcher();
    $es->init(array(
      'string' => $string,
      'count'  => $search_elements_count
    ));
    $results = $ugs->getResults($doctrine);
    $results['elements'] = $es->getElements(
      $doctrine, 
      $user_id, 
      'execute',
      array('word_min_length' => $min_word_length)
    );
    return $results;
  }
  
  
  
  
  
  
  
}