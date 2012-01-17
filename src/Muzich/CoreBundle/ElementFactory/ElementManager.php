<?php

namespace Muzich\CoreBundle\ElementFactory;

use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

use Muzich\CoreBundle\ElementFactory\Site\YoutubecomFactory;
use Muzich\CoreBundle\ElementFactory\Site\YoutubeFactory;
use Muzich\CoreBundle\ElementFactory\Site\DailymotioncomFactory;
use Muzich\CoreBundle\ElementFactory\Site\JamendocomFactory;
use Muzich\CoreBundle\ElementFactory\Site\SoundcloudcomFactory;
use Muzich\CoreBundle\ElementFactory\Site\DeezercomFactory;

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
  public function proceedFill(User $owner)
  {
    $this->element->setOwner($owner);
    $this->element->setTagsWithIds($this->em, json_decode($this->element->getTags()));
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
  protected function determineType()
  {
    // On ne prend pas de risque avec le www, on l'enlève
    $url = str_replace('www.', '', $this->element->getUrl());
    
    preg_match("/^(http:\/\/)?([^\/]+)/i", $url, $chaines);
    
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
      $this->element->setEmbed($site_factory->getEmbedCode());
    }
    
  }
  
  protected function getFactory()
  { 
//    $factory_name = ucfirst(str_replace('.', '', $this->element->getType())).'Factory';
//    return new $factory_name($this->element, $this->container);
    
    switch ($this->element->getType())
    {
      case 'youtube.com':
        return new YoutubecomFactory($this->element, $this->container);
      break;
      case 'youtu.be':
        return new YoutubeFactory($this->element, $this->container);
      break;
      case 'soundcloud.com':
        return new SoundcloudcomFactory($this->element, $this->container);
      break;
      case 'jamendo.com':
        return new JamendocomFactory($this->element, $this->container);
      break;
      case 'dailymotion.com':
        return new DailymotioncomFactory($this->element, $this->container);
      break;
      case 'deezer.com':
        return new DeezercomFactory($this->element, $this->container);
      break;
      default:
        throw new \Exception("La Factory n'est pas prise en charge pour ce type.");
      break;
    }
    
  }
    
}

?>
