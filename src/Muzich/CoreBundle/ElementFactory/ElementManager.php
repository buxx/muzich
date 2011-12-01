<?php

namespace Muzich\CoreBundle\ElementFactory;

use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

use Muzich\CoreBundle\ElementFactory\Site\YoutubeFactory;

/**
 * 
 *
 * @author bux
 */
class ElementManager
{
  
  protected $types = array(
    'youtube.com', 
    'soundcloud.com', 
    'son2teuf.org', 
    'jamendo.com'
  );
  
  protected $em;
  protected $element;
  protected $container;
  
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
    $this->element->setTagsWithIds($this->em, $this->element->getTags());
    $this->setGroup();
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
  }
  
  /**
   * Determine le type d'objet auquel on a affaire.
   */
  protected function determineType()
  {
    preg_match("/^(http:\/\/)?([^\/]+)/i", $this->element->getUrl(), $chaines);
    $host = $chaines[2];
    // Repérer les derniers segments
    preg_match("/[^\.\/]+\.[^\.\/]+$/",$host,$chaines);
    
    $type = null;
    
    
    if (in_array($chaines[0], $this->types))
    {
      $type = $this->em->getRepository('MuzichCoreBundle:ElementType')->find($chaines[0]);
    }
    
    $this->element->setType($type);
  }
  
  /**
   * Construction des autres champs tel que embed.
   * 
   * 
   */
  protected function proceedExtraFields()
  {
   
    // Instanciation d'un objet factory correspondant au type, par exemple
    // YoutubeFactory, qui répondant a une implementation retournera ces infos.
  
    if ($this->element->getType())
    {
      $site_factory = $this->getFactory();
      $this->element->setEmbed($site_factory->getEmbedCode());
    }
    
  }
  
  protected function getFactory()
  { 
    switch ($this->element->getType()->getId())
    {
      case 'youtube.com':
        return new YoutubeFactory($this->element, $this->container);
      break;
      case 'soundcloud.com':
        return new SoundCloudFactory($this->element, $this->container);
      break;
      case 'son2teuf.org':
        return new Son2TeufFactory($this->element, $this->container);
      break;
      case 'jamendo.com':
        return new JamendoFactory($this->element, $this->container);
      break;
    
      default:
        throw new Exception("La Factory n'est pas connu pour ce type.");
      break;
    }
  }
    
}

?>
