<?php

namespace Muzich\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Muzich\CoreBundle\ElementFactory\ElementManager;

class UpdateTagSlugsCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('tagengine:update-slugs')
      ->setDescription('Actualise les slugs')
      // Dans l'avenir on pourra préciser:
      // - le type
      // - l'élément
//      ->addOption('sites', null, InputOption::VALUE_REQUIRED, 'Liste exhaustive des site a traiter')
//      ->addArgument('sites', InputArgument::OPTIONAL, 'Liste exhaustive des site a traiter')
//      ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $doctrine = $this->getContainer()->get('doctrine');
    $em = $doctrine->getEntityManager();

    $output->writeln('#');
    $output->writeln('## Script de mise a jour des slugs tags ##');
    $output->writeln('#');
 
    // On récupère les tags
    $tags = $em->getRepository('MuzichCoreBundle:Tag')->findAll();
    $tag_manager = $this->getContainer()->get('muzich_tag_manager');
    
    $output->writeln('<info>Nombre de tags a traiter: '.count($tags).'</info>');
    $output->writeln('<info>Début du traitement ...</info>');
    
    foreach ($tags as $tag)
    {
      $tag_manager->updateSlug($tag);
      $em->persist($tag);
    }
    
    $output->writeln('<info>Traitement terminé, enregistrement en base ...</info>');
    $em->flush();
    $output->writeln('<info>Enregistrement terminé !</info>');
  }
}