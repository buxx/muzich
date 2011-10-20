<?php

namespace Muzich\CoreBundle\ElementFactory\Site\base;

use Muzich\CoreBundle\Entity\Element;
use Symfony\Component\DependencyInjection\Container;
use Muzich\CoreBundle\ElementFactory\Site\base\BaseFactory;
use Muzich\CoreBundle\ElementFactory\lib\VideoEmbed;

/**
 * 
 *
 * @author bux
 */
class VideoSiteFactory extends BaseFactory
{
  
  protected $video_engine = null;
  
  public function __construct(Element $element, Container $container)
  {
    parent::__construct($element, $container);
    
    // Configuration de VideoEmbed
    define('SITEBASE', $container->getParameter('sitebase'));
    define('VIDEO_EMBED_CONFIG_FILE', SITEBASE.$container->getParameter('video_embed_config_file'));
    //to activate debug mode and false for production usage. it will write 
    //to a log file when something goes wrong but should not produce 
    //exceptions in production enviroment
    define('DEBUG', $container->getParameter('video_embed_debug')); 
    
    try {
      $this->video_engine =  new VideoEmbed($this->element->getUrl());
    } catch (Exception $exc) {
      
    }
  }
  
  public function getEmbedCode()
  {
    if ($this->video_engine)
    {
      return $this->video_engine->embed;
    }
    return null;
  }
  
}

?>
