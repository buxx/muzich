<?php

namespace Muzich\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Muzich\CoreBundle\Entity\Element;

class LoadElementData  extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
  
  private $container;
  private $entity_manager;
  
  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
  }
  
  public function getOrder()
  {
    return 4;
  }
  
  protected function getArrayOfTag($names)
  {
    $tags = array();
    foreach ($names as $name)
    {
      $tags[] = $this->entity_manager->merge($this->getReference('tag_'.$name));
    }
    return $tags;
  }
  
  /**
   *  
   */
  protected function createElement($reference_id, $name, $url, $tags, $type, $owner, $date = null)
  {
    if (!$date)
    {
      $date = new \DateTime();
    }
    
    $element = new Element();
    $element->setName(ucfirst($name));
    $element->setUrl($url);
    $element->setType($type);
    $element->setOwner($owner);
    $element->setDateAdded($date);
    $this->addReference('element_'.$reference_id, $element);
    
    foreach ($tags as $tag)
    {
      $element->addTag($tag);
    }
    
    $this->entity_manager->persist($element);
  }
  
  public function load($entity_manager)
  {
    $this->entity_manager = $entity_manager;

    $bux  = $this->entity_manager->merge($this->getReference('user_bux'));
    $jean = $this->entity_manager->merge($this->getReference('user_jean'));
    $paul = $this->entity_manager->merge($this->getReference('user_paul'));
    $bob  = $this->entity_manager->merge($this->getReference('user_bob'));

    // 'youtube', 'soundclound', 'son2teuf', 'jamendo'
    $youtube     = $this->entity_manager->merge($this->getReference('element_type_youtube'));
    $soundclound = $this->entity_manager->merge($this->getReference('element_type_soundclound'));
    $son2teuf    = $this->entity_manager->merge($this->getReference('element_type_son2teuf'));
    $jamendo     = $this->entity_manager->merge($this->getReference('element_type_jamendo'));
    
    $this->createElement('youtube_heretik_1', 'Heretik System Popof - Resistance', 
      'http://www.youtube.com/watch?v=tq4DjQK7nsM',
      $this->getArrayOfTag(array('hardtek')),
    $youtube, $bux);
    
    $this->createElement('youtube_dtc_passdrop', 'dtc che passdrop', 
      'http://www.youtube.com/watch?v=2A4buFCp7qM', 
      $this->getArrayOfTag(array('hardtek')),
    $youtube, $bux);
    
    $this->createElement('youtube_antroppod_1', 'Antropod - Polakatek', 
      'http://www.youtube.com/watch?v=VvpF3lCh1hk&NR=1', 
      $this->getArrayOfTag(array('hardtek')),
    $youtube, $bux);
    
    $this->createElement('youtube_koinkoin_1', 'koinkOin - H5N1', 
      'http://www.son2teuf.org/Voir-details/Sons/Lives/Hardtek-_-Tribe/koinkOin-_-H5N1', 
      $this->getArrayOfTag(array('hardtek', 'electro')),
    $youtube, $bux);
    
    
    $this->createElement('youtube_djfab_1', 'DJ FAB', 
      'http://www.jamendo.com/fr/album/42567', 
      $this->getArrayOfTag(array('hardtek')),
    $jamendo, $jean);
    
    $this->createElement('youtube_djantoine_1', 'dj antoine', 
      'http://www.jamendo.com/fr/album/75206', 
      $this->getArrayOfTag(array('hardtek', 'tribe')),
    $jamendo, $jean);
    
    $this->createElement('youtube_acroyek_1', 'Acrotek Hardtek G01', 
      'http://www.jamendo.com/fr/album/3409', 
      $this->getArrayOfTag(array('hardtek')),
    $jamendo, $jean);
    
    
    $this->createElement('jamendo_caio_1', 'All Is Full Of Pain', 
      'http://soundcloud.com/keytek/all-is-full-of-pain', 
      $this->getArrayOfTag(array('tribe', 'hardtek')),
    $soundclound, $paul);
    
    $this->createElement('jamendo_reverb_1', 'RE-FUCK (ReVeRB_FBC) mix.', 
      'http://soundcloud.com/reverb-2/re-fuck-reverb_fbc-mix', 
      $this->getArrayOfTag(array('tribe')),
    $soundclound, $paul);
    
    $this->createElement('jamendo_cardio_1', 'CardioT3K - Juggernaut Trap', 
      'http://soundcloud.com/cardiot3k/cardiot3k-juggernaut-trap', 
      $this->getArrayOfTag(array('tribe')),
    $soundclound, $paul);

    $this->entity_manager->flush();
  }
}