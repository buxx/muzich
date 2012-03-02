<?php

namespace Muzich\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
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
  
  public function load(ObjectManager $entity_manager)
  {
    $this->entity_manager = $entity_manager;

//    // Slug stuff
//    $evm = new \Doctrine\Common\EventManager();
//    // ORM and ODM
//    $sluggableListener = new \Gedmo\Sluggable\SluggableListener();
//    $evm->addEventSubscriber($sluggableListener);
//    // now this event manager should be passed to entity manager constructor
//    $entity_manager->getEventManager()->addEventSubscriber($sluggableListener);
    
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
    'melodique',
    
    // au tour des instruments
    'aboès',
    'accordéon',
    'accordina',
    'acordo',
    'adjalin',
    'aérophon',
    'agogô',
    'alboka',
    'allun',
    'alto',
    'ampongabe',
    'antsiva',
    'appeau',
    'archicistre',
    'archiluth',
    'arghoul / argol',
    'atabal',
    'atabaque',
    'atranatrana / bakilo / katiboke',
    'autoharpe',
    'azina',

    'baglama',
    'bagpipe',
    'balafon',
    'balalaïka',
    'bandola',
    'bandonéon',
    'bandourka',
    'bandura',
    'bangdi / bāngdí',
    'banjo',
    'banshrî',
    'bant\'you',
    'barâtaka',
    'barbitos / barbiton',
    'baryton',
    'basse quatre cylindres',
    'bassethorn',
    'basson',
    'batá',
    'bâton de pluie',
    'batterie',
    'batterie électronique',
    'batyphon',
    'bawoo',
    'bayan',
    'béchonnet',
    'belek',
    'bendir',
    'bendré',
    'benju / dulcimer japonais',
    'berimbau',
    'biniou',
    'birbyne',
    'bitu-uvu',
    'biva / bûva',
    'biwa',
    'bobre',
    'bodega',
    'bodhrán',
    'boha',
    'bomba',
    'bombarde',
    'bombardon',
    'bombo',
    'bongo(s)',
    'bourdon',
    'bouzouki',
    'bratsch / braci / brace / contrã',
    'bugle',
    'buhai',
    'bulbultara',

    'cabassa',
    'cabrette',
    'cài ban nhac',
    'cài chuong chua',
    'cài dan nha tro',
    'cài kèn dôi',
    'cài-nhi / dou-co',
    'caisse claire',
    'caisse',
    'caisse roulante',
    'caixa',
    'cajón',
    'calebasse',
    'çaradiya-vina',
    'carémère / caramèra',
    'carillon',
    'carillon de fanfare',
    'carnyx',
    'castagnettes',
    'çauktika-vina',
    'caval',
    'cavaquinho',
    'caxixi',
    'célesta',
    'cellulophone',
    'chabrette',
    'chalemie',
    'chalumeau',
    'chang-kou',
    'chapeau chinois',
    'chapey',
    'charango',
    'cheipour',
    'chitarrone',
    'chkacheks',
    'choro',
    'choron',
    'cialamella',
    'cistre',
    'cistre français',
    'cistre japonais',
    'cithare',
    'cithare anglaise',
    'cithare japonaise',
    'citole chinoise',
    'clairon',
    'clarinette',
    'clarinette basse',
    'clavecin',
    'claves',
    'clavicorde',
    'clavicytherium',
    'clavinet',
    'cloche',
    'cloches de bois',
    'cloches tubulaires',
    'cobza',
    'colachon',
    'collier de grelots',
    'concertina',
    'congas',
    'conque',
    'contrebasse à cordes',
    'contrebasse à vent',
    'contrebasson',
    'cor à pistons',
    'cor anglais',
    'cor d\'harmonie / cor moderne',
    'cor de basset',
    'cor de chasse',
    'cor des Alpes',
    'cor naturel',
    'cornamuse',
    'corne',
    'corne d\'appel',
    'corne de brume',
    'cornemuse',
    'cornet à bouquin',
    'cornet à pistons',
    'cornu',
    'cosmophone',
    'courtaud',
    'cow antler',
    'cow bells',
    'cromorne',
    'crouth / crwth',
    'cuatro',
    'cuica',
    'cura',
    'cymbale antique',
    'cymbales',
    'cymbalum',

    'dabakan',
    'daf',
    'dâmâna',
    'danbolino',
    'danhoun',
    'danyen',
    'dap',
    'darbouka / darbuka / darbuqqa',
    'darvyra',
    'def',
    'dehol',
    'diapason',
    'didgeridoo',
    'dimplito',
    'dízi',
    'djembé',
    'djozé',
    'doedelzak',
    'dolcian / dulcian',
    'domra',
    'dòngxiāo',
    'dou-co / cài-nhi',
    'doudouçaine',
    'doudouk',
    'doutara',
    'Dubreq Stylophone',
    'duda',
    'dulcimer',
    'Dulcitone',
    'dulzaina / dultzania',
    'dum dum / dunun',

    'egg shaker',
    'eka-tantrika',
    'eka-tara',
    'e\'oud',
    'épinette',
    'épinette des Vosges',
    'erh-hou-hou',
    'erh-huang-hou',
    'erhu / èrhú',
    'esrar',
    'estive',
    'euphonium',

    'fa-haotong',
    'fakroum',
    'fango-fango',
    'fifre',
    'flabiol',
    'flageolet / flajol',
    'fläute de Behaigne (Bohême)',
    'flaviol',
    'flexatone',
    'fluierul mic',
    'flûte à bec',
    'flûte à bec double',
    'flûte béarnaise / flabuta / flutet / fluitet',
    'flûte d\'amour sioux / siotantka (bâton qui chante)',
    'flûte de Pan',
    'flûte de saule',
    'flûte en sol',
    'flûte harmonique',
    'flûte irlandaise',
    'flûte traversière',
    'forte / forte-piano',
    'foulé',
    'foyera',
    'frestel',
    'fujara',

    'gadoulka',
    'gaïda',
    'gaita',
    'gaita gallega',
    'galoubet',
    'gambri, gumbri, guinbri, guenbri, gembri / gunbri',
    'gamelan',
    'ganibri',
    'gankeke',
    'ganza',
    'gardon',
    'gasbâ',
    'gassaa',
    'geige',
    'gemshorn',
    'gendang-boeleo',
    'gheteh',
    'gig',
    'gigue',
    'girine',
    'gita',
    'glassharmonica',
    'glockenspiel',
    'gnaccara',
    'gong',
    'gongsa',
    'gonkogui',
    'gopi-yantra',
    'gota',
    'goto',
    'graïle',
    'grelots',
    'grosse caisse',
    'guedra',
    'guimbarde / muxukitarra',
    'guiro',
    'guitare',
    'guitare acoustique',
    'guitare basse',
    'guitare électrique',
    'guitare folk',
    'guitare mauresque',
    'guitarra',
    'guiterne / guitare latine',
    'guqin',
    'gusli / gusle',
    'gut-komm',
    'gŭzhēng',
    'guzla',

    'hackbrett',
    'halam sénégalais',
    'hangdoung',
    'hapetan de Sumatra',
    'harmonica',
    'harmonica à bouche',
    'harmonica à lames de verre',
    'harmonica à clavier',
    'harmonicor',
    'harmoniflûte',
    'harmonino',
    'harmonium',
    'harpe',
    'harpe à pédales',
    'harpe celtique',
    'harpe chinoise',
    'harpe d\'Afrique',
    'harpe des Pahouins',
    'harpe égyptienne',
    'harpu',
    'hautbois',
    'hautbois à capsule',
    'hautbois d\'amour',
    'heang-teih',
    'hegelung',
    'hélicon',
    'héliophone',
    'hiachi-riki / hichiriki',
    'hochet',
    'hochet à grelots',
    'horn bugle',
    'hornpipe',
    'huayllaca',
    'húqín',
    'hwang chong tché',
    'hwang-teih',

    'ichigenkin',
    'inanga',
    'ingomba',
    'insibi',

    'jabisen',
    'jagajhampa',
    'ja-kin\'rh',
    'jaleika',
    'jamblock',
    'jejy voatavo',
    'jenbe',
    'jeu de timbre',
    'jouhikko',

    'ka',
    'kabosse (kabossy / kabosa)',
    'kacapi',
    'kacha-vînâbossy',
    'kachapî-vînâ',
    'kagoura-fouye',
    'kaïrâta-vînâ',
    'kalimba',
    'kamalen kòni',
    'kamanja',
    'kandang indien',
    'kânih',
    'kankangui',
    'kanoon / qânon',
    'kanoun',
    'kantele',
    'kanyahte\'ka\'nowa / hochet-tortue',
    'kao kao javanais',
    'kara',
    'karabid',
    'karkabet',
    'karna',
    'kass',
    'kasso',
    'kattyauma-vînâ',
    'kaval',
    'kayamb',
    'kazoo / mirliton',
    'kemaçe',
    'kemângeh à gouz',
    'kemângeh-roumy',
    'kena',
    'kesbate',
    'kese kese',
    'keseng keseng',
    'kétipoeng',
    'kétjapi',
    'kharatala',
    'khên',
    'khol',
    'khoradah',
    'khurdra kattyauna-vînâ',
    'kîn',
    'kin-kon',
    'kinan',
    'king / pien king',
    'kinnari-vînâ',
    'kinnery',
    'kîringie',
    'kissar',
    'klaxon',
    'klentony',
    'koant-tsé',
    'koholo',
    'kokiou',
    'kònkòni',
    'kora / cora',
    'kotek',
    'koto',
    'kou',
    'koundyeh',
    'kousser',
    'kpanouhoun',
    'kpézin',
    'kuitra',
    'kulintang',
    'kunjerry',

    'langeleik',
    'lapa',
    'laya bansi',
    'lira da braccio',
    'lithophone',
    'lituus',
    'lo / yang',
    'lo kou',
    'lokombi',
    'loure',
    'lu tchun',
    'lung-tao-ty',
    'luth',
    'lyre',
    'lyro-guitare',
    'lyrone',

    'ma-ca-doi',
    'madiumba',
    'magondi',
    'magrouna',
    'maha-mandira',
    'mahati-vînâ',
    'mainty kely',
    'malakat',
    'mandira',
    'mandoline',
    'mandore',
    'mandurria',
    'manichordion',
    'maracas',
    'maravanne',
    'marddala / madala',
    'marimba',
    'marouvané',
    'mattauphone',
    'mayuri / tayuc',
    'mazhar',
    'm\'bira',
    'medylenara',
    'megyoung',
    'meia-lua',
    'mellophone',
    'mellotron',
    'mélodica',
    'merline',
    'métronome',
    'mezoued',
    'mina-sarangi',
    'mirliton / kazoo',
    'mizmar',
    'mochanga',
    'mon yu / mon ye',
    'moraharpa',
    'mridang',
    'mridangam',
    'muselaar',
    'muscal / naï',

    'nacaire',
    'nadeshvara-vînà',
    'nafir',
    'nagara',
    'nagelgeige / nail violin',
    'nagelharmonica / violon de fer',
    'nagharats',
    'naï / muscal',
    'nanga',
    'napura',
    'nay / nay châch / ney',
    'néo-cor',
    'n\'goni',
    'nhac',
    'nicolo',
    'nihoîhagi',
    'nimfali / ninfali',
    'nira',
    'nixenharfe / nickelharfe',
    'nkwanga / swanga',
    'noordische balk',
    'n\'tama',
    'nyâstaranga',
    'nyckelharpa',

    'ocarina',
    'ocean drum',
    'octobasse',
    'octoblock',
    'olifant',
    'ombi',
    'omerti',
    'omni',
    'ondes Martenot',
    'ongo',
    'ophicléide',
    'organistrum',
    'orgue',
    'orgue numérique',
    'orgue de Barbarie',
    'orphéon',
    'orphéoron',
    'orphica',
    'ottavino',
    'oud',
    'oukazasio',
    'oukpwé',

    'pakay',
    'pakhâwaj',
    'pandeiro',
    'pandereta',
    'pandore',
    'pandura',
    'pandurina',
    'pang tsé / erh hou kin',
    'patola',
    'pedal steel',
    'pee',
    'pennywhistle',
    'percuphone',
    'phorminx',
    'pi-li',
    'piano',
    'piano à queue',
    'piano à tangentes',
    'piano droit',
    'piano-forte',
    'pib-gorn',
    'pibrock',
    'piccolo',
    'pifferta',
    'pipa / pípa',
    'planche à laver',
    'pochette de maître à danser',
    'poun-goun',
    'psaltérion',
    'puïli',
    'pung',
    'pungi',
    'pyrophone',

    'Q-Chord',
    'qanun',
    'qaraqebs',
    'qarnay',
    'qilaut',
    'qin',
    'quena',
    'quinterna',
    'Quinton',

    'rabâb',
    'rajiok',
    'rasarani vina',
    'rauschpfeife',
    'ravanastron',
    'reacTable',
    'rébana',
    'rebec',
    'régale',
    'rek',
    'repinique',
    'rhombe',
    'riqq',
    'romouze',
    'roudadar',
    'roulèr',
    'rovana',
    'rudra-vina',

    'sacqueboute',
    'sagat',
    'salamouri',
    'salpinx',
    'sambucca / sambuque',
    'san-heen / samm-jin / samm-sinn / samhine',
    'sânâi / sani',
    'sangé',
    'santir',
    'santour',
    'sanyogi',
    'sanza',
    'sarala-bensi',
    'sârangî',
    'sarciros',
    'sarod',
    'sarou',
    'sarrussophone',
    'satârâ',
    'sato',
    'saw dorang',
    'saw-duang',
    'saw-tai',
    'saxhorn',
    'saxhorn alto',
    'saxophone',
    'saxophone sopranissimo',
    'saxophone sopranino',
    'saxophone soprano',
    'saxophone alto',
    'saxophone ténor',
    'saxophone baryton',
    'saxophone basse',
    'saxophone contrebasse',
    'saxotromba',
    'saz',
    'schalmey',
    'schiguene',
    'schiti-gekkin / schikenkin',
    'schlagzither',
    'scho / Scho-no-fouge',
    'schofar',
    'schoko',
    'schounga',
    'scie musicale',
    'sciotang / lebel',
    'seaou-po',
    'selantan',
    'selompret',
    'serdam',
    'serinette',
    'serpent',
    'setâr',
    'shakuhachi / siaku-hachi',
    'shamisen',
    'sharadiya-vina / sharode',
    'shekere',
    'sheng',
    'shophar',
    'showktica-vina',
    'shuang-kin',
    'siao',
    'sifflet',
    'sigou-mbarva',
    'sigou-nihou',
    'siku',
    'silbote',
    'sinbi / sinbiòw',
    'sistre',
    'sitar',
    'sodina, sody, soly, antsoly / antsody',
    'sona',
    'sonaja',
    'sorna',
    'soubassophone / sousaphone',
    'soudzou',
    'souffârrah',
    'soug',
    'soum / soungn',
    'souma-koto',
    'soung-king',
    'sounnaïa',
    'souqqarah',
    'sourdine',
    'sousounou',
    'spitzharfe',
    'sringa',
    'sruti-vina',
    'stamentien-pfeiffe',
    'steel-drum',
    'stick',
    'stock-horn',
    'stopf-trumpet',
    'streich-zither',
    'stretch machine',
    'suka',
    'suling / souling',
    'suona / suŏnà',
    'sur-bahara',
    'sur-vâhâra',
    'sur-vina',
    'surdo',
    'swedish bagpipe',
    'synthétiseur',
    'syntophone',
    'sze-hou-hsien',

    'ta huang hou kin',
    'taakan',
    'taarija',
    'tablâ',
    'tablat',
    'taiko',
    'taïsene',
    'taki-koto',
    'talain / talan',
    'tam-tam',
    'tama',
    'tamborim',
    'tambour',
    'tambour boulghary',
    'tambourin',
    'Tambûr',
    'tan-tan',
    'tao-kou',
    'tapan',
    'târ',
    'tarambouka',
    'tarole',
    'tarqa (ou tarka)',
    'tasa',
    'tashepoto',
    'tatchoota',
    'tawaya',
    'tbal',
    'tchang-kou',
    'tché',
    'tchengue',
    'tchogor / tchoger',
    'tchong',
    'tchong-tou',
    'tchou',
    'té-tchong',
    'tebashoul',
    'tebilats',
    'tebloun / tebol',
    'telharmonium',
    'tembour',
    'tenora',
    'terab-en,guiz',
    'terpodion',
    'terr',
    'tet-jer',
    'thar',
    'thari',
    'théorbe',
    'thérémine',
    'thumgo-tuapan',
    'thurnerhorn',
    'ti-kin',
    'tien-kou',
    'tilinca',
    'timbale',
    'timbales / timbalitos / timbalon',
    'timple',
    'tiple',
    'tjé-tjé',
    'tohona',
    'tombah',
    'tombak',
    'to rung',
    'toumourah',
    'tournebout',
    'tourti / tourryi',
    'toutsoumi',
    'trawanga',
    'tres',
    'triangle',
    'tritantri-vina',
    'tritare',
    'troccola',
    'trombone à coulisse',
    'trombone à pistons',
    'trompe de chasse',
    'trompe suisse',
    'trompette d\'harmonie',
    'trompette de mail coach',
    'trompette marine',
    'trompette médiévale / buisine / busine / anafil',
    'tseng',
    'tsikadraha / faray / racle',
    'tsou-kou',
    'tsou toung hou-kin',
    'tsouma-koto',
    'tsouzoumi',
    'tuba',
    'tuba corva',
    'tubilattes / tobillets',
    'tubri',
    'tubular bells',
    'tumburu-vina',
    'turi',
    'turr',
    'txalaparta',
    'txanbela',
    'txirula',
    'txistu',
    'ty',
    'tympanon',

    'uilacapitztli',
    'ukeke-laau',
    'ukulélé',
    'uliuli',
    'utricularium',
    'udu',

    'valiha toritenany',
    'vançali',
    'venu',
    'veuze',
    'vibraphone',
    'vibraslap, fouet vobrant',
    'vièle',
    'vielle à roue',
    'vihuela',
    'villancoyel',
    'vînâ',
    'vioarã cu goarnã / highèghe / violon à entonnoir',
    'viole',
    'viole d\'amour',
    'viole de gambe',
    'violon',
    'violon à pavillon',
    'violon alto',
    'violon savart',
    'violoncelle',
    'violone',
    'vipancia vina',
    'virginal',
    'voix',

    'wakrapuku',
    'wambée',
    'wangong',
    'waterphone',
    'whit-horn',
    'wistaka',
    'wood-block',

    'xaphoon',
    'xeremia / xiremia',
    'xun',
    'xylophone',

    'yabara / sira',
    'yakoumakoto',
    'yang / Lo',
    'yang-kin',
    'yo',
    'yotsu-dake',
    'you-kin',
    'youkoulélé',
    'yun-lo',

    'zampogna simplice',
    'zampoña',
    'zamr-el-kébyr',
    'zamr-el-soghair',
    'zanza',
    'zarb',
    'zithera',
    'zokra',
    'zongora',
    'zourna',
    'zummârah arbaouïa',
    'zummârah khamsaouïa',
    'zummârah settaouïa',
    'zummârah sabaouïa',

    );
        
    foreach ($tags_names as $tag_name)
    {
      $this->createTag($tag_name);
    }

    $this->entity_manager->flush();
  }
}