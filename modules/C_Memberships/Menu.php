<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 


global $mod_strings, $app_strings, $sugar_config;
	if(ACLController::checkAccess('C_Memberships', 'edit', true))$module_menu[] = Array("index.php?module=C_Memberships&action=EditView&return_module=C_Memberships&return_action=DetailView", $mod_strings['LNK_NEW_RECORD'],"CreateC_Memberships", 'C_Memberships');

	if(ACLController::checkAccess('C_Memberships', 'list', true))$module_menu[] =Array("index.php?module=C_Memberships&action=index&return_module=C_Memberships&return_action=DetailView", $mod_strings['LNK_LIST'],"C_Memberships", 'C_Memberships');
    
	if(ACLController::checkAccess('C_Memberships', 'import', true))$module_menu[] =Array("index.php?module=Import&action=Step1&import_module=C_Memberships&return_module=C_Memberships&return_action=index", $mod_strings['LNK_IMPORT_C_MEMBERSHIPS'],"Import", 'C_Memberships');

?>