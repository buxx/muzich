<?php

namespace Muzich\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Muzich\CoreBundle\Entity\EventArchive;

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
      
      /*
       * On calcule pour les éléments
       */
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
      
      /*
       * On calcule pour les favoris
       */
      $coef_element_fav = $this->getContainer()->getParameter('reputation_element_favorite_value');
      $count_favs = 0;
      $fav = $em->createQuery(
        "SELECT COUNT(f) FROM MuzichCoreBundle:UsersElementsFavorites f"
        . " JOIN f.element e JOIN f.user fu"
        . " WHERE e.owner = :uid AND f.user != :uid AND fu.email_confirmed = 1"
      )->setParameter('uid', $user->getId())
       ->getScalarResult()      
      ;
      
      if (count($fav))
      {
        if (count($fav[0]))
        {
          $count_favs = $fav[0][1];
        }
      }
      
      /*
       * Calcul pour les utilisateurs suivis
       */
      $coef_follow = $this->getContainer()->getParameter('reputation_element_follow_value');
      $count_follow = 0;
      $fol = $em->createQuery(
        "SELECT COUNT(f) FROM MuzichCoreBundle:FollowUser f"
        . " JOIN f.follower fu"
        . " WHERE f.followed = :uid AND fu.email_confirmed = 1"
      )->setParameter('uid', $user->getId())
       ->getScalarResult()      
      ;
      
      if (count($fol))
      {
        if (count($fol[0]))
        {
          $count_follow = $fol[0][1];
        }
      }
      
      /*
       *  Calcul pour les tags proposés sur des éléments
       */
      $coef_tag_prop = $this->getContainer()->getParameter('reputation_element_tags_element_prop_value');
      
      try {
        
        $count_tag_prop = $em->createQuery(
          "SELECT a.count FROM MuzichCoreBundle:EventArchive a"
          . " WHERE a.user = :uid AND a.type = :type"
        )->setParameters(array(
          'uid'  => $user->getId(),
          'type' => EventArchive::PROP_TAGS_ELEMENT_ACCEPTED
        ))
         ->getSingleScalarResult()      
        ;
        
      } catch (\Doctrine\ORM\NoResultException $exc) {
        $count_tag_prop = 0;
      }

      $points = 
          ($element_points * $coef_element_point)
        + ($count_favs     * $coef_element_fav)
        + ($count_follow   * $coef_follow)
        + ($count_tag_prop * $coef_tag_prop)
      ;
      
      $user->setReputation($points);
      $em->persist($user);
      $em->flush();
    }
    
    $output->writeln('<info>Terminé !</info>');
  }
}