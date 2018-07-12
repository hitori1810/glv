<?php

require_once('include/DetailView/DetailView.php');
require_once('data/SugarBean.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;
global $focus, $support_coming_due, $support_expired;
global $sugar_config, $current_user;
    
$focus = new bc_survey_automizer();
$focus->retrieve($_REQUEST['record']);

$detailView = new DetailView();
$offset = 0;
//$access = get_workflow_admin_modules_for_user($current_user);

$params = array();
$params[] = "<a href='index.php?module=bc_survey_automizer&action=index'>{$mod_strings['LBL_MODULE_NAME']}</a>";
$params[] = $focus->get_summary_text();

$GLOBALS['log']->info("survey automizer detail view");

$xtpl = new XTemplate('modules/bc_survey_automizer/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign('NAME', $focus->name);
$xtpl->assign('ID', $focus->id);
$xtpl->assign("DESCRIPTION", nl2br($focus->description));


$xtpl->assign('STATUS', $app_list_strings['status_list'][$focus->status]);
$xtpl->assign('TYPE', $app_list_strings['applied_to_list'][$focus->run_when]);
$xtpl->assign('RECORD_TYPE', $app_list_strings['execution_occurs_list'][$focus->flow_run_on]);
$xtpl->assign('BASE_MODULE', $focus->flow_module);



$xtpl->parse("main");
$xtpl->out("main");

$sub_xtpl = $xtpl;
$old_contents = ob_get_contents();
ob_end_clean();
ob_start();
echo $old_contents;



