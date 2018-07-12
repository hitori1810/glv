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










global $timedate;
global $app_strings;
global $app_list_strings;
global $current_language, $current_user;
$current_module_strings = return_module_language($current_language, 'ProjectTask');

$today = $timedate->nowDbDate(); 
$today = $timedate->handle_offset($today, $timedate->dbDayFormat, false);

$ListView = new ListView();
$seedProjectTask = new ProjectTask();
$where = "project_task.assigned_user_id='{$current_user->id}'"
	. " AND (project_task.status IS NULL OR (project_task.status!='Completed' AND project_task.status!='Deferred'))"
	. " AND (project_task.date_start IS NULL OR project_task.date_start <= '$today')";
$ListView->initNewXTemplate('modules/ProjectTask/MyProjectTasks.html',
	$current_module_strings);
$header_text = '';

if(is_admin($current_user)
	&& $_REQUEST['module'] != 'DynamicLayout'
	&& !empty($_SESSION['editinplace']))
{	
	$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=MyTasks&from_module=Tasks'>"
		. SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDITLAYOUT'])
		. '</a>';
}
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_MY_PROJECT_TASKS'].$header_text);
$ListView->setQuery($where, "", "date_due,priority desc", "PROJECT_TASK");
$ListView->processListView($seedProjectTask, "main", "PROJECT_TASK");
?>
