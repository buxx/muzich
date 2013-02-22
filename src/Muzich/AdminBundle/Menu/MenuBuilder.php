<?php

namespace Muzich\AdminBundle\Menu;

use Admingenerator\GeneratorBundle\Menu\AdmingeneratorMenuBuilder as BaseMenu;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder extends BaseMenu
{
  
  public function createDashboardMenu(Request $request)
  {
     $menu = $this->factory->createItem('root');
  
     $menu->setChildrenAttributes(array('id' => 'dashboard_sidebar', 'class' => 'nav nav-list'));
     $menu->setExtra('request_uri', $this->container->get('request')->getRequestUri());
     $menu->setExtra('translation_domain', 'Admingenerator');
     
     $this->addNavHeader($menu, 'Overview');
     $this->addNavLinkRoute($menu, 'Dashboard', 'AdmingeneratorDashboard_welcome')->setExtra('icon', 'icon-home');
     $this->addNavHeader($menu, 'Features');
     $this->addNavLinkRoute($menu, 'Statistics', 'AdmingeneratorDashboard_welcome', array('document' => 'commands'))->setExtra('icon', 'icon-bullhorn');
     $this->addNavLinkUri($menu, 'Wiki', 'http://work.bux.fr/projects/muzich/wiki', array('document' => 'filters'))->setExtra('icon', 'icon-filter');
     $this->addNavHeader($menu, 'Administration');
     $this->addAdministrationLinksToMenu($menu);
     
     return $menu;
  }
  
  protected function addAdministrationLinksToMenu($menu)
  {
    $this->addNavLinkRoute($menu, 'Users', 'Muzich_AdminBundle_Admin_user_list');
    $this->addNavLinkRoute($menu, 'Elements', 'Muzich_AdminBundle_Admin_element_list');
    $this->addNavLinkRoute($menu, 'Tags', 'Muzich_AdminBundle_Admin_tag_list');
    $this->addNavLinkRoute($menu, 'Groups', 'Muzich_AdminBundle_Admin_group_list');
    $this->addNavLinkRoute($menu, 'Group Follows', 'Muzich_AdminBundle_Admin_follow_group_list');
    $this->addNavLinkRoute($menu, 'User Follows', 'Muzich_AdminBundle_Admin_follow_user_list');
    $this->addNavLinkRoute($menu, 'Users Tags Favorites', 'Muzich_AdminBundle_Admin_users_tags_favorites_list');
    $this->addNavLinkRoute($menu, 'Groups Tags Favorites', 'Muzich_AdminBundle_Admin_groups_tags_favorites_list');
    $this->addNavLinkRoute($menu, 'Element Tags propositions', 'Muzich_AdminBundle_Admin_element_tags_proposition_list');
    $this->addNavLinkRoute($menu, 'Events Archives', 'Muzich_AdminBundle_Admin_event_archive_list');
    $this->addNavLinkRoute($menu, 'Registration Tokens', 'Muzich_AdminBundle_Admin_registration_token_list');
    $this->addNavLinkRoute($menu, 'Pre-subscriptions', 'Muzich_AdminBundle_Admin_presubscription_list');
  }
  
  public function createAdminMenu(Request $request)
  {
    $menu = parent::createAdminMenu($request);
    $menu->setExtra('translation_domain', 'Admingenerator');
    $administration = $this->addDropdownMenu($menu, 'Administration');
    $this->addAdministrationLinksToMenu($administration);
    
    return $menu;
  }
}
