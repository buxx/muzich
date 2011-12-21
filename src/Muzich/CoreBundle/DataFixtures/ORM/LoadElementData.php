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
    return 6;
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
  protected function createElement($reference_id, $name, $url, $tags, $type, $owner, $group = null, $date = null)
  {    
    $element = new Element();
    $element->setName(ucfirst($name));
    $element->setUrl($url);
    $element->setType($type);
    $element->setOwner($owner);
    if ($date)
    {
      $date_object = new \DateTime($date);
      $element->setCreated($date_object);
      $element->setUpdated($date_object);
    }
    if ($group)
      $element->setGroup($group);
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
    
    // Timestampable stuff
    $evm = new \Doctrine\Common\EventManager();
    // ORM and ORM
    $timestampableListener = new \Gedmo\Timestampable\TimestampableListener();
    $evm->addEventSubscriber($timestampableListener);
    // now this event manager should be passed to entity manager constructor
    $this->entity_manager->getEventManager()->addEventSubscriber($timestampableListener);

    // 
    $bux  = $this->entity_manager->merge($this->getReference('user_bux'));
    $jean = $this->entity_manager->merge($this->getReference('user_jean'));
    $paul = $this->entity_manager->merge($this->getReference('user_paul'));
    $bob  = $this->entity_manager->merge($this->getReference('user_bob'));
    $joelle  = $this->entity_manager->merge($this->getReference('user_joelle'));

    // 'youtube', 'soundclound', 'son2teuf', 'jamendo'
    $youtube     = $this->entity_manager->merge($this->getReference('element_type_youtube.com'));
    $soundclound = $this->entity_manager->merge($this->getReference('element_type_soundcloud.com'));
    $son2teuf    = $this->entity_manager->merge($this->getReference('element_type_son2teuf.org'));
    $jamendo     = $this->entity_manager->merge($this->getReference('element_type_jamendo.com'));
    
    $this->createElement('youtube_heretik_1', 'Heretik System Popof - Resistance', 
      'http://www.youtube.com/watch?v=tq4DjQK7nsM',
      $this->getArrayOfTag(array('hardtek')),
    $youtube, $bux, null, '2011-12-10 17:35:07');
    
    $this->createElement('youtube_dtc_passdrop', 'dtc che passdrop', 
      'http://www.youtube.com/watch?v=2A4buFCp7qM', 
      $this->getArrayOfTag(array('hardtek')),
    $youtube, $bux, null, '2011-12-10 18:35:07');
    
    $this->createElement('youtube_antroppod_1', 'Antropod - Polakatek', 
      'http://www.youtube.com/watch?v=VvpF3lCh1hk&NR=1', 
      $this->getArrayOfTag(array('hardtek')),
    $youtube, $bux, null, '2011-12-10 19:45:07');
    
    $this->createElement('youtube_koinkoin_1', 'koinkOin - H5N1', 
      'http://www.son2teuf.org/Voir-details/Sons/Lives/Hardtek-_-Tribe/koinkOin-_-H5N1', 
      $this->getArrayOfTag(array('hardtek', 'electro')),
    $youtube, $bux, null, '2011-12-10 21:35:07');
    
    
    $this->createElement('youtube_djfab_1', 'DJ FAB', 
      'http://www.jamendo.com/fr/album/42567', 
      $this->getArrayOfTag(array('hardtek')),
    $jamendo, $jean, null, '2011-11-11 17:35:07');
    
    $this->createElement('youtube_djantoine_1', 'dj antoine', 
      'http://www.jamendo.com/fr/album/75206', 
      $this->getArrayOfTag(array('hardtek', 'tribe')),
    $jamendo, $jean, null, '2011-11-11 19:35:07');
    
    $this->createElement('youtube_acroyek_1', 'Acrotek Hardtek G01', 
      'http://www.jamendo.com/fr/album/3409', 
      $this->getArrayOfTag(array('hardtek')),
    $jamendo, $jean, null, '2011-12-11 14:35:07');
    
    
    $this->createElement('jamendo_caio_1', 'All Is Full Of Pain', 
      'http://soundcloud.com/keytek/all-is-full-of-pain', 
      $this->getArrayOfTag(array('tribe', 'hardtek')),
    $soundclound, $paul, null, '2011-12-02 01:35:07');
    
    $this->createElement('jamendo_reverb_1', 'RE-FUCK (ReVeRB_FBC) mix.', 
      'http://soundcloud.com/reverb-2/re-fuck-reverb_fbc-mix', 
      $this->getArrayOfTag(array('tribe')),
    $soundclound, $paul, null, '2011-12-04 14:35:07');
    
    $this->createElement('jamendo_cardio_1', 'CardioT3K - Juggernaut Trap', 
      'http://soundcloud.com/cardiot3k/cardiot3k-juggernaut-trap', 
      $this->getArrayOfTag(array('tribe')),
    $soundclound, $paul, null, '2011-12-12 13:35:07');
    
    $this->createElement('dudeldrum', 'DUDELDRUM', 
      'http://www.jamendo.com/fr/album/89109', 
      $this->getArrayOfTag(array('medieval')),
      $jamendo, $joelle,
      $this->entity_manager->merge($this->getReference('group_dudeldrum'))
      , '2011-12-16 18:11:07'
    );
    
    $this->createElement('infected_psycho', 'Infected Mushroom - Psycho', 
      'http://www.youtube.com/watch?v=dLWXSsYJoWY', 
      $this->getArrayOfTag(array('psytrance')),
      $youtube, $paul,
      $this->entity_manager->merge($this->getReference('group_fan_de_psytrance'))
      , '2011-12-10 16:11:17'
    );
    
    $this->createElement('infected_muse', 'Infected mushroom - Muse Breaks', 
      'http://www.youtube.com/watch?v=g0Cbfm1PStA', 
      $this->getArrayOfTag(array('psytrance')),
      $youtube, $bob,
      $this->entity_manager->merge($this->getReference('group_fan_de_psytrance'))
      , '2011-12-08 17:35:07'
    );
    
    $this->createElement('joelle_1', 'Cents Pas - Joëlle', 
      'http://www.youtube.com/watch?v=bIAFB4vRdGw', 
      $this->getArrayOfTag(array('chanteuse')),
      $youtube, $joelle, null, '2011-12-08 14:21:07'
    );
    
    $this->createElement('joelle_2', 'Cents Pas - Joëlle (bis)', 
      'http://www.youtube.com/watch?v=bIAFB4vRdGw', 
      $this->getArrayOfTag(array('chanteuse')),
      $youtube, $joelle,
      $this->entity_manager->merge($this->getReference('group_joelle'))
      , '2011-12-07 14:12:07'
    );
    
    $this->createElement('ukf_1', 'UKF Dubstep Mix - August ', 
      'http://www.youtube.com/watch?v=SFu2DfPDGeU', 
      $this->getArrayOfTag(array('dubstep')),
      $youtube, $joelle, null, '2011-12-10 00:35:07'
    );
    
    $this->createElement('beatbox_1', 'Dubstep Beatbox', 
      'http://www.dailymotion.com/video/xm5omz_dubstep-beatbox_creation', 
      $this->getArrayOfTag(array('dubstep', 'beatbox')),
      null, $joelle, null, '2011-12-10 11:11:11'
    );
    
    $this->createElement('soulfly_1', 'SOULFLY - Prophecy', 
      'http://www.youtube.com/watch?v=zCc_jLctZkA', 
      $this->getArrayOfTag(array('metal')),
      $youtube, $bux, null, '2011-12-12 17:39:07'
    );
    
    $this->createElement('azyd_azylum_1', 'AZYD AZYLUM Live au Café Provisoire', 
      'http://www.youtube.com/watch?v=8AXhRXAt2E4', 
      $this->getArrayOfTag(array('metal')),
      $youtube, $bux, null, '2011-11-11 11:11:11'
    );
    
    $this->createElement('babylon_pression_1', 'Babylon Pression - Des Tasers et des Pauvres', 
      'http://www.youtube.com/watch?v=XWkbaHxRvds&feature=related', 
      $this->getArrayOfTag(array('metal', 'hardcore')),
      $youtube, $bux, null, '2011-12-14 18:35:07'
    );
    
    $this->createElement('ed_cox_1', 'Ed Cox - La fanfare des teuffeurs (Hardcordian)', 
      'http://www.youtube.com/watch?v=Lk1gnh-JCDs&feature=related', 
      $this->getArrayOfTag(array('electro')),
      $youtube, $bux, null, '2011-12-15 21:35:07'
    );
    

    $this->entity_manager->flush();
  }
}