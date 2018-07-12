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








global $mod_strings;
global $app_strings;
global $app_list_strings;
global $focus;
$focus = new ReportMaker();
if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
    if($result == null)
    {
    	sugar_die($app_strings['ERROR_NO_RECORD']);
    }
}
else {
	header("Location: index.php?module=ReportMaker&action=index");
}

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

$params = array();
$params[] = $focus->get_summary_text();
echo getClassicModuleTitle("ReportMaker", $params, true);

$GLOBALS['log']->info("ReportMaker detail view");

$xtpl=new XTemplate ('modules/ReportMaker/DetailView.html');
$sub_xtpl = new XTemplate ('modules/ReportMaker/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("GRIDLINE", $gridline);
$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);

$xtpl->assign("ID", $focus->id);
$xtpl->assign('NAME', $focus->name);
$xtpl->assign('TITLE', $focus->title);
$xtpl->assign("DESCRIPTION", nl2br($focus->description));


$xtpl->assign("REPORT_ALIGN", $app_list_strings['report_align_dom'][$focus->report_align]);

$xtpl->assign("TEAM", $focus->assigned_name);

global $current_user;

//if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
//	$xtpl->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$_REQUEST['record']. "'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDITLAYOUT'])."</a>");
//}

// adding custom fields:
require_once('modules/DynamicFields/templates/Files/DetailView.php');


$xtpl->parse("main");
$xtpl->out("main");

//Show the datasets

$old_contents = ob_get_contents();
ob_end_clean();

if($sub_xtpl->var_exists('subpanel', 'SUBDATASETS')){
ob_start();
global $focus_list;
$focus_list = $focus->get_data_sets("ORDER BY list_order_y ASC");
include('modules/DataSets/SubPanelView.php');
echo "<BR>\n";
$subdatasets =ob_get_contents();
ob_end_clean();
}

ob_start();
echo $old_contents;

if(!empty($subdatasets))$sub_xtpl->assign('SUBDATASETS', $subdatasets);

$sub_xtpl->parse("subpanel");
$sub_xtpl->out("subpanel");

	
		
?>
