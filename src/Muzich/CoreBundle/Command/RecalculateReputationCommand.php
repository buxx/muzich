<?php

namespace Muzich\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Muzich\CoreBundle\Entity\EventArchive;
use Muzich\CoreBundle\Entity\Element;

class RecalculateReputationCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('score:recalculate')
      ->setDescription('Recalcul des scores')
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
    $output->writeln('## Script de recalcul des scores ##');
    $output->writeln('#');

    $output->writeln('<info>Début du traitement ...</info>');
    $this->recalculateElementScores($input, $output);
    $this->recalculateUserScores($input, $output);
    
    $output->writeln('<info>Saving in database ...</info>');
    $em->flush();
    $output->writeln('<info>Terminé !</info>');
  }
  
  protected function recalculateUserScores(InputInterface $input, OutputInterface $output)
  {
    $doctrine = $this->getContainer()->get('doctrine');
    $em = $doctrine->getEntityManager();
    
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
      
      //$coef_element_point = $this->getContainer()->getParameter('reputation_element_point_value');
      $element_points = 0;
      foreach ($elements as $element)
      {
        $element_points += $element->getPoints();
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
          $element_points
        + ($count_follow   * $coef_follow)
        + ($count_tag_prop * $coef_tag_prop)
      ;
      
      $user->setReputation($points);
      $em->persist($user);
      $output->writeln('<info>User "'.$user->getUsername().'": '.$points.' score</info>');
    }
    
  }
    
  protected function recalculateElementScores(InputInterface $input, OutputInterface $output)
  {
    $doctrine = $this->getContainer()->get('doctrine');
    $em = $doctrine->getEntityManager();
    
    $elements = $em->createQuery(
      "SELECT element FROM MuzichCoreBundle:Element element"
    )->getResult();
    
    foreach ($elements as $element)
    {
      $element_score = $this->getElementScore($element);
      $element->setPoints($element_score);
      $output->writeln('<info>Element "'.$element->getName().'": '.$element_score.' score</info>');
      $em->persist($element);
    }
    
  }
  
  protected function getElementScore(Element $element)
  {
    $element_score = 0;
    
    $element_score += (count($element->getVoteGoodIds())*$this->getContainer()->getParameter('reputation_element_point_value'));
    
    $element_score += ($element->getCountFavorited()*$this->getContainer()->getParameter('reputation_element_favorite_value'));
    
    $element_score += ($element->getCountPlaylisted()*$this->getContainer()->getParameter('reputation_element_added_to_playlist'));
    
    return $element_score;
  }
  
}