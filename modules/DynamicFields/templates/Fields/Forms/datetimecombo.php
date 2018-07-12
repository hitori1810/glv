<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/

require_once('modules/DynamicFields/templates/Fields/TemplateDatetimecombo.php');

function get_body(&$ss, $vardef){
	$defaultTime = '';
	$hours = "";
	$minitues = "";
	$meridiem = "";
	$td = new TemplateDatetimecombo();
	$ss->assign('default_values', array_flip($td->dateStrings));
	
    global $timedate;
    $user_time_format = $timedate->get_user_time_format();
    $show_meridiem = preg_match('/pm$/i', $user_time_format) ? true : false;
    if($show_meridiem) {
    	$ss->assign('default_hours_values', array_flip($td->hoursStrings));
    } else {
    	$ss->assign('default_hours_values', array_flip($td->hoursStrings24));
    }

    $ss->assign('show_meridiem', $show_meridiem);
	$ss->assign('default_minutes_values', array_flip($td->minutesStrings));
	$ss->assign('default_meridiem_values', array_flip($td->meridiemStrings));
	if(isset($vardef['display_default']) && strstr($vardef['display_default'] , '&')){
		$dt = explode("&", $vardef['display_default']); //+1 day&06:00pm
		$date = $dt[0];
		$defaultTime = $dt[1];
		$hours = substr($defaultTime, 0, 2); 
		$minitues = substr($defaultTime, 3, 2);
		$meridiem = substr($defaultTime, 5, 2);
		if(!$show_meridiem) {
		   preg_match('/(am|pm)$/i', $meridiem, $matches);
		   if(strtolower($matches[0]) == 'am' && $hours == 12) {
		   	  $hours = '00';
		   } else if (strtolower($matches[0]) == 'pm' && $hours != 12) {
		   	  $hours += 12;
		   }
		}
		$ss->assign('default_date', $date);
	}
	$ss->assign('default_hours', $hours);
	$ss->assign('default_minutes', $minitues);
	$ss->assign('default_meridiem', $meridiem);
	$ss->assign('defaultTime', $defaultTime);
	return $ss->fetch('modules/DynamicFields/templates/Fields/Forms/datetimecombo.tpl');
}

?>
