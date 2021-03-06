<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            //new Sonata\jQueryBundle\SonatajQueryBundle(),
            //new Sonata\BluePrintBundle\SonataBluePrintBundle(),
            //new Sonata\AdminBundle\SonataAdminBundle(),
            //new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Gregwar\ImageBundle\GregwarImageBundle(),
            new Admingenerator\GeneratorBundle\AdmingeneratorGeneratorBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Shtumi\UsefulBundle\ShtumiUsefulBundle(),
            new FOS\FacebookBundle\FOSFacebookBundle(),
            new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle(),
            
            new Muzich\CoreBundle\MuzichCoreBundle(),
            new Muzich\UserBundle\MuzichUserBundle(),
            new Muzich\IndexBundle\MuzichIndexBundle(),
            new Muzich\HomeBundle\MuzichHomeBundle(),
            new Muzich\MynetworkBundle\MuzichMynetworkBundle(),
            //new Muzich\AdminBundle\MuzichAdminBundle(),
            new Muzich\GroupBundle\MuzichGroupBundle(),
            new Muzich\FavoriteBundle\MuzichFavoriteBundle(),
            new Muzich\CommentBundle\MuzichCommentBundle(),
            new Muzich\AdminBundle\MuzichAdminBundle(),
            new Muzich\PlaylistBundle\MuzichPlaylistBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Acme\DemoBundle\AcmeDemoBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
    
    public function init()
    {
      if ($this->debug || $this->getEnvironment() == "test") {
        ini_set('display_errors', 1);
        error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
        Debug::enable(E_RECOVERABLE_ERROR & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED, false);
      } else {
        ini_set('display_errors', 0);
      }
    }
    
}
