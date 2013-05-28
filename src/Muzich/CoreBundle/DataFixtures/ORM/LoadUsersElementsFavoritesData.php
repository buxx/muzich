<?php

namespace Muzich\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Muzich\CoreBundle\Entity\UsersElementsFavorites;
use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Managers\PlaylistManager;

class LoadUsersElementsFavoritesData  extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
  
  private $container;
  private $entity_manager;
  
  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
  }
  
  public function getOrder()
  {
    return 102; // the order in which fixtures will be loaded
  }
  
  /**
   *  
   */
  protected function createRecord(User $user, $element)
  {
    $favorite = new UsersElementsFavorites();
    $favorite->setUser($user);
    $favorite->setElement($element);
    $this->entity_manager->persist($favorite);
    //$this->addReference('user_tag_'.$user->getId().'_'.$tag->getId(), $userTag);
  }
  
  protected function createPlaylist($name, User $user, $public, $elements)
  {
    $playlist_manager = new PlaylistManager($this->entity_manager);
    $playlist = $playlist_manager->getNewPlaylist($user);
    $playlist->setPublic($public);
    $playlist->setName($name);
    
    $playlist_manager->addElementsToPlaylist($elements, $playlist);
    $this->entity_manager->persist($playlist);
    return $playlist;
  }
  
  public function load(ObjectManager $entity_manager)
  {
    $this->entity_manager = $entity_manager;

    // favoris de bux
    $bux = $this->entity_manager->merge($this->getReference('user_bux'));
    $paul = $this->entity_manager->merge($this->getReference('user_paul'));
    
    $youtube_heretik_1 = $this->getReference('element_youtube_heretik_1');
    $youtube_djfab_1 = $this->getReference('element_youtube_djfab_1');
    $jamendo_caio_1 = $this->getReference('element_jamendo_caio_1');
    
    $this->createRecord($bux, $youtube_heretik_1);
    $this->createRecord($bux, $youtube_djfab_1);
    $this->createRecord($paul, $youtube_heretik_1);
    $this->createRecord($paul, $jamendo_caio_1);
    
    // Playlists
    $playlist1 = $this->createPlaylist("Un peu de basses ?",
      $bux, true, array(
        $this->getReference('element_soulfly_1'),
        $youtube_heretik_1,
        $youtube_djfab_1,
        $jamendo_caio_1,
        $this->getReference('element_youtube_dtc_passdrop'),
        $this->getReference('element_youtube_antroppod_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_djantoine_1'),
        $this->getReference('element_youtube_acroyek_1'),
        $this->getReference('element_jamendo_caio_1'),
        $this->getReference('element_jamendo_reverb_1'),
        $this->getReference('element_jamendo_cardio_1'),
        $this->getReference('element_dudeldrum'),
        $this->getReference('element_infected_psycho'),
        $this->getReference('element_infected_muse'),
        $this->getReference('element_joelle_1'),
        $this->getReference('element_joelle_2'),
        $this->getReference('element_ukf_1'),
        $this->getReference('element_beatbox_1'),
        $this->getReference('element_soulfly_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_youtube_koinkoin_1'),
        $this->getReference('element_soulfly_1')
      ));
    
    $playlist2 = $this->createPlaylist("Ma playlist perso",
    $bux, false, array($youtube_heretik_1, $youtube_djfab_1));
    
    $playlist_manager = new PlaylistManager($this->entity_manager);
    $playlist_manager->addPickedPlaylistToUser($paul, $playlist1);
    
    $this->entity_manager->flush();
  }
}