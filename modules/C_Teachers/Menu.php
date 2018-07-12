<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings, $app_strings, $sugar_config;

$module_menu = Array();
if(ACLController::checkAccess('C_Teachers', 'edit', true)){
$module_menu[]=Array("index.php?module=C_Teachers&action=EditView&return_module=C_Teachers&return_action=DetailView&teacher_type=Teacher", $mod_strings['LNK_NEW_RECORD'],"CreateC_Teachers", 'C_Teachers');
//$module_menu[]=Array("index.php?module=C_Teachers&action=EditView&return_module=C_Teachers&return_action=DetailView&teacher_type=TA", $mod_strings['LNK_NEW_RECORD_TA'],"CreateC_Teachers", 'C_Teachers');
}


if(ACLController::checkAccess('C_Teachers', 'list', true))$module_menu[]=Array("index.php?module=C_Teachers&action=index&return_module=C_Teachers&return_action=DetailView", $mod_strings['LNK_LIST'],"C_Teachers", 'C_Teachers');