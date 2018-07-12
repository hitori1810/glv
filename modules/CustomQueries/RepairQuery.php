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

if (!is_admin($current_user))
{
   sugar_die($app_strings['LBL_UNAUTH_ADMIN']);
}

global $theme;

$GLOBALS['log']->info("DataSets edit view");

$xtpl=new XTemplate ('modules/CustomQueries/RepairQuery.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

	$xtpl->assign("ID", $_REQUEST['record']);
	$xtpl->assign("QUERY_MSG", $_REQUEST['error_msg']);
	
	if(!empty($_REQUEST['edit'])){
		$xtpl->assign("EDIT", $_REQUEST['edit']);
	}
	//if(!empty($_REQUEST['edit']) && $_REQUEST['edit'] !=true){
		$xtpl->assign("REPAIR", "repair");	
	//}	
	
	$xtpl->parse("main");
	$xtpl->out("main");

?>
