<?php

namespace Muzich\CoreBundle\Searcher;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bundle\DoctrineBundle\Registry;

/**
 * Objet de recherche
 */
class UserAndGroupSearcher extends Searcher implements SearcherInterface
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
    // On remplace le caratcère '%' au cas ou un malin l'insére.
    $string = str_replace('%', '#', $this->string);
    
    $users = $doctrine
      ->getRepository('MuzichCoreBundle:User')
      ->findByString($string)
      ->execute()
    ;
    
    $groups = $doctrine
      ->getRepository('MuzichCoreBundle:Group')
      ->findByString($string)
      ->execute()
    ;
    
    return array(
      'users'  => $users,
      'groups' => $groups
    );
  }
  
}
