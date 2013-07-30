<?php

namespace Muzich\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
//use Muzich\CoreBundle\Managers\CommentsManager;
use Muzich\CoreBundle\Util\StrictCanonicalizer;
use Muzich\CoreBundle\Entity\Element;

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
    
    if ($input->getArgument('from') == '0.9.8.1' && $input->getArgument('to') == '0.9.8.2')
    {
      $proceeded = true;
      $canonicalizer = new StrictCanonicalizer();
      $elements = $em->getRepository('MuzichCoreBundle:Element')->findAll();
      foreach ($elements as $element)
      {
        $element->setSlug($canonicalizer->canonicalize_stricter($element->getName()));
        $this->updateCountFavorited($element);
        $this->updateCountPlaylisted($element);
        
        $output->writeln('<info>Element '.$element->getName().' favorited ...'.$element->getCountFavorited().'</info>');
        $output->writeln('<info>Element '.$element->getName().' playlisted ...'.$element->getCountPlaylisted().'</info>');
        
        //$output->write('.');
        $em->persist($element);
      }
      
      $output->writeln('');
      $output->writeln('<info>Save in Database ...</info>');
      $em->flush();
      $output->writeln('<info>Terminé !</info>');
    }
    
    if (!$proceeded)
    {
      $output->writeln('<error>Versions saisies non prises en charges</error>');
    }
  }
  
  protected function updateCountFavorited(Element $element)
  {
    $em = $this->getContainer()->get('doctrine')->getEntityManager();
    
    // Compter le nombre de favoris de cet élement 
    // + Effectué par un autre que le proprio
    // + Qui ont l'email confirmé
    
    $count = $em->createQueryBuilder()
      ->select('COUNT(DISTINCT element)')
      ->from('MuzichCoreBundle:UsersElementsFavorites', 'fav')
      ->join('fav.user', 'owner')
      ->join('fav.element', 'element')
      ->where('fav.user != :element_owner_id AND owner.email_confirmed = 1')
      ->andWhere('fav.element = :element_id')
      ->setParameter('element_owner_id', $element->getOwner()->getId())
      ->setParameter('element_id', $element->getId())
      ->getQuery()
      ->getSingleScalarResult()
    ;
    $element->setCountFavorited($count);
  }
  
  protected function updateCountPlaylisted(Element $element)
  {
    $em = $this->getContainer()->get('doctrine')->getEntityManager();
    
    // Compter le nombre d'user qui ont l'element dans une playlist
    // + Effectué par un user différent que le proprio
    // + Qui ont l'email confirmé
    
    $count = $em->createQueryBuilder()
      ->select('COUNT(DISTINCT user)')
      ->from('MuzichCoreBundle:Playlist', 'playlist')
      ->join('playlist.owner', 'user')
      ->where('playlist.elements LIKE :element_id AND playlist.owner != :element_owner_id AND user.email_confirmed = 1')
      ->setParameter('element_owner_id', $element->getOwner()->getId())
      ->setParameter('element_id', '%"id":"'.$element->getId().'"%')
      ->getQuery()
      ->getSingleScalarResult()
    ;
    $element->setCountPlaylisted($count);
  }
  
}