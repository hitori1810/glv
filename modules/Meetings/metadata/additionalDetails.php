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


function additionalDetailsMeeting($fields) {
	static $mod_strings;
	if(empty($mod_strings)) {
		global $current_language;
		$mod_strings = return_module_language($current_language, 'Meetings');
	}
		
	$overlib_string = '';
	
    if(!empty($fields['NAME'])) {
          	$overlib_string .= '<b>'. $mod_strings['LBL_SUBJECT'] . '</b> ' . $fields['NAME'];
          	$overlib_string .= '<br>';
    }
	if(!empty($fields['DATE_START'])) 
		$overlib_string .= '<b>'. $mod_strings['LBL_DATE_TIME'] . '</b> ' . $fields['DATE_START'] . ' <br>';
	
    if(isset($fields['DURATION_HOURS']) || isset($fields['DURATION_MINUTES'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_DURATION'] . '</b> '; 
        if(isset($fields['DURATION_HOURS'])) {
            $overlib_string .= $fields['DURATION_HOURS'] . $mod_strings['LBL_HOURS_ABBREV'] . ' ';
        }
        if(isset($fields['DURATION_MINUTES'])) {
            $overlib_string .=  $fields['DURATION_MINUTES'] . $mod_strings['LBL_MINSS_ABBREV'];
        }
        $overlib_string .=  '<br>';
	}

    if (!empty($fields['PARENT_ID']))
    {
         $overlib_string .= "<b>". $mod_strings['LBL_RELATED_TO'] . "</b> ".
   	               "<a href='index.php?module=".$fields['PARENT_TYPE']."&action=DetailView&record=".$fields['PARENT_ID']."'>".
   	               $fields['PARENT_NAME'] . "</a>";
   	       $overlib_string .= '<br>';
    }

    if(!empty($fields['STATUS'])) {
  	    $overlib_string .= '<b>'. $mod_strings['LBL_STATUS'] . '</b> ' . $fields['STATUS'];
  	    $overlib_string .= '<br>';
      }


    if(!empty($fields['DESCRIPTION'])) {
		$overlib_string .= '<b>'. $mod_strings['LBL_DESCRIPTION'] . '</b> ' . substr($fields['DESCRIPTION'], 0, 300);
		if(strlen($fields['DESCRIPTION']) > 300) $overlib_string .= '...';
		$overlib_string .= '<br>';
	}

	
	$editLink = "index.php?action=EditView&module=Meetings&record={$fields['ID']}"; 
	$viewLink = "index.php?action=DetailView&module=Meetings&record={$fields['ID']}";	
	
	return array('fieldToAddTo' => 'NAME', 
				 'string' => $overlib_string, 
				 'editLink' => $editLink, 
				 'viewLink' => $viewLink);
	
}
 
?>
