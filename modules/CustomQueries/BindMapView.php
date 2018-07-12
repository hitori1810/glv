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

/*********************************************************************************

 * Description:  
 ********************************************************************************/





$header_text = '';
global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;

$focus = new CustomQuery();

	if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
    	$focus->retrieve($_REQUEST['record']);
	}
	
	if(isset($_REQUEST['old_column_array']) && $_REQUEST['old_column_array']!="") {
		$old_column_array = $_REQUEST['old_column_array'];
	}


if (!is_admin($current_user))
{
   sugar_die($app_strings['LBL_UNAUTH_ADMIN']);
}

global $theme;


$GLOBALS['log']->info("DataSets edit view");

$xtpl=new XTemplate ('modules/CustomQueries/BindMapView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

	$temp_select = $focus->repair_column_binding();
	$temp_select2 = $temp_select;

	foreach($_SESSION['old_column_array'] as $key => $value){
		//eliminate direct matches
		if(!empty($temp_select2[$value])){
				unset($temp_select2[$value]);
		//end eliminate direct matches
		}
	//foreach	
	}	

	foreach($_SESSION['old_column_array'] as $key => $value){
		//only show if there is no direct match
		if(empty($temp_select[$value])){
			$selectdropdown = get_select_options_with_id($temp_select2,$value);
			$xtpl->assign("OLD_COLUMN_NAME", $value);
			$xtpl->assign("SELECT_NAME","column_".$key);
			$xtpl->assign("SELECT_OPTIONS",$selectdropdown);
			$xtpl->parse("main.row");
		//end if only show if no direct match
		} else {
			//remove this element from the array
			unset($temp_select[$value]);	
		}	

	//foreach	
	}	
	
	$xtpl->assign("ID", $_REQUEST['record']);

	$xtpl->parse("main");
	$xtpl->out("main");

?>
