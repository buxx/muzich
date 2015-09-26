<?php

namespace Muzich\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Muzich\CoreBundle\Mining\Tag\TagMiner;
use Doctrine\ORM\QueryBuilder;
use Muzich\CoreBundle\Entity\User;

class MineTagsDataCommand extends ContainerAwareCommand
{
  
  const MINE_DIFFUSION = 'diffusion';
  const MINE_FAVORITE = 'favorite';
  const MINE_PLAYLIST = 'playlist';
  const MINE_TAGS = 'tags';
  
  static $mine_types = array(
    self::MINE_DIFFUSION,
    self::MINE_FAVORITE,
    self::MINE_PLAYLIST,
    self::MINE_TAGS
  );
  
  protected $em;
  protected $tag_miner;
  protected $input;
  protected $output;
  protected $progress;
  protected $users_mineds = array();
  
  protected function configure()
  {
    $this
      ->setName('mining:tags')
      ->setDescription('Mine tags data')
      ->addOption('all', null, InputOption::VALUE_NONE, 'Selectionne tous les utilisateurs')
      ->addOption(self::MINE_DIFFUSION, null, InputOption::VALUE_NONE, 'Ne traite que les diffusions')
      ->addOption(self::MINE_FAVORITE, null, InputOption::VALUE_NONE, 'Ne traite que les favoris')
      ->addOption(self::MINE_PLAYLIST, null, InputOption::VALUE_NONE, 'Ne traite que les playlists')
      ->addOption(self::MINE_TAGS, null, InputOption::VALUE_NONE, 'Ne traite que les tags')
    ;
  }
  
  /** @return TagMiner */
  protected function getTagMiner()
  {
    return $this->tag_miner;
  }
  
  /** @return \Doctrine\ORM\EntityManager */
  protected function getEntityManager()
  {
    return $this->em;
  }

  protected function init(InputInterface $input, OutputInterface $output)
  {
    $this->em = $this->getContainer()->get('doctrine')->getEntityManager();
    $this->tag_miner = $this->getContainer()->get('muzich.mining.tag.miner');
    
    // Experimental
    $this->tag_miner->setLogger($this);
    
    $this->progress = $this->getHelperSet()->get('progress');
    $this->input = $input;
    $this->output = $output;
  }
  
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->init($input, $output);
    
    if ($this->canIMineThat(self::MINE_DIFFUSION))
      $this->mineDiffusions();
    
    if ($this->canIMineThat(self::MINE_FAVORITE))
      $this->mineFavorites();
    
    if ($this->canIMineThat(self::MINE_PLAYLIST))
      $this->minePlaylists();
    
    if ($this->canIMineThat(self::MINE_TAGS))
      $this->mineTags();
    
    $this->output->writeln('<info>Terminé !</info>');
  }
  
  /** @return QueryBuilder */
  protected function getUserQueryBuilder()
  {
    $user_query_builder = $this->getEntityManager()->createQueryBuilder()
      ->from('MuzichCoreBundle:User', 'user');
    $this->tag_miner->adaptQueryBuilderSelectorsForUser($user_query_builder, 'user');
    
    return $user_query_builder;
  }
  
  protected function mineDiffusions()
  {
    $this->output->writeln('<info>Retriving users ...</info>');
    $users = $this->getUsersToProceed(User::DATA_DIFF_UPDATED);
    $this->output->writeln('<info>Diffusions: '.count($users).' utilisateurs</info>');
    $this->progress->start($this->output, count($users));
    $this->getTagMiner()->mineDiffusionTagsForUsers($users);
    $this->addUsersToUsersMineds($users);
  }
  
  protected function mineFavorites()
  {
    $users = $this->getUsersToProceed(User::DATA_FAV_UPDATED);
    $this->output->writeln('<info>Favoris: '.count($users).' utilisateurs</info>');
    $this->progress->start($this->output, count($users));
    $this->getTagMiner()->mineFavoriteTagsForUsers($users);
    $this->addUsersToUsersMineds($users);
  }
  
  protected function minePlaylists()
  {
    $users = $this->getUsersToProceed(User::DATA_PLAY_UPDATED);
    $this->output->writeln('<info>Playlists: '.count($users).' utilisateurs</info>');
    $this->progress->start($this->output, count($users));
    $this->getTagMiner()->minePlaylistTagsForUsers($users);
    $this->addUsersToUsersMineds($users);
  }
  
  protected function mineTags()
  {
    $this->output->writeln('<info>Tags: '.count($this->users_mineds).' utilisateurs</info>');
    $this->progress->start($this->output, count($this->users_mineds));
    $this->getTagMiner()->mineTagsForUsers($this->users_mineds);
  }
  
  protected function getUsersToProceed($condition)
  {
    $users_query_builder = $this->getUserQueryBuilder();
    if (!$this->input->getOption('all'))
    {
      $users_query_builder->andWhere('user.datas NOT LIKE :condition OR user.datas IS NULL');
      $users_query_builder->setParameter('condition', '%"'.$condition.'":false%');
    }
    
    return $users_query_builder->getQuery()->getResult();
  }
  
  protected function canIMineThat($mine_type_asked)
  {
    $mine_type_specified = false;
    foreach (self::$mine_types as $mine_type)
    {
      if ($this->input->getOption($mine_type))
        $mine_type_specified = true;
    }
    
    if (!$mine_type_specified)
      return true;
    
    if ($this->input->getOption($mine_type_asked))
      return true;
    
    return false;
  }
  
  public function logUserProceed()
  {
    $this->progress->advance();
  }
  
  public function logSavingInDatabase()
  {
    $this->output->writeln('');
    $this->output->writeln('<info>Saving in database ...</info>');
  }
  
  protected function addUsersToUsersMineds($users_to_add)
  {
    foreach ($users_to_add as $user_to_add)
    {
      $found = false;
      foreach ($this->users_mineds as $user_mined)
      {
        if ($user_mined->getId() == $user_to_add->getId())
          $found = true;
      }
      
      if (!$found)
        $this->users_mineds[] = $user_to_add;
    }
  }
  
}