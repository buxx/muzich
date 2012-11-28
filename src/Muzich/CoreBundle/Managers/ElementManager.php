<?php

namespace Muzich\CoreBundle\Managers;

use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

use Muzich\CoreBundle\Factory\Elements\Youtubecom;
use Muzich\CoreBundle\Factory\Elements\Youtube;
use Muzich\CoreBundle\Factory\Elements\Dailymotioncom;
use Muzich\CoreBundle\Factory\Elements\Jamendocom;
use Muzich\CoreBundle\Factory\Elements\Soundcloudcom;
use Muzich\CoreBundle\Factory\Elements\Deezercom;

/**
 * 
 *
 * @author bux
 */
class ElementManager
{  
  protected $em;
  protected $element;
  protected $container;
  protected $factories;
  
  /**
   * Procédure chargé de retourner des information destiné au 
   * formulaire d'ajout d'un element.
   * 
   * @param string $url
   * @return array
   */
  public static function collect($url)
  {
    
    return array(
      'name' => null,
      'tags' => array()
    );
  }

  /**
   * 
   * @param Element $element
   * @param EntityManager $em
   * @param Container $container
   */
  public function __construct(Element $element, EntityManager $em, Container $container)
  {
    $this->element = $element;
    $this->em = $em;
    $this->container = $container;
    $this->factories = $this->container->getParameter('factories');
        
    $evm = new \Doctrine\Common\EventManager();
    $timestampableListener = new \Gedmo\Timestampable\TimestampableListener();
    $evm->addEventSubscriber($timestampableListener);
    
    $this->em->getEventManager()->addEventSubscriber($timestampableListener);
  }
  
  /**
   * Procédure chargé de construire le contenu de l'élément.
   *  nom, Code d'embed, [...]
   * 
   * @param array $params
   * @param User $owner
   */
  public function proceedFill(User $owner, $do_tags = true)
  {
    $this->element->setOwner($owner);
    if ($do_tags)
    {
      $this->element->setTagsWithIds($this->em, json_decode($this->element->getTags()));
    }
    $this->determineType();
    $this->proceedExtraFields();
  }
  
  protected function setGroup()
  {
    if ($this->element->getGroup())
    {
      $group = $this->em->getRepository('MuzichCoreBundle:Group')->findOneById($this->element->getGroup());
      $this->element->setGroup($group);
    }
    else
    {
      $this->element->setGroup(null);
    }
  }
  
  /**
   * Determine le type d'objet auquel on a affaire.
   */
  public function determineType()
  {
    // On ne prend pas de risque avec le www, on l'enlève
    $url = str_replace('www.', '', $this->element->getUrl());
    
    preg_match("/^(http:\/\/|https:\/\/)?([^\/]+)/i", $url, $chaines);
    
    $type = 'unknow';
    if (array_key_exists(2, $chaines))
    {
      $host = $chaines[2];
      // Repérer les derniers segments
      preg_match("/[^\.\/]+\.[^\.\/]+$/",$host,$chaines);

      if (array_key_exists(0, $chaines))
      {
        $type = $chaines[0];
      }
    }
    
    $this->element->setType($type);
  }
  
  /**
   * Construction des autres champs tel que embed.
   * 
   * 
   */
  public function proceedExtraFields()
  {
   
    // Instanciation d'un objet factory correspondant au type, par exemple
    // YoutubeFactory, qui répondant a une implementation retournera ces infos.
  
    if (in_array($this->element->getType(), $this->factories))
    {
      $site_factory = $this->getFactory();
      // On récupères les datas de l'élément
      $site_factory->retrieveDatas();
      // On procède a la construction de nos informations
      $site_factory->proceedEmbedCode();
      $site_factory->proceedThumbnailUrl();
    }
    
  }
  
  public function getFactory()
  { 
//    $factory_name = ucfirst(str_replace('.', '', $this->element->getType())).'Factory';
//    return new $factory_name($this->element, $this->container);
    
    switch ($this->element->getType())
    {
      case 'youtube.com':
        return new Youtubecom($this->element, $this->container);
      break;
      case 'youtu.be':
        return new Youtube($this->element, $this->container);
      break;
      case 'soundcloud.com':
        return new Soundcloudcom($this->element, $this->container);
      break;
      case 'jamendo.com':
        return new Jamendocom($this->element, $this->container);
      break;
      case 'dailymotion.com':
        return new Dailymotioncom($this->element, $this->container);
      break;
      case 'deezer.com':
        return new Deezercom($this->element, $this->container);
      break;
      default:
        throw new \Exception("La Factory n'est pas prise en charge pour ce type.");
      break;
    }
    
  }
    
}

?>
