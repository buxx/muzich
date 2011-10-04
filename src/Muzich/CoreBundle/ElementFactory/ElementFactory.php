<?php

namespace Muzich\CoreBundle\ElementFactory;

use Muzich\CoreBundle\Entity\Element;
use Muzich\CoreBundle\Entity\User;
use Doctrine\ORM\EntityManager;

use Muzich\CoreBundle\ElementFactory\Site\YoutubeFactory;

/**
 * 
 *
 * @author bux
 */
class ElementFactory
{
  
  protected $types = array(
    'youtube.com'    => 'YoutubeFactory', 
    'soundcloud.com' => 'SoundCloudFactory', 
    'son2teuf.org'   => 'Son2TeufFactory', 
    'jamendo.com'    => 'JamendoFactory'
  );
  
  protected $em;
  protected $element;
  
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
  
  public function __construct(Element $element, EntityManager $em)
  {
    $this->element = $element;
    $this->em = $em;
    
    $evm = new \Doctrine\Common\EventManager();
    $timestampableListener = new \Gedmo\Timestampable\TimestampableListener();
    $evm->addEventSubscriber($timestampableListener);
    
    $this->em->getEventManager()->addEventSubscriber($timestampableListener);
  }
  
  /**
   * Procédure chargé de construire le contenu de l'élément.
   *  nom, Code d'embed, [...]
   * 
   * @param Element $element
   * @param array $params
   * @param User $owner
   * @return Element 
   */
  public function proceed($params, User $owner)
  {
    $this->element->setName($params['name']);
    $this->element->setUrl($params['url']);
    $this->element->setOwner($owner);
    $this->element->setTagsWithIds($this->em, $params['tags']);
    $this->determineType();
    $this->proceedExtraFields();
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
    if (array_key_exists($chaines[0], $this->types))
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
      $factory_name = $this->types[$this->element->getType()->getId()];
      
      $site_factory = new YoutubeFactory($this->element);
      $this->element->getEmbed($site_factory->getEmbedCode());
    }
    
  }
    
}

?>
