<?php

namespace Muzich\CoreBundle\Searcher;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bundle\DoctrineBundle\Registry;

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
  
  /**
   * @see SearcherInterface
   * @param array $params 
   */
  public function init($params)
  {
    // Control des parametres transmis.
    $this->checkParams($params, array(
      'string' => "Muzich\CoreBundle\Searcher\UserAndGroupSearch::init(): \$params: Un string est nécéssaire"
    ));
    
    // Mise a jour des attributs
    $this->setAttributes(array('string', 'min_lenght'), $params);
  }
  
  /**
   * @see SearcherInterface
   * @param array $params 
   */
  public function update($params)
  {
    // Mise a jour des attributs
    $this->setAttributes(array(
      'string', 'min_length'
    ), $params);
  }
  
  /**
   * @see SearcherInterface
   * 
   * @return array 
   */
  public function getParams()
  {
    return array(
      'string' => $this->string,
      'min_length' => $this->min_length
    );
  }
  
  public function getString()
  {
    return $this->string;
  }
  
  public function setString($string)
  {
    $this->string = $string;
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
