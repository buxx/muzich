<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;

class UserControllerTest extends FunctionalTest
{
  
  public function testTagsFavoritesSuccess()
  {
    /**
     * Inscription d'un utilisateur
     */
    $this->client = self::createClient();

    $hardtek_id = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek')->getId();
    $tribe_id   = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe')->getId();
    
    $this->crawler = $this->client->request('GET', $this->generateUrl('index'));
    $this->isResponseSuccess();

    $this->procedure_registration_success(
      'raoulc', 
      'raoulc.def4v65sds@gmail.com', 
      'toor', 
      'toor'
    );
    
    // Il ne doit y avoir aucun enregistrements de tags favoris
    $Favorites = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array(
          'user' => $this->getUser()->getId()
      ))
    ;
    
    $this->assertEquals(0, count($Favorites));
    
    // On a attérit sur la page de présentation et de sleection des tags favoris
    $this->exist('form[action="'.($url = $this->generateUrl('update_tag_favorites')).'"]');
    
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['tag_favorites_form[tags]['.$hardtek_id.']'] = $hardtek_id;
    $form['tag_favorites_form[tags]['.$tribe_id.']'] = $tribe_id;
    $this->submit($form);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // Désormais il y a deux tags favoris pour cet utilisateur
    $Favorites = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array(
          'user' => $this->getUser()->getId()
      ))
    ;
    $this->assertEquals(2, count($Favorites));
    
    $Favorites = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array(
          'user' => $this->getUser()->getId(),
          'tag'  => $hardtek_id
      ))
    ;
    $this->assertEquals(1, count($Favorites));
    
    $Favorites = $this->getDoctrine()->getRepository('MuzichCoreBundle:UsersTagsFavorites')
      ->findBy(array(
          'user' => $this->getUser()->getId(),
          'tag'  => $tribe_id
      ))
    ;
    $this->assertEquals(1, count($Favorites));
  }
}