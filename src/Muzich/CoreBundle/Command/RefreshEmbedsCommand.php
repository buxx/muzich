<?php

namespace Muzich\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Muzich\CoreBundle\Managers\ElementManager;

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
      ->addOption('sites', null, InputOption::VALUE_REQUIRED, 'Liste exhaustive des site a traiter')
//      ->addArgument('sites', InputArgument::OPTIONAL, 'Liste exhaustive des site a traiter')
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

    $filter_sites = array();
    if (($sites = $input->getOption('sites')))
    {
      foreach (explode(',', $sites) as $site)
      {
        $filter_sites[] = trim($site);
      }
    }
    
    
    // On récupère les éléments
    if (count($filter_sites))
    {
      $elements = $em->createQuery(
        "SELECT e FROM MuzichCoreBundle:Element e "
        . " WHERE e.type IN (:types)"
      )->setParameter('types', $filter_sites)
       ->getResult()      
      ;
      $output->writeln('<comment>Utilisation de filtre par site ('.$input->getOption('sites').')</comment>');
    }
    else
    {
      $elements = $em->getRepository('MuzichCoreBundle:Element')->findAll();
    }
    
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