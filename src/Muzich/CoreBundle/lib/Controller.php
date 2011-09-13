<?php

namespace Muzich\CoreBundle\lib;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Muzich\CoreBundle\Searcher\ElementSearcher;

class Controller extends BaseController
{
  
  private $ElementSearcher = null;
    
  /**
   * Met a jour l'objet ElementSearcher
   * 
   * @param ElementSearcher $es 
   */
  protected function setElementSearcher(ElementSearcher $es)
  {
    $this->ElementSearcher = $es;
    $session->set('user.element_search.params', $es->getParams());
  }
  
  /**
   * Retourn l'objet ElementSearcher en cours.
   * 
   * @param int $user_id
   * @return  ElementSearcher
   */
  protected function getElementSearcher($user_id)
  {
    // Premièrement, est-ce que l'objet existe
    if (!$this->ElementSearcher)
    {
      $session  = $this->get("session");
      // Si l'objet n'existe pas encore, a t-on déjà des paramètres de recherche
      if (!$session->has('user.element_search.params'))
      {
        // Il nous faut instancier notre premier objet recherche
        // Premièrement on récupère les tags favoris de l'utilisateur
        $tags_id = array();
        foreach ($this->getDoctrine()->getRepository('MuzichCoreBundle:User')
          // TODO: 3: CONFIG !!
          ->getTagIdsFavorites($user_id, 3)
          as $tag)
        {
          $tags_id[] = $tag['id'];
        }
        
        // Ensuite on fabrique l'objet ElementSearcher
        $this->ElementSearcher = new ElementSearcher();
        $this->ElementSearcher->init(array(
          'tags' => $tags_id
        ));
        
        // Et on met en session les paramètres
        $session->set('user.element_search.params', $this->ElementSearcher->getParams());
      }
      else
      {
        // Des paramètres existes, on fabrique l'objet recherche
        $this->ElementSearcher = new ElementSearcher();
        $this->ElementSearcher->init($session->get('user.element_search.params'));
      }
      
      // L'objet existe déjà, on le retourne
      return $this->ElementSearcher;
    }
  }
  
}
