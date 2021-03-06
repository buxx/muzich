<?php

namespace Muzich\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Muzich\CoreBundle\Managers\ElementManager;

class CheckModerateCommand extends ContainerAwareCommand
{
  protected function configure()
  {
    $this
      ->setName('moderate:check')
      ->setDescription('Contrôle les moderations a effectuer et envoie un email')
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
    $output->writeln('## Script de contrôle de la modération ##');
    $output->writeln('#');
 
    $count_tags = $doctrine->getRepository('MuzichCoreBundle:Tag')
      ->countToModerate();
    $count_elements = $doctrine->getRepository('MuzichCoreBundle:Element')
      ->countToModerate();
    $count_comments = $doctrine->getRepository('MuzichCoreBundle:Element')
      ->countForCommentToModerate();
    
    $output->writeln('<info>Nombre de tags a modérer: '.$count_tags.'</info>');
    $output->writeln('<info>Nombre d\'élément a modérer: '.$count_elements.'</info>');
    $output->writeln('<info>Nombre d\'élément avec commentaire a modérer: '.$count_comments.'</info>');
    
    if ($count_tags || $count_elements || $count_comments)
    {
      $output->writeln('<info>Envoie du courriel ...</info>');

      $website_url = $this->getContainer()->getParameter('siteurl');
      
      $message = \Swift_Message::newInstance()
          ->setSubject('Muzi.ch: Contrôle modération')
          ->setFrom('contact@muzi.ch')
          ->setTo('sevajol.bastien@gmail.com')
          ->setBody($this->getContainer()->get('templating')->render('MuzichCoreBundle:Email:checkmoderate.txt.twig', array(
            'tags'     => $count_tags,
            'elements' => $count_elements,
            'comments' => $count_comments,
            'url'      => $website_url.$this->getContainer()->get('router')->generate('AdmingeneratorDashboard_welcome', array(), false)
          )))
      ;
      $this->getContainer()->get('mailer')->send($message);
    }
    
    $output->writeln('<info>Terminé !</info>');
  }
}