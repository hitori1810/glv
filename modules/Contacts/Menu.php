<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


global $mod_strings, $app_strings, $sugar_config;
if(ACLController::checkAccess('Contacts', 'edit', true))$module_menu[] = Array("index.php?module=Contacts&action=EditView&return_module=Contacts&return_action=index", $mod_strings['LNK_NEW_CONTACT'],"CreateContacts", 'Contacts');

if(ACLController::checkAccess('Contacts', 'list', true))$module_menu[] =Array("index.php?module=Contacts&action=index&return_module=Contacts&return_action=DetailView", $mod_strings['LNK_CONTACT_LIST'],"Contacts", 'Contacts');

if(ACLController::checkAccess('Contacts', 'import', true))$module_menu[]=Array("index.php?module=Import&action=Step1&import_module=Contacts&return_module=Leads&return_action=index", $mod_strings['LNK_IMPORT_CONTACTS'],"Import", 'Contacts');
//
//if(ACLController::checkAccess('J_Feedback', 'edit', true))$module_menu[]=Array("index.php?module=J_Feedback&action=EditView&return_module=J_Feedback&return_action=DetailView", $mod_strings['LNK_NEW_FEEDBACK'],"CreateFeedback");
//
//if(ACLController::checkAccess('J_StudentSituations', 'edit', true) && ($GLOBALS['current_user']->team_type == 'Junior'))
//    $module_menu[] = Array("index.php?module=J_StudentSituations&action=EditView&return_module=J_StudentSituations&return_action=DetailView&type=Moving%20Out", $mod_strings['LNK_NEW_MOVING_CLASS']);
//
//if(ACLController::checkAccess('J_Payment', 'edit', true))
//    $module_menu[]=Array("index.php?module=J_Payment&action=EditView&return_module=J_Payment&return_action=DetailView&payment_type=Moving%20Out", $mod_strings['LNK_NEW_MOVING']);
//
//if(ACLController::checkAccess('J_Payment', 'edit', true))
//    $module_menu[]=Array("index.php?module=J_Payment&action=EditView&return_module=J_Payment&return_action=DetailView&payment_type=Transfer%20Out", $mod_strings['LNK_NEW_TRANSFER']);
//

$module_menu[]=Array("index.php?module=Contacts&action=sendSMS", $mod_strings['LBL_SEND_SMS_TITLE']);
//$module_menu[]=Array("index.php?module=Contacts&action=viewduplicate", $mod_strings['LBL_VIEW_DUPLICATE']);

?>