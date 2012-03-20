<?php

namespace Muzich\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RecalculateReputationCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('users:reputation:recalculate')
      ->setDescription('Recalcul des scores de reputation')
      ->addOption('user', null, InputOption::VALUE_REQUIRED, 'username du compte a traiter')
//      ->addArgument('sites', InputArgument::OPTIONAL, 'Liste exhaustive des site a traiter')
//      ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $doctrine = $this->getContainer()->get('doctrine');
    $em = $doctrine->getEntityManager();

    $output->writeln('#');
    $output->writeln('## Script de recalcul des scores de reputation ##');
    $output->writeln('#');

    $output->writeln('<info>Début du traitement ...</info>');
    
    if (($username = $input->getOption('user')))
    {
      $output->writeln('<info>Utilisateur choisis: '.$username.'</info>');
      $user = $em->createQuery(
        "SELECT u FROM MuzichCoreBundle:User u"
        . " WHERE u.username = :username"
      )->setParameter('username', $username)
       ->getResult()      
      ;
      
      if (count($user))
      {
        $output->writeln('<info>Utilisateur trouvé</info>');
        $users = array($user[0]);
      }
    }
    else
    {
      // Sinon on traite tout les utilisateurs
      $users = $em->createQuery(
        "SELECT u FROM MuzichCoreBundle:User u"
      )
       ->getResult()      
      ;
    }
    
    // Pour chaque utilisateur a traiter
    foreach ($users as $user)
    {
      $user_points = 0;
      
      // On calcule pour les éléments
      $elements = $em->createQuery(
        "SELECT e FROM MuzichCoreBundle:Element e"
        . " WHERE e.owner = :uid"
      )->setParameter('uid', $user->getId())
       ->getResult()      
      ;
      
      $coef_element_point = $this->getContainer()->getParameter('reputation_element_point_value');
      $element_points = 0;
      foreach ($elements as $element)
      {
        $element_points += $element->getPoints();
      }
      
      $user->setReputation($element_points * $coef_element_point);
      $em->persist($user);
    }
    
    $output->writeln('<info>Enregistrement en base ...</info>');
    $em->flush();
    $output->writeln('<info>Terminé !</info>');
  }
}