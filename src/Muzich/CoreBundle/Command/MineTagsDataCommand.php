<?php

namespace Muzich\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Muzich\CoreBundle\Mining\Tag\TagMiner;

class MineTagsDataCommand extends ContainerAwareCommand
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
      ->setName('mining:tags')
      ->setDescription('Mine tags data')
    ;
  }
  
  /** @return TagMiner */
  protected function getTagMiner()
  {
    return $this->getContainer()->get('muzich.mining.tag.miner');
  }
  
  /** @return \Doctrine\ORM\EntityManager */
  protected function getEntityManager()
  {
    return $this->em;
  }

  protected function init()
  {
    $this->em = $this->getContainer()->get('doctrine')->getEntityManager();
  }
  
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->init();
    
    $tag_miner = $this->getTagMiner();
    $user_query_builder = $this->getEntityManager()->createQueryBuilder()->from('MuzichCoreBundle:User', 'user');
    $tag_miner->adaptQueryBuilderSelectorsForUser($user_query_builder, 'user');
    // $user_query_builder->where en fonction des inputes etc
    $this->getTagMiner()->mineTagsForUsers($user_query_builder->getQuery()->getResult());
    
    $output->writeln('<info>Terminé !</info>');
  }
  
}