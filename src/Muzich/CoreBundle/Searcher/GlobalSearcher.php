<?php

namespace Muzich\CoreBundle\Searcher;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bundle\DoctrineBundle\Registry;
use Muzich\CoreBundle\Searcher\UserAndGroupSearcher;

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
  public function getResults(Registry $doctrine)
  {
    $ug_searcher = new UserAndGroupSearcher();
    $ug_searcher->setString($this->string);
    
    
    // instancier objet SearchUser and groups;
    // puis faire recherche sur elements
    
    // On remplace le caratcère '%' au cas ou un malin l'insére.
    $string = str_replace('%', '#', $this->string);
    
    return $ug_searcher->getResults($doctrine);
  }
  
  
  
  
  
  
  
}