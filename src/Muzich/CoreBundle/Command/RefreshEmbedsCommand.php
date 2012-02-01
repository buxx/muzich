<?php

namespace Muzich\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Muzich\CoreBundle\ElementFactory\ElementManager;

class RefreshEmbedsCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('elementengine:refresh-embeds')
      ->setDescription('Actualise les embeds')
      // Dans l'avenir on pourra préciser:
      // - le type
      // - l'élément
//      ->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to greet?')
//      ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $doctrine = $this->getContainer()->get('doctrine');
    $em = $doctrine->getEntityManager();

    $output->writeln('#');
    $output->writeln('## Script de mise a jour des code embeds ##');
    $output->writeln('#');
    
    // On récupère tout les éléments
    $elements = $em->getRepository('MuzichCoreBundle:Element')->findAll();
    
    $output->writeln('<info>Nombre d\'éléments a traiter: '.count($elements).'</info>');
    $output->writeln('<info>Début du traitement ...</info>');
    
    foreach ($elements as $element)
    {
      $output->writeln('<info>Prise en charge de "'.$element->getUrl().'" ...</info>');
      $factory = new ElementManager($element, $em, $this->getContainer());
      $factory->proceedExtraFields();
      $em->persist($element);
    }
    
    $output->writeln('<info>Traitement terminé, enregistrement en base ...</info>');
    $em->flush();
    $output->writeln('<info>Enregistrement terminé !</info>');
  }
}