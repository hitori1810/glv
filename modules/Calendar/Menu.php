<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 


global $mod_strings;
//if(ACLController::checkAccess('Meetings', 'edit', true))
//if(ACLController::checkAccess('Calls', 'edit', true))$module_menu[]=Array("index.php?module=Calls&action=EditView&return_module=Calls&return_action=DetailView", $mod_strings['LNK_NEW_CALL'],"CreateCalls");
//if(ACLController::checkAccess('Tasks', 'edit', true))$module_menu[]=Array("index.php?module=Tasks&action=EditView&return_module=Tasks&return_action=DetailView", $mod_strings['LNK_NEW_TASK'],"CreateTasks");
if(ACLController::checkAccess('Calendar', 'list', true))$module_menu[]=Array("index.php?module=Calendar&action=index&view=week&only=Meeting", $mod_strings['LNK_VIEW_MEETING'],"Calendar");
if(ACLController::checkAccess('Calendar', 'list', true))$module_menu[]=Array("index.php?module=Calendar&action=index&view=week&only=Testing", $mod_strings['LNK_VIEW_TESTING'],"Calendar");
if(ACLController::checkAccess('Calendar', 'list', true))$module_menu[]=Array("index.php?module=Calendar&action=index&view=week&only=Consultant", $mod_strings['LNK_VIEW_CONSULTANT'],"Calendar");
if(ACLController::checkAccess('Calendar', 'list', true))$module_menu[]=Array("index.php?module=Calendar&action=index&view=week&only=Task", $mod_strings['LNK_VIEW_TASK'],"Calendar");
if(ACLController::checkAccess('Calendar', 'list', true))$module_menu[]=Array("index.php?module=Calendar&action=index&view=week&only=Call", $mod_strings['LNK_VIEW_CALL'],"Calendar");
if(ACLController::checkAccess('Calendar', 'list', true))$module_menu[]=Array("index.php?module=Calendar&action=index&view=week", $mod_strings['LNK_VIEW_ALL'],"Calendar");

if(ACLController::checkAccess('C_Classes', 'edit', true))$module_menu[]=Array("index.php?module=Calendar&action=index&view=month&only=Session&delete_Session=true", $mod_strings['LNK_SESSION'],"C_Classes");


?>
