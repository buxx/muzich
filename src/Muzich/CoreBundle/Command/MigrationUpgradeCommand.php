<?php

namespace Muzich\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
//use Muzich\CoreBundle\Managers\CommentsManager;

class MigrationUpgradeCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('migration:upgrade')
      ->setDescription('Mise a jour de données nécéssaire pour migrations')
      ->addArgument('from', InputArgument::REQUIRED, 'version de départ')
      ->addArgument('to', InputArgument::REQUIRED, 'version visé')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $doctrine = $this->getContainer()->get('doctrine');
    $em = $doctrine->getEntityManager();

    $output->writeln('#');
    $output->writeln('## Outil de migration ##');
    $output->writeln('#');

    $proceeded = false;
    if ($input->getArgument('from') == '0.6' && $input->getArgument('to') == '0.7')
    {
      $proceeded = true;
      $output->writeln('<info>Mise a jour des données "commentaires" pour migration 0.6 => 0.7</info>');
      /**
       * Pour cette migration on a besoin de rajouter une valeur aux données
       * de commentaire d'éléments.
       */
      
      $elements = $em->getRepository('MuzichCoreBundle:Element')->findAll();
      foreach ($elements as $element)
      {
        if (count(($comments = $element->getComments())))
        {
          // Oui un for serai plus performant ...
          foreach ($comments as $i => $comment)
          {
            // Si il n'y as pas d'enregistrement 'a' dans le commentaire
            if (!array_key_exists('a', $comment))
            {
              // Il faut le rajouter
              $comments[$i]['a'] = array();
            }
          }
          $element->setComments($comments);
          $em->persist($element);
        }
      }
      $em->flush();
      $output->writeln('<info>Terminé !</info>');
    }
    
    if (!$proceeded)
    {
      $output->writeln('<error>Versions saisies non prises en charges</error>');
    }
  }
}