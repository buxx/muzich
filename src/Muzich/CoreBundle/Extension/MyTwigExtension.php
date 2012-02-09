<?php

namespace Muzich\CoreBundle\Extension;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class MyTwigExtension extends \Twig_Extension {

  private $translator;

  public function __construct(Translator $translator)
  {
    $this->translator = $translator;
  }
  
  public function getFilters()
  {
    return array(
      'var_dump'               => new \Twig_Filter_Function('var_dump'),
      'date_or_relative_date'  => new \Twig_Filter_Method($this, 'date_or_relative_date')
    );
  }
  
  protected function datetime2timestamp($string)
  {
    list($date, $time) = explode(' ', $string);
    list($year, $month, $day) = explode('-', $date);
    list($hour, $minute, $second) = explode(':', $time);

    $timestamp = mktime($hour, $minute, $second, $month, $day, $year);

    return $timestamp;
  }

  public function date_or_relative_date($sentence, $expr = null)
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
        
    if( $iSeconds<60 )
    {
      return $this->translator->trans('date.less_than_minute', array(), 'userui');
    }
    elseif( $iMinutes<60 )
    {
      return $this->translator->transChoice(
        'il y a une minute|Il y a %count% minutes',
        1,
        array('%count%' => $iMinutes)
      );
    }
    elseif( $iHours<24 )
    {
      return $this->translator->transChoice(
        'il y a une heure|Il y a %count% heures',
        1,
        array('%count%' => $iHours)
      );
    }
    elseif( $iDays<7 )
    {
      return $this->translator->transChoice(
        'il y a un jour|Il y a %count% jours',
        1,
        array('%count%' => $iDays)
      );
    }
    elseif( $iWeeks <4 )
    {
      return $this->translator->transChoice(
        'il y a une semaine|Il y a %count% semaines',
        1,
        array('%count%' => $iWeeks)
      );
    }
    elseif( $iMonths<12 )
    {
      return $this->translator->transChoice(
        'il y a un mois|Il y a %count% mois',
        1,
        array('%count%' => $iMonths)
      );
    }
    else
    {
      return $this->translator->transChoice(
        'il y a un an|Il y a %count% ans',
        1,
        array('%count%' => $iYears)
      );
    }
  }

  public function getName()
  {
    return 'my_twig_extension';
  }

}
