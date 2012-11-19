<?php

namespace Muzich\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Searcher\ElementSearcher;
use Muzich\CoreBundle\lib\Tag as TagLib;

class TagOrderCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('tagengine:order')
      ->setDescription('Ordonne la liste des tags sur les pages utilisateurs')
    ;
  }
  
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $doctrine = $this->getContainer()->get('doctrine');
    $em = $doctrine->getEntityManager();
    $tag_lib = new TagLib();

    $output->writeln('#');
    $output->writeln('## Script d\'ordonance des listes de tags sur les pages'
      .' utilisateurs ##');
    $output->writeln('#');

    // Premier point on récupère les utilisateurs qui ont mis a jour leurs 
    // favoris
    $output->writeln('<info>Gestion de l\'ordre des tags sur les pages de '
      .'partages favoris</info>');
 
    $users = $em->createQuery(
        "SELECT u FROM MuzichCoreBundle:User u"
        . " WHERE u.datas LIKE :favupd"
      )->setParameter('favupd', '%"'.User::DATA_FAV_UPDATED.'":true%')
      ->getResult()
    ;
    
    if (count($users))
    {
      $output->writeln('<info>Traitement de '.count($users).' utilisateurs</info>');
      foreach ($users as $user)
      {
        // On récupère ses éléments favoris
        $search_object = new ElementSearcher();
        $search_object->init(array(
          'user_id'  => $user->getId(),
          'favorite' => true
        ));
        $elements_favorite = $search_object->getElements($doctrine, $user->getId());
        
        // On récupère la nouvelle liste de tags ordonnés
        $tags_ordered = $tag_lib->getOrderedTagsWithElements($elements_favorite);
        // On enregistre ça en base
        $user->setData(User::DATA_TAGS_ORDER_PAGE_FAV, $tags_ordered);
        $user->setData(User::DATA_FAV_UPDATED, false);
        $em->persist($user);
        $em->flush();
      }
    
    }
    else
    {
      $output->writeln('<info>Aucun utilisateur a traiter</info>');
    }
    
    // Deuxième point on récupère les utilisateurs qui ont mis a jour leurs 
    // diffusion
    $output->writeln('<info>Gestion de l\'ordre des tags sur les diffusions</info>');
 
    $users = $em->createQuery(
        "SELECT u FROM MuzichCoreBundle:User u"
        . " WHERE u.datas LIKE :favupd"
      )->setParameter('favupd', '%"'.User::DATA_DIFF_UPDATED.'":true%')
      ->getResult()
    ;
    
    if (count($users))
    {
      $output->writeln('<info>Traitement de '.count($users).' utilisateurs</info>');
      foreach ($users as $user)
      {
        // On récupère ses éléments diffusés
        $search_object = new ElementSearcher();
        $search_object->init(array(
          'user_id'  => $user->getId()
        ));
        $elements_diffused = $search_object->getElements($doctrine, $user->getId());
        
        // On récupère la nouvelle liste de tags ordonnés
        $tags_ordered = $tag_lib->getOrderedTagsWithElements($elements_diffused);
        // On enregistre ça en base
        $user->setData(User::DATA_TAGS_ORDER_DIFF, $tags_ordered);
        $user->setData(User::DATA_DIFF_UPDATED, false);
        $em->persist($user);
        $em->flush();
      }
    
    }
    else
    {
      $output->writeln('<info>Aucun utilisateur a traiter</info>');
    }
    
    $output->writeln('<info>Terminé !</info>');
  }
}