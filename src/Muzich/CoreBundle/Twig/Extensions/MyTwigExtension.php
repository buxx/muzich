<?php

namespace Muzich\CoreBundle\Twig\Extensions;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Muzich\CoreBundle\Entity\Event;
use Symfony\Component\Form\FormView;
use Symfony\Component\DependencyInjection\Container;
use Muzich\CoreBundle\Entity\User;

class MyTwigExtension extends \Twig_Extension {

  private $translator;
  private $container;
  protected $params = array();

  public function __construct(Translator $translator, $params, Container $container)
  {
    $this->translator = $translator;
    $this->params = $params;
    $this->container = $container;
  }
  
  public function getFilters()
  {
    return array(
      'var_dump'               => new \Twig_Filter_Function('var_dump'),
      'date_or_relative_date'  => new \Twig_Filter_Method($this, 'date_or_relative_date'),
      'date_epurate'           => new \Twig_Filter_Method($this, 'date_epurate'),
      'form_has_errors'        => new \Twig_Filter_Method($this, 'form_has_errors'),
      'format_score'           => new \Twig_Filter_Method($this, 'format_score'),
      'can_autoplay'           => new \Twig_Filter_Method($this, 'can_autoplay'),
      'can_autoplay_type'      => new \Twig_Filter_Method($this, 'can_autoplay_type'),
      'userId'                 => new \Twig_Filter_Method($this, 'id_or_null')
    );
  }
  
  public function getFunctions() {
    return array(
      'date_or_relative_date'  => new \Twig_Function_Method($this, 'date_or_relative_date'),
      'event_const'            => new \Twig_Function_Method($this, 'event_const'),
      'css_list_length_class'  => new \Twig_Function_Method($this, 'getCssLengthClassForList'),
      'token'                  => new \Twig_Function_Method($this, 'token'),
      'path_token'             => new \Twig_Function_Method($this, 'path_token')
    );
  }
  
  public function format_score($score)
  {
    return number_format($score, 0, '.', ' ');
  }
  
  public function can_autoplay($element)
  {
    if (in_array($element->getType(), $this->params['autoplay_sites_enabled']))
    {
      return true;
    }
    return false;
  }
  
  public function can_autoplay_type($element_type)
  {
    if (in_array($element_type, $this->params['autoplay_sites_enabled']))
    {
      return true;
    }
    return false;
  }
  
  protected function datetime2timestamp($string)
  {
    list($date, $time) = explode(' ', $string);
    list($year, $month, $day) = explode('-', $date);
    list($hour, $minute, $second) = explode(':', $time);

    $timestamp = mktime($hour, $minute, $second, $month, $day, $year);

    return $timestamp;
  }
  
  protected function translate_date_relative($tr, $type, $x)
  {
    if ($x != 1)
    {
      return $this->translator->trans(
        $tr.'x_'.$type, 
        array('%x%' => $x), 
        'messages'
      );
    }
    return $this->translator->trans(
      $tr.'one_'.$type, 
      array(), 
      'messages'
    );
  }

  public function date_or_relative_date($sentence, $context = "default")
  {
    $iTimeDifference = time() - $this->datetime2timestamp($sentence);
    if( $iTimeDifference<0 ) 
    { 
      return $this->translator->trans('date.instant', array(), 'userui');; 
    }
    $iSeconds 	= $iTimeDifference ;
    $iMinutes 	= round( $iTimeDifference/60 );
    $iHours   	= round( $iTimeDifference/3600 );
    $iDays 	  	= round( $iTimeDifference/86400 );
    $iWeeks   	= round( $iTimeDifference/604800 );
    $iMonths   	= round( $iTimeDifference/2419200 );
    $iYears   	= round( $iTimeDifference/29030400 );
        
    $tr = 'date_since.'.$context.'.';
    
    if( $iSeconds<60 )
    {
      return $this->translator->trans('date_since.'.$context.'.less_min', array(), 'messages');
    }
    elseif( $iMinutes<60 )
    {
      return $this->translate_date_relative($tr, 'min', $iMinutes);
    }
    elseif( $iHours<24 )
    {
      return $this->translate_date_relative($tr, 'hour', $iHours);
    }
    elseif( $iDays<7 )
    {
      return $this->translate_date_relative($tr, 'day', $iDays);
    }
    elseif( $iWeeks <4 )
    {
      return $this->translate_date_relative($tr, 'week', $iWeeks);
    }
    elseif( $iMonths<12 )
    {
      return $this->translate_date_relative($tr, 'month', $iMonths);
    }
    else
    {
      return $this->translate_date_relative($tr, 'year', $iYears);
    }
  }

  public function getName()
  {
    return 'my_twig_extension';
  }
  
  public function date_epurate($date)
  {
    $date = str_replace(' ', '', $date);
    $date = str_replace('-', '', $date);
    $date = str_replace(':', '', $date);
    $date = str_replace('.', '', $date);
    return $date;
  }
  
  public function event_const($const_name)
  {
    switch ($const_name)
    {
      case 'TYPE_COMMENT_ADDED_ELEMENT':
        return Event::TYPE_COMMENT_ADDED_ELEMENT;
      break;
      case 'TYPE_FAV_ADDED_ELEMENT':
        return Event::TYPE_FAV_ADDED_ELEMENT;
      break;
      case 'TYPE_USER_FOLLOW':
        return Event::TYPE_USER_FOLLOW;
      break;
      case 'TYPE_TAGS_PROPOSED':
        return Event::TYPE_TAGS_PROPOSED;
      break;
      default:
        throw new \Exception('Constante non géré dans MyTwigExtension::event_const');
      break;
    }
    return null;
  }
  
  /**
   * Cette fonction retourne une classe CSS (string) permettant de donner
   * de l'importance a des élements de liste en foncyion de leur position dans
   * la liste.
   * 
   * @param int $position
   * @param int $count
   * @return string 
   */
  public function getCssLengthClassForList($position, $count)
  {
    // On établie 3 type de taille
    // grand, moyen, standart
    // chacun correspondant a 1/3
    
    if ($position <= $count/3)
    {
      return 'list_length_big';
    }
    elseif ($position <= ($count/3)*2)
    {
      return 'list_length_medium';
    }
    else
    {
      return 'list_length_default';
    }
  }
  
  public function token($intention = '')
  {
    return $this->container->get('form.csrf_provider')->generateCsrfToken($intention);
  }
  
  public function path_token($route, $parameters = array(), $intention = '', $absolute = false)
  {
    $parameters = array_merge($parameters, array('token' => $this->token($intention)));
    return $this->container->get('router')->generate($route, $parameters, $absolute);
  }
  
  public function form_has_errors(FormView $form)
  {
    $form_vars = $form->getVars();
    $count_error = count($form_vars['errors']);
    foreach ($form as $form_children)
    {
      $form_children_vars = $form_children->getVars();
      $count_error += count($form_children_vars['errors']);
    }
    if ($count_error)
    {
      return true;
    }
    return false;
  }
  
  public function id_or_null($user)
  {
    if ($user)
    {
      if ($user instanceof User)
      {
        return $user->getId();
      }
    }
    
    return null;
  }

}
