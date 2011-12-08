<?php

namespace Muzich\CoreBundle\Tests\Controller;

use Muzich\CoreBundle\lib\FunctionalTest;

class GroupControllerTest extends FunctionalTest
{
 
  /**
   * Test de création d'un groupe
   */
  public function testGroupAdd()
  {
    $this->client = self::createClient();
    $this->connectUser('bob', 'toor');
    
    $Fans_de_psytrance = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('Fans de psytrance');
    $hardtek_id = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Hardtek')->getId();
    $tribe_id   = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Tribe')->getId();
    
    $this->crawler = $this->client->request('GET', $this->generateUrl('groups_own_list'));
    
    // Le groupe que nous voulons créer n'existe pas dans la base
    $group = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')
      ->findOneByName('HardtekMania')
    ;
    
    $this->assertTrue(is_null($group));
    
    // bob administre le groupe Fan de psutrance
    $this->exist('a[href="'.$this->generateUrl('show_group', array('slug' => $Fans_de_psytrance->getSlug())).'"]');
    // On a le formulaire de création sur la page
    $this->exist('form[action="'.($url = $this->generateUrl('group_add')).'"]');
    $this->exist('form[action="'.$url.'"] input[id="group_name"]');
    $this->exist('form[action="'.$url.'"] textarea[id="group_description"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['group[name]'] = 'HardtekMania';
    $form['group[description]'] = 'Des bass, des bpm, des gros caissons !';
    $form['group[tags]['.$hardtek_id.']'] = $hardtek_id;
    $form['group[tags]['.$tribe_id.']'] = $tribe_id;
    $this->submit($form);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // Le groupe que créé existe bien dans la base
    $group = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')
      ->findOneBy(array(
        'name'        => 'HardtekMania',
        'description' => 'Des bass, des bpm, des gros caissons !'
      ))
    ;
    
    $this->assertTrue(!is_null($group));
    
    // On vérifie egallement le lien avec les tags
    if (!is_null($group))
    {
      $group_tags = $this->getDoctrine()->getRepository('MuzichCoreBundle:GroupsTagsFavorites')
        ->findBy(array(
          'group' => $group->getId(),
          'tag'   => $hardtek_id
        ))
      ;
      
      $this->assertEquals(1, count($group_tags));
      
      $group_tags = $this->getDoctrine()->getRepository('MuzichCoreBundle:GroupsTagsFavorites')
        ->findBy(array(
          'group' => $group->getId(),
          'tag'   => $tribe_id
        ))
      ;
      
      $this->assertEquals(1, count($group_tags));      
    }
    
  }
  
  /**
   * Test de la mise a jour d'un groupe
   */
  public function testGroupUpdate()
  {
    $this->client = self::createClient();
    $this->connectUser('bob', 'toor');
    
    $Fans_de_psytrance = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')->findOneByName('Fans de psytrance');
    $psytrance_id = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Psytrance')->getId();
    $electro_id   = $this->getDoctrine()->getRepository('MuzichCoreBundle:Tag')->findOneByName('Electro')->getId();
    
    // Le groupe n'a pas encore les modifs que nous voulons effectuer
    $group = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')
      ->findOneBy(array(
          'name' => 'Les Fans de Psytrance'
      ))
    ;
    
    $this->assertTrue(is_null($group));
    
    // On se rend sur la page de listing des groupes possédés
    $this->crawler = $this->client->request('GET', $this->generateUrl('groups_own_list'));
    
    // Il y a bien le lien vers son groupe
    $this->exist('a[href="'.($url = $this->generateUrl('show_group', array('slug' => $Fans_de_psytrance->getSlug()))).'"]');
    $link = $this->selectLink('a[href="'.$url.'"]');
    // On clique dessus
    $this->clickOnLink($link);
    $this->isResponseSuccess();
    
    // On est sur la page du groupe, bob doit voir le lien vers la page d'edition
    $this->exist('a[href="'.($url = $this->generateUrl('group_edit', array('slug' => $Fans_de_psytrance->getSlug()))).'"]');
    $link = $this->selectLink('a[href="'.$url.'"]');
    // On clique dessus
    $this->clickOnLink($link);
    $this->isResponseSuccess();
    
    // On vérifie al présence du forumaire
    $this->exist('form[action="'.($url = $this->generateUrl('group_update', array('slug' => $Fans_de_psytrance->getSlug()))).'"]');
    $this->exist('form[action="'.$url.'"] input[id="group_name"]');
    $this->exist('form[action="'.$url.'"] textarea[id="group_description"]');
    $this->exist('form[action="'.$url.'"] input[type="submit"]');
    
    // On valide ce formulaire
    $form = $this->selectForm('form[action="'.$url.'"] input[type="submit"]');
    $form['group[name]'] = 'Les Fans de Psytrance';
    $form['group[description]'] = 'Ca va swiguer !';
    $form['group[tags]['.$psytrance_id.']'] = $psytrance_id;
    // On rajoute le lien vers le tag electro
    $form['group[tags]['.$electro_id.']'] = $electro_id;
    $this->submit($form);
    
    $this->isResponseRedirection();
    $this->followRedirection();
    $this->isResponseSuccess();
    
    // Le groupe est bien modifié en base
    $group = $this->getDoctrine()->getRepository('MuzichCoreBundle:Group')
      ->findOneBy(array(
        'name'        => 'Les Fans de Psytrance',
        'description' => 'Ca va swiguer !'
      ))
    ;
    
    $this->assertTrue(!is_null($group));
    
    // On vérifie egallement le lien avec les tags
    if (!is_null($group))
    {
      $group_tags = $this->getDoctrine()->getRepository('MuzichCoreBundle:GroupsTagsFavorites')
        ->findBy(array(
          'group' => $group->getId(),
          'tag'   => $psytrance_id
        ))
      ;
      
      $this->assertEquals(1, count($group_tags));
      
      $group_tags = $this->getDoctrine()->getRepository('MuzichCoreBundle:GroupsTagsFavorites')
        ->findBy(array(
          'group' => $group->getId(),
          'tag'   => $electro_id
        ))
      ;
      
      $this->assertEquals(1, count($group_tags));      
    }
    
    // Et sur la page aussi
    $this->exist('h2:contains("Les Fans de Psytrance")');
  }
  
}