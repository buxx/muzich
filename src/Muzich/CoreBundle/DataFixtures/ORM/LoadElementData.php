<?php

namespace Muzich\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Managers\CommentsManager;

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
  protected function createElement($reference_id, $name, $url, $tags, $type, $owner, $group = null, $date = null, $comments = null)
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
    
    $element->setComments($comments);
    
    $this->entity_manager->persist($element);
  }
  
  protected function dateD($ecal)
  {
    return date('Y-m-d H:i:s', time() - 60 * 60 *24 * $ecal);
  }
  
  public function load(ObjectManager $entity_manager)
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
    
    $this->createElement('youtube_heretik_1', 'Heretik System Popof - Resistance', 
      'http://www.youtube.com/watch?v=tq4DjQK7nsM',
      $this->getArrayOfTag(array('hardtek')),
    'youtube.com', $bux, null, $this->dateD(200));
    
    $this->createElement('youtube_dtc_passdrop', 'dtc che passdrop', 
      'http://www.youtube.com/watch?v=2A4buFCp7qM', 
      $this->getArrayOfTag(array('hardtek')),
    'youtube.com', $bux, null, $this->dateD(199));
    
    $this->createElement('youtube_antroppod_1', 'Antropod - Polakatek', 
      'http://www.youtube.com/watch?v=VvpF3lCh1hk&NR=1', 
      $this->getArrayOfTag(array('hardtek')),
    'youtube.com', $bux, null, $this->dateD(198));
    
    $this->createElement('youtube_koinkoin_1', 'koinkOin - H5N1', 
      'http://www.son2teuf.org/Voir-details/Sons/Lives/Hardtek-_-Tribe/koinkOin-_-H5N1', 
      $this->getArrayOfTag(array('hardtek', 'electro')),
    'son2teuf.org', $bux, null, $this->dateD(197));
    
    
    $this->createElement('youtube_djfab_1', 'DJ FAB', 
      'http://www.jamendo.com/fr/album/42567', 
      $this->getArrayOfTag(array('hardtek')),
    'jamendo.com', $jean, null, $this->dateD(196));
    
    $this->createElement('youtube_djantoine_1', 'dj antoine', 
      'http://www.jamendo.com/fr/album/75206', 
      $this->getArrayOfTag(array('hardtek', 'tribe')),
    'jamendo.com', $jean, null, $this->dateD(195));
    
    $this->createElement('youtube_acroyek_1', 'Acrotek Hardtek G01', 
      'http://www.jamendo.com/fr/album/3409', 
      $this->getArrayOfTag(array('hardtek')),
    'jamendo.com', $jean, null, $this->dateD(194));
    
    
    $this->createElement('jamendo_caio_1', 'All Is Full Of Pain', 
      'http://soundcloud.com/keytek/all-is-full-of-pain', 
      $this->getArrayOfTag(array('tribe', 'hardtek')),
    'soundcloud.com', $paul, null, $this->dateD(193));
    
    $this->createElement('jamendo_reverb_1', 'RE-FUCK (ReVeRB_FBC) mix.', 
      'http://soundcloud.com/reverb-2/re-fuck-reverb_fbc-mix', 
      $this->getArrayOfTag(array('tribe')),
    'soundcloud.com', $paul, null, $this->dateD(192));
    
    $this->createElement('jamendo_cardio_1', 'CardioT3K - Juggernaut Trap', 
      'http://soundcloud.com/cardiot3k/cardiot3k-juggernaut-trap', 
      $this->getArrayOfTag(array('tribe')),
    'soundcloud.com', $paul, null, $this->dateD(191));
    
    $this->createElement('dudeldrum', 'DUDELDRUM', 
      'http://www.jamendo.com/fr/album/89109', 
      $this->getArrayOfTag(array('medieval')),
      'jamendo.com', $joelle,
      $this->entity_manager->merge($this->getReference('group_dudeldrum'))
      , $this->dateD(190)
    );
    
    $this->createElement('infected_psycho', 'Infected Mushroom - Psycho', 
      'http://www.youtube.com/watch?v=dLWXSsYJoWY', 
      $this->getArrayOfTag(array('psytrance')),
      'youtube.com', $paul,
      $this->entity_manager->merge($this->getReference('group_fan_de_psytrance'))
      , $this->dateD(189)
    );
    
    $this->createElement('infected_muse', 'Infected mushroom - Muse Breaks', 
      'http://www.youtube.com/watch?v=g0Cbfm1PStA', 
      $this->getArrayOfTag(array('psytrance')),
      'youtube.com', $bob,
      $this->entity_manager->merge($this->getReference('group_fan_de_psytrance'))
      , $this->dateD(188)
    );
    
    $this->createElement('joelle_1', 'Cents Pas - Joëlle', 
      'http://www.youtube.com/watch?v=bIAFB4vRdGw', 
      $this->getArrayOfTag(array('chanteuse')),
      'youtube.com', $joelle, null, $this->dateD(187)
    );
    
    $this->createElement('joelle_2', 'Cents Pas - Joëlle (bis)', 
      'http://www.youtube.com/watch?v=bIAFB4vRdGw', 
      $this->getArrayOfTag(array('chanteuse')),
      'youtube.com', $joelle,
      $this->entity_manager->merge($this->getReference('group_joelle'))
      , $this->dateD(186)
    );
    
    $this->createElement('ukf_1', 'UKF Dubstep Mix - August ', 
      'http://www.youtube.com/watch?v=SFu2DfPDGeU', 
      $this->getArrayOfTag(array('dubstep')),
      'youtube.com', $joelle, null, $this->dateD(185)
    );
    
    $this->createElement('beatbox_1', 'Dubstep Beatbox', 
      'http://www.dailymotion.com/video/xm5omz_dubstep-beatbox_creation', 
      $this->getArrayOfTag(array('dubstep', 'beatbox')),
      'dailymotion.com', $joelle, null, $this->dateD(184)
    );
    
    $this->createElement('soulfly_1', 'SOULFLY - Prophecy', 
      'http://www.youtube.com/watch?v=zCc_jLctZkA', 
      $this->getArrayOfTag(array('metal')),
      'youtube.com', $bux, null, $this->dateD(183)
    );
    
    $cm = new CommentsManager();
    $cm->add($joelle, "J'aime bien quand ça tape. Ca rapelle ".
      "le grincement sinistre des volets de vieilles ".
      "maisons. D'ailleur j'ai repeint mon mur des shiots !", $this->dateD(180));
    
    $this->createElement('azyd_azylum_1', 'AZYD AZYLUM Live au Café Provisoire', 
      'http://www.youtube.com/watch?v=8AXhRXAt2E4', 
      $this->getArrayOfTag(array('metal')),
      'youtube.com', $bux, null, $this->dateD(182),
      $cm->get()
    );
    
    $cm = new CommentsManager();
    $cm->add($bux, "Je commenteuuh nanana 1", $this->dateD(180));
    $cm->add($paul, "Je répond 2", $this->dateD(180));
    $cm->add($bux, "Je répond 3", $this->dateD(179));
    $cm->add($paul, "Je répond 4", $this->dateD(178));
    $cm->add($bux, "Je répond 5", $this->dateD(177));
    $cm->add($paul, "Je répond 6", $this->dateD(176));
    $cm->add($bux, "Je répond 7", $this->dateD(175));
    $cm->add($paul, "Je répond 8", $this->dateD(174));
    $cm->add($bux, "Je répond 9", $this->dateD(173));
    $cm->add($paul, "Je répond 10", $this->dateD(172));
    $cm->add($bux, "Je répond 11", $this->dateD(161));
    $cm->add($paul, "Je répond 12", $this->dateD(150));
    $cm->add($bux, "Je répond 13", $this->dateD(140));
    
    $this->createElement('babylon_pression_1', 'Babylon Pression - Des Tasers et des Pauvres', 
      'http://www.youtube.com/watch?v=XWkbaHxRvds&feature=related', 
      $this->getArrayOfTag(array('metal', 'hardcore')),
      'youtube.com', $bux, null, $this->dateD(181),
      $cm->get()
    );
    
    $cm = new CommentsManager();
    $cm->add($bux, "C'est trop bon hein ?", $this->dateD(180));
    $cm->add($paul, "C'est pas mal en effet", $this->dateD(180));
        
    $this->createElement('ed_cox_1', 'Ed Cox - La fanfare des teuffeurs (Hardcordian)', 
      'http://www.youtube.com/watch?v=Lk1gnh-JCDs&feature=related', 
      $this->getArrayOfTag(array('electro')),
      'youtube.com', $bux, null, $this->dateD(180),
      $cm->get()
    );
    

    $this->entity_manager->flush();
  }
}