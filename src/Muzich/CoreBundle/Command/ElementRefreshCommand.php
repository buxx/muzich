<?php

namespace Muzich\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Muzich\CoreBundle\Managers\ElementManager;

class ElementRefreshCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('elementengine:refresh')
      ->setDescription('Actualise les éléments avec les API externes')
      // Dans l'avenir on pourra préciser:
      // - le type
      // - l'élément
      ->addOption('sites', null, InputOption::VALUE_REQUIRED, 'Liste exhaustive des site a traiter')
//      ->addOption('section', null, InputOption::VALUE_REQUIRED, 'Sections a mettre a jours (all, embed, data, )', 'all')
//      ->addArgument('sites', InputArgument::OPTIONAL, 'Liste exhaustive des site a traiter')
//      ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $doctrine = $this->getContainer()->get('doctrine');
    $em = $doctrine->getEntityManager();

    $output->writeln('#');
    $output->writeln('## Script de mise a jour des éléments ##');
    $output->writeln('#');

    $filter_sites = array();
    $sql_like = "";
    $sql_like_parameters = array();
    if (($sites = $input->getOption('sites')))
    {
      foreach (explode(',', $sites) as $site)
      {
        $filter_sites[] = trim($site);
        $sql_like .= " OR e.url LIKE :site".trim(str_replace('.', '', $site));
        $sql_like_parameters['site'.trim(str_replace('.', '', $site))] = '%'.$site.'%';
      }
    }
    
    
    // On récupère les éléments
    if (count($filter_sites))
    {
      $elements = $em->createQuery(
        "SELECT e FROM MuzichCoreBundle:Element e "
        . " WHERE e.type IN (:types) $sql_like"
      )->setParameters(array_merge(array('types' => $filter_sites), $sql_like_parameters))
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
      $factory->determineType();
      $factory->proceedExtraFields();
      $em->persist($element);
    }
    
    $output->writeln('<info>Traitement terminé, enregistrement en base ...</info>');
    $em->flush();
    $output->writeln('<info>Enregistrement terminé !</info>');
  }
}