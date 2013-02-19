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
    $this->addNavLinkRoute($menu, 'Elements', 'Muzich_AdminBundle_Admin_element_list');
    $this->addNavLinkRoute($menu, 'Tags', 'Muzich_AdminBundle_Admin_tag_list');
    $this->addNavLinkRoute($menu, 'Groups', 'Muzich_AdminBundle_Admin_group_list');
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
