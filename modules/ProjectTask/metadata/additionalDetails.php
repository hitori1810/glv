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

 


function additionalDetailsProjectTask($fields) {
	static $mod_strings;
	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'ProjectTask');
	}
		
	$overlib_string = '';
	if(!empty($fields['PRIORITY'])) $overlib_string .= '<b>' . $mod_strings['LBL_PRIORITY'] . '</b> ' . $fields['PRIORITY'] . '<br>';
	if(!empty($fields['PERCENT_COMPLETE'])) $overlib_string .= '<b>' . $mod_strings['LBL_PERCENT_COMPLETE'] . '</b> ' . $fields['PERCENT_COMPLETE'] . '%<br>';	
	if(!empty($fields['ESTIMATED_EFFORT'])) $overlib_string .= '<b>' . $mod_strings['LBL_ESTIMATED_EFFORT'] . '</b> ' . $fields['ESTIMATED_EFFORT'] . '<br>';	
	if(!empty($fields['ACTUAL_EFFORT'])) $overlib_string .= '<b>' . $mod_strings['LBL_ACTUAL_EFFORT'] . '</b> ' . $fields['ACTUAL_EFFORT'] . '<br>';
	if(!empty($fields['TASK_NUMBER'])) $overlib_string .= '<b>' . $mod_strings['LBL_TASK_NUMBER'] . '</b> ' . $fields['TASK_NUMBER'] . '<br>';
	if(!empty($fields['DATE_START'])) $overlib_string .= '<b>' . $mod_strings['LBL_DATE_START'] . '</b> ' . $fields['DATE_START'] . ' ' . $fields['TIME_START'] . '<br>';
		
	if(!empty($fields['DESCRIPTION'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ' . substr($fields['DESCRIPTION'], 0, 300);
		if(strlen($fields['DESCRIPTION']) > 300) $overlib_string .= '...';
	}	

	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'editLink' => "index.php?action=EditView&module=ProjectTask&return_module=ProjectTask&record={$fields['ID']}", 
				 'viewLink' => "index.php?action=DetailView&module=ProjectTask&return_module=ProjectTask&record={$fields['ID']}");
}
 
 ?>
 
 