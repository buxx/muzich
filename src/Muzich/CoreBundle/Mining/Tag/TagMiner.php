<?php
namespace Muzich\CoreBundle\Mining\Tag;

use Doctrine\ORM\EntityManager;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry as MongoManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Muzich\CoreBundle\Document\EntityTags;
use Muzich\CoreBundle\Document\UserTags;
use Muzich\CoreBundle\Document\GroupTags;
use Muzich\CoreBundle\Document\PlaylistTags;
use Doctrine\ORM\QueryBuilder;
use Muzich\CoreBundle\lib\Tag as TagOrderer;
use Muzich\CoreBundle\lib\TagScorer;
use Muzich\CoreBundle\Entity\User;
use Muzich\CoreBundle\Managers\PlaylistManager;

class TagMiner
{
  
  protected $doctrine_entity_manager;
  protected $mongo_manager_registry;
  protected $tag_scorer;
  protected $tag_orderer;
  
  public function __construct(EntityManager $doctrine_entity_manager, MongoManagerRegistry $mongo_manager_registry)
  {
    $this->doctrine_entity_manager = $doctrine_entity_manager;
    $this->mongo_manager_registry = $mongo_manager_registry;
    $this->tag_scorer = new TagScorer();
    $this->tag_orderer = new TagOrderer();
  }
  
  /** @return EntityManager */
  protected function getDoctrineEntityManager()
  {
    return $this->doctrine_entity_manager;
  }
  
  /** @return DocumentRepository */
  protected function getMongoRepository($repository)
  {
    return $this->mongo_manager_registry->getRepository($repository);
  }
  
  /** @return DocumentManager */
  protected function getMongoManager()
  {
    return $this->mongo_manager_registry->getManager();
  }
  
  /** @return TagScorer */
  protected function getTagsScorer()
  {
    return $this->tag_scorer;
  }
  
  /** @return TagOrderer */
  protected function getTagOrderer()
  {
    return $this->tag_orderer;
  }
  
  /** 
   * @param QueryBuilder $query_builder
   * @param string $user_alias
   */
  public function adaptQueryBuilderSelectorsForUser(QueryBuilder $query_builder, $user_alias = 'user')
  {
    // Adapt query builder to necessary data in mining
    $query_builder->leftJoin($user_alias.'.elements', 'element_owned');
    $query_builder->leftJoin('element_owned.tags', 'element_owned_tags');
    
    $query_builder->leftJoin($user_alias.'.elements_favorites', 'element_favorite');
    $query_builder->leftJoin('element_favorite.element', 'element_favorite_element');
    
    $query_builder->select($user_alias.', element_owned, element_owned_tags, element_favorite');
    
  }
  
  /**
   * @param array $users
   */
  public function mineTagsForUsers($users)
  {
    foreach ($users as $user)
    {
      $user_tags = $this->getEntityTagsDocument($user->getId(), EntityTags::TYPE_USER);
      
      $this->scoreUserDiffusionsTags($user_tags, $user);
      $this->scoreUserFavoritesTags($user_tags, $user);
      $this->scoreUserPlaylistsTags($user_tags, $user);
      $this->scoreUserTags($user_tags, $user);
      $this->determineTagsTops($user_tags);
      
      $this->getMongoManager()->persist($user_tags);
    }
    
    $this->getMongoManager()->flush();
  }
  
  /** @return EntityTags */
  protected function getEntityTagsDocument($ref, $type)
  {
    if (!($user_tags = $this->getMongoManager()->createQueryBuilder('MuzichCoreBundle:'.$type.'Tags')
      ->field('ref')->equals((int)$ref)
      ->getQuery()->getSingleResult()
    ))
    {
      $user_tags = $this->getObjectTypeTags($type);
      $user_tags->setRef($ref);
    }
    
    return $user_tags;
  }
  
  /** @return EntityTags */
  protected function getObjectTypeTags($type)
  {
    switch ($type)
    {
      case EntityTags::TYPE_USER:
        return new UserTags();
      break;
      case EntityTags::TYPE_GROUP:
        return new GroupTags();
      break;
      case EntityTags::TYPE_PLAYLIST:
        return new PlaylistTags();
      break;
    }
  }
  
  protected function scoreUserDiffusionsTags(EntityTags $user_tags, User $user)
  {
    $tags_ids_ordereds = $this->getTagOrderer()->getOrderedTagsWithElements($user->getElements());
    $scoreds_tags_ids = $this->getTagsScorer()->scoreOrderedsTagsIds($tags_ids_ordereds);
    $user_tags->setElementDiffusionTags($scoreds_tags_ids);
  }
  
  protected function scoreUserFavoritesTags(EntityTags $user_tags, User $user)
  {
    $tags_ids_ordereds = $this->getTagOrderer()->getOrderedTagsWithElements($user->getElementsFavoritesElements());
    $scoreds_tags_ids = $this->getTagsScorer()->scoreOrderedsTagsIds($tags_ids_ordereds);
    $user_tags->setElementFavoriteTags($scoreds_tags_ids);
  }
  
  protected function scoreUserPlaylistsTags(EntityTags $user_tags, User $user)
  {
    $playlist_manager = new PlaylistManager($this->getDoctrineEntityManager());
    $tags_ids_ordereds = $this->getTagOrderer()->getOrderedTagsWithElements($playlist_manager->getElementsOfPlaylists($this->getUserPlaylists($user)));
    $scoreds_tags_ids = $this->getTagsScorer()->scoreOrderedsTagsIds($tags_ids_ordereds);
    $user_tags->setElementPlaylistTags($scoreds_tags_ids);
  }
  
  protected function getUserPlaylists(User $user)
  {
    $playlists = $user->getPlaylistsOwneds();
    foreach ($user->getPickedsPlaylists() as $picked_playlist)
    {
      $found = false;
      foreach ($playlists as $playlist)
      {
        if ($playlist->getId() == $picked_playlist->getId())
        {
          $found = true;
        }
      }
      
      if (!$found)
        $playlists[] = $picked_playlist;
    }
    
    return $playlists;
  }
  
  protected function scoreUserTags(EntityTags $user_tags, User $user)
  {
    $all_tags_ordered = $this->getTagsScorer()->scoreEntireOrderedTagsIds(array(
      $user_tags->getElementDiffusionTags(),
      $user_tags->getElementFavoriteTags(),
      $user_tags->getElementPlaylistTags()
    ), $user->getTagsFavoritesQuickIds());
    
    $user_tags->setTagsAll($all_tags_ordered);
  }
  
  protected function determineTagsTops(EntityTags $user_tags)
  {
    $user_tags->setTagsTop1($this->getTopTagsRange($user_tags->getTagsAll(), 1));
    $user_tags->setTagsTop2($this->getTopTagsRange($user_tags->getTagsAll(), 2));
    $user_tags->setTagsTop3($this->getTopTagsRange($user_tags->getTagsAll(), 3));
    $user_tags->setTagsTop5($this->getTopTagsRange($user_tags->getTagsAll(), 5));
    $user_tags->setTagsTop10($this->getTopTagsRange($user_tags->getTagsAll(), 10));
    $user_tags->setTagsTop25($this->getTopTagsRange($user_tags->getTagsAll(), 25));
  }
  
  protected function getTopTagsRange($tags, $range_end)
  {
    $tags_top = array();
    if ($range_end <= count($tags))
    {
      $max = $range_end;
    }
    else
    {
      $max = count($tags);
    }
      
    for ($index = 0; $index <= $max-1; $index++)
    {
      $tags_top[] = $tags[$index];
    }
    
    
    return $tags_top;
  }
  
}