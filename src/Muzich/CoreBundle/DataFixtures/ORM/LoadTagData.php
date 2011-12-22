<?php

namespace Muzich\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Muzich\CoreBundle\Entity\Tag;

class LoadTagData  extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
  
  private $container;
  private $entity_manager;
  
  public function setContainer(ContainerInterface $container = null)
  {
    $this->container = $container;
  }
  
  public function getOrder()
  {
    return 2; // the order in which fixtures will be loaded
  }
  
  /**
   *  
   */
  protected function createTag($name)
  {
    $tag = new Tag();
    $tag->setName(ucfirst($name));
    $this->entity_manager->persist($tag);
    $this->addReference('tag_'.strtolower(str_replace(' ', '-', $name)), $tag);
  }
  
  public function load($entity_manager)
  {
    $this->entity_manager = $entity_manager;

    $tags_names = array(
        
    '2 Step',

    'A cappella',
    'Acousmatique',
    'Acidcore',
    'Acid breaks',
    'Acid house',
    'Acid rock',
    'Acid Folk',
    'Acid techno',
    'Acid trance',
    'Allaoui',
    'Arfa',
    'Ambient Minimaliste',
    'Ambient Jungle',
    'Ambient Techno',
    'Ambient House',
    'Anarcho-punk',
    'Art acousmatique',
    'Avant-gardiste',
    'Abstract hip-hop',
    'Acid jazz',
    'Alternative hip-hop',
    'Ambient',
    'Axé',

    'Baroque',
    'Brass Band',
    'Bachata',
    'Bachatango',
    'Bacalao',
    'Baile funk',
    'Ballade',
    'Ballet',
    'Batcave',
    'Beat',
    'Bebop',
    'Berceuse',
    'Big band',
    'Biguine',
    'Black metal',
    'Blues',
    'Bluegrass',
    'Boléro',
    'Boogie',
    'Bossa-Nova',
    'Big beat',
    'Braindance',
    'Breakbeat',
    'Breakcore',
    'Bretonne',
    'Brit House',
    'Britpop',
    'Broken beat',
    'Brutal death metal',

    'Cabaret',
    'Cadienne',
    'Calypso',
    'Capoeira',
    'Celtique',
    'Chaâbi',
    'Cha-cha-cha',
    'Chabada',
    'Changüi',
    'Chant grégorien',
    'Chanson française',
    'Charleston',
    'Chicago House',
    'Chœur',
    'Classique',
    'Club',
    'Coimbra',
    'Coldwave',
    'Comptine',
    'Contradanza',
    'Country',
    'Crossover',
    'Crust',
    'Crunk',
    'Culte',
    'Cumbia',

    'Dance',
    'Dance-Pop',
    'Dance-Punk',
    'Dance-Rock',
    'Dancehall',
    'Dancehall-Reggae',
    'Dark ambient',
    'Dark house',
    'Dark metal',
    'Darkstep',
    'Darksynth',
    'Darkwave',
    'Death metal',
    'Death Rock',
    'Deep House',
    'Detroit house',
    'Dirty South',
    'Disco',
    'Disco house',
    'Diva house',
    'Doom metal',
    'Doo-wop',
    'Down tempo',
    'Dream',
    'Drill\'n bass',
    'Drum and bass',
    'Dub',
    'Dubstep',
    'Dub poetry',
    'Dub house',

    'East Coast',
    'EBM',
    'Electro',
    'Electroacoustique',
    'Electro Boogie',
    'Electro-Goth',
    'Electro industriel',
    'Electro Jazz',
    'Electro rock',
    'Electroclash',
    'Electronic Body Music',
    'Electronique',
    'Electro-pop',
    'Émergente',
    'Emocore',
    'Ethnique',
    'Eurobeat',
    'Euroclash',
    'Eurodance',
    'Euro Disco',
    'Euro-Trance',
    'Euro-Pop',
    'Expérimentale',

    'Fado',
    'Fanfare',
    'Filin',
    'Flamenco',
    'Folk',
    'Folk metal',
    'Forrò',
    'Fox-trot',
    'Freestyle',
    'Fugue',
    'Funk',
    'French touch',

    'Gangsta Funk',
    'Gabber',
    'Gangsta Rap',
    'Garage Rock',
    'Garage House',
    'Gavotte',
    'Geek Rock',
    'Gigue (danse)',
    'Glam rock',
    'Glitchcore',
    'Goregrind',
    'Gospel',
    'Gospel Rock',
    'Gospel Pop',
    'Gothabilly',
    'Goth Rock',
    'Gothic metal',
    'Gnaouas',
    'Grindcore',
    'Grindie',
    'Grunge',

    'Habanera',
    'Hair metal',
    'Handsup',
    'Happy hardcore',
    'Hard house',
    'Hard rock',
    'Hard trance',
    'Hard-core',
    'Hardtek',
    'Hardstyle',
    'Heavy metal',
    'Hellbilly',
    'Hi-NRG',
    'Hip-hop',
    'Hip-house',
    'House',
    'House progressive',
    'Horror punk',

    'IDM',
    'Illbient',
    'Indie dance',
    'Indie Rock',
    'Indie pop',
    'Indie Folk',
    'Industrial Metal',
    'Industrial Rock',
    'Industriel',
    'Instrumental',
    'Irlandaise',
    'Italo Disco',
    'ItaloDance',

    'Javanaise',
    'JPop',
    'Jrock',
    'Java',
    'Jazz',
    'Jazz-rock fusion',
    'Jazz Rap',
    'Jazz house',
    'Jerk',
    'jumpstyle',
    'jungle',

    'Kizomba',
    'Klezmer',
    'Kompa',
    'Kpop',
    'Krautrock',
    'Kuduro',

    'Latin',
    'Latin House',
    'Locale',
    'Lo-fi',
    'Logobi',
    'Louange',
    'Lounge',
    'Lied',

    'Madison',
    'Mainstream rap',
    'Makina',
    'Maloya',
    'Mambo',
    'Marche',
    'Mashup',
    'Mazurka',
    'Mbalax',
    'Mediatif',
    'Menuet',
    'Merengue',
    'Merengue House',
    'Merengue Rap',
    'Musique Gothique',
    'Metal',
    'Metal Alternatif',
    'Metalcore',
    'Metal chrétien',
    'Metal progressif',
    'Metal symphonique',
    'Microhouse',
    'Milonga',
    'Minimaliste',
    'Musique classique',
    'Musique concrète',
    'Musique électroacoustique',
    'Murga',
    'Musette',

    'Ndombolo',
    'Negro spiritual',
    'Néofolk',
    'Néo Classicisme',
    'Néo Jazz',
    'Néo Soul',
    'Néo-trad',
    'New Age',
    'New Prog',
    'New Beat',
    'New wave',
    'New York House',
    'Nocturne',
    'No wave',
    'Noise',
    'Noisy Pop',
    'Nu-NRG',
    'Nu Italo',
    'Nu Italo Disco',
    'Nu Jazz',
    'Nu Metal',
    'Nu Roots',
    'Nu Synthpop',
    'Nueva cancion',
    'Nueva trova',
    'NWOBHM',

    'Oi!',
    'Old school Rap',
    'Old school Acid',
    'Oldies',
    'Opéra',
    'Opéra-comique',
    'Opéra rock',
    'Oratorio',

    'Pachanga',
    'Pagan metal',
    'Paillarde',
    'Party House',
    'Paso doble',
    'Pidikhtos',
    'Pirate metal',
    'Polka',
    'Polonaise',
    'Pop',
    'Pop progressive',
    'Pop Experimental',
    'Pop-Folk',
    'Pop-punk',
    'Pop-Rock',
    'PortoDance',
    'Post Industrial',
    'Post-Punk',
    'Post-rock',
    'Power ballad',
    'Power metal',
    'Prélude',
    'Progressif',
    'Progressive House',
    'Psychédélique',
    'Psychobilly',
    'Psytrance',
    'Punk rock',

    'Rhythm and Blues',
    'R\'n\'B',
    'Rabiz',
    'Nu\'R\'n\'b',
    'RAC',
    'Raï',
    'Raï\'n\'b',
    'Rap',
    'Rave',
    'Ragga',
    'Rébétiko',
    'Reggae',
    'Reggae Fusion',
    'Reggaeton',
    'Retro',
    'Rhapsodie',
    'Rigodon',
    'Rock',
    'Rock alternatif',
    'Rock alternatif latino',
    'Rock canadien',
    'Rock celtique',
    'Rock indépendant',
    'Rock noisy',
    'Rock \'n\' roll',
    'Rockabilly',
    'Romantique',
    'Ronde',
    'Rondo',
    'Roots',
    'Rumba',
    'Rumba flamenca',
    'Rumba Catalane',
    'Rumba congolaise',
    'Rygalda Rock',

    'Salsa',
    'Salsaton',
    'Salsa-ragga',
    'Salsa Rock',
    'Salsa Samba',
    'Salsa Romantica',
    'Salsa Erotica',
    'Samba',
    'Sarabande',
    'Saudade',
    'Scherzo',
    'Screamo',
    'Séga',
    'Seggae',
    'Semba',
    'Shoegazing',
    'Sirtaki',
    'Slow',
    'Slow Jam',
    'Slow fox',
    'Slow-Rock',
    'Ska',
    'Skacore',
    'Ska-jazz',
    'Ska-punk',
    'Skate punk',
    'Slam',
    'Smooth jazz',
    'Soca',
    'Sonate',
    'Sonatine',
    'Son cubain',
    'Songo',
    'Soul',
    'Southern rock',
    'Space rock',
    'Speed metal',
    'Soukous',
    'Swing',
    'Symphonie',
    'Synth Pop',
    'Synth Funk',
    'Synthpunk',
    'Shibuya-Kei',
    'Stadium House',
    'Speed Garage',
    'Speedcore',

    'Tango',
    'Tango argentin',
    'Tarentelle',
    'Tech House',
    'Technical death metal',
    'Teen Pop',
    'Techno',
    'Techno minimaliste',
    'Techno hardcore',
    'Tecktonik',
    'Tekfunk',
    'Thrash metal',
    'Timba',
    'Toccata',
    'Trailer',
    'Trad Rock',
    'Trance',
    'Trance-Goa',
    'Trance psychédélique',
    'Trance progressive',
    'Tribal House',
    'Tribal Techno',
    'Trip hop',
    'Trip Rock',
    'Tumba',
    'Twee pop',
    'Twist',

    'Unblack metal',

    'Valse',
    'Valse lente',
    'Valse péruvienne',
    'Valse tyrolienne',
    'Visual Kei',
    'Viking metal',
    'Vocal Jazz',
    'Vocal house',
    'Vocal trance',

    'Washboard',
    'World Beat',
    'World music',
    'Worship',
    'Worship Pop-Rock',
    'Worship-rock',

    'X-Over',

    'Yela',
    'Yirmi',
    'Yal',

    'Zouglou',
    'Zouk',
    'Zydeco',

    'tribe',
    'medieval',
    'chanteuse',
    'beatbox',
    'hardcore',
    'minimal',
    'melancolique',
    'mellow',
    'melodique'
    
    );
        
    foreach ($tags_names as $tag_name)
    {
      $this->createTag($tag_name);
    }

    $this->entity_manager->flush();
  }
}