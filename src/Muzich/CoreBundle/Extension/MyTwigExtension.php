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
      'date_or_relative_date'  => new \Twig_Filter_Method($this, 'date_or_relative_date'),
      'date_epurate'            => new \Twig_Filter_Method($this, 'date_epurate')
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
    return $date;
  }

}
