<?php

namespace Muzich\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateRobotFilesCommand extends ContainerAwareCommand
{
  
  protected $sitemap_content;
  protected $sitemap_urls;
  protected $router;
  protected $locales;
  protected $siteurl_prefix;
  protected $em;
  
  protected function configure()
  {
    $this
      ->setName('generate:robot:files')
      ->setDescription('Generate files for bots')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->init();
    $this->generateRobotsTxt();
    $this->generateSitemap();
    $output->writeln('<info>Terminé !</info>');
  }
  
  protected function init()
  {
    $this->sitemap_content = new \DOMDocument('1.0', 'utf-8');
    $this->sitemap_urls = $this->sitemap_content->createElement('urlset');
    $this->router = $this->getContainer()->get('router');
    $this->locales = $this->getContainer()->getParameter('supported_langs');
    $this->siteurl_prefix = $this->getContainer()->getParameter('siteurl');
    $this->em = $this->getContainer()->get('doctrine')->getEntityManager();
  }
  
  protected function generateRobotsTxt()
  {
    $robotstxt_content = "User-agent: *\n".
      "Sitemap: http://muzi.ch/sitemap.xml";
    file_put_contents('web/robots.txt', $robotstxt_content);
  }
  
  protected function generateSitemap()
  {
    $this->addStaticsUrlsToUrlNode();
    $this->addUserUrlsToUrlNode();
    $this->addGroupUrlsToUrlNode();
    $this->addPlaylistUrlsToUrlNode();
    $this->addElementPermalinkUrlsToUrlNode();
    
    $this->sitemap_content->appendChild($this->sitemap_urls);
    $this->sitemap_content->save('web/sitemap.xml');
  }
  
  protected function addStaticsUrlsToUrlNode()
  {
    $this->addUrlsToNode($this->sitemap_urls, $this->generateUrls('home'), 'always');
    $this->addUrlsToNode($this->sitemap_urls, $this->generateUrls('element_show_need_tags'), 'hourly');
  }
  
  protected function addUserUrlsToUrlNode()
  {
    $users = $this->em->createQueryBuilder()
      ->from('MuzichCoreBundle:User', 'user')
      ->select('user.id, user.slug')
      ->getQuery()->getScalarResult();
    
    foreach ($users as $user)
    {
      $this->addUrlsToNode($this->sitemap_urls, $this->generateUrls('show_user', array(
        'slug' => $user['slug']
      )), 'weekly');
      
      $this->addUrlsToNode($this->sitemap_urls, $this->generateUrls('favorite_user_list', array(
        'slug' => $user['slug']
      )), 'weekly');
      
      $this->addUrlsToNode($this->sitemap_urls, $this->generateUrls('playlists_user', array(
        'user_slug' => $user['slug']
      )), 'weekly');
    }
  }
  
  protected function addGroupUrlsToUrlNode()
  {
    $groups = $this->em->createQueryBuilder()
      ->from('MuzichCoreBundle:Group', 'g')
      ->select('g.id, g.slug')
      ->getQuery()->getScalarResult();
    
    foreach ($groups as $group)
    {
      $this->addUrlsToNode($this->sitemap_urls, $this->generateUrls('show_group', array(
        'slug' => $group['slug']
      )), 'weekly');
    }
  }
  
  protected function addPlaylistUrlsToUrlNode()
  {
    $playlists = $this->em->createQueryBuilder()
      ->from('MuzichCoreBundle:Playlist', 'playlist')
      ->leftJoin('playlist.owner', 'owner')
      ->select('playlist.id, owner.slug')
      ->getQuery()->getScalarResult();
    
    foreach ($playlists as $playlist)
    {
      $this->addUrlsToNode($this->sitemap_urls, $this->generateUrls('playlist', array(
        'playlist_id' => $playlist['id'],
        'user_slug' => $playlist['slug']
      )), 'monthly');
    }
  }
  
  protected function addElementPermalinkUrlsToUrlNode()
  {
    $elements = $this->em->createQueryBuilder()
      ->from('MuzichCoreBundle:Element', 'element')
      ->select('element.id, element.slug')
      ->where('element.private = 0')
      ->getQuery()->getScalarResult();
    
    foreach ($elements as $element)
    {
      $this->addUrlsToNode($this->sitemap_urls, $this->generateUrls('element_show_one', array(
        'element_id' => $element['id'],
        'element_slug' => $element['slug']
      )), 'yearly');
    }
  }
  
  /** @return array */
  protected function generateUrls($route, $parameters = array())
  {
    $urls = array();
    foreach ($this->locales as $locale)
    {
      $urls[] = $this->siteurl_prefix . $this->router->generate($route, array_merge($parameters, array(
        '_locale' => $locale
      )));
    }
    
    return $urls;
  }
  
  protected function addUrlsToNode(\DOMNode $node, $urls, $changefreq = null)
  {
    foreach ($urls as $url)
    {
      $url_loc_content = $this->sitemap_content->createTextNode($url);
      $url_node = $this->sitemap_content->createElement('url');
      
      $loc_node = $this->sitemap_content->createElement('loc');
      $loc_node->appendChild($url_loc_content);
      
      $url_node->appendChild($loc_node);
      
      if ($changefreq)
      {
        $changefreq_node = $this->sitemap_content->createElement('changefreq');
        $changefreq_node->appendChild($this->sitemap_content->createTextNode($changefreq));
        $url_node->appendChild($changefreq_node);
      }
      
      $node->appendChild($url_node);
    }
  }
  
}