<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


global $mod_strings, $app_strings, $sugar_config;
// Get lisence info
$lisence = getLisenceOnlineCRM();   

if(ACLController::checkAccess('Meetings', 'edit', true))
    $module_menu[]=Array("index.php?module=Meetings&action=EditView&return_module=Meetings&return_action=DetailView&type=Meeting", $mod_strings['LNK_NEW_MEETING'],"CreateMeetings", "Meetings");

if(ACLController::checkAccess('Meetings', 'edit', true) && $lisence['version'] != "Free")
    $module_menu[]=Array("index.php?module=Meetings&action=EditView&return_module=Meetings&return_action=DetailView&type=PT", $mod_strings['LNK_NEW_TESTING'],"CreateMeetings", "Meetings");

if(ACLController::checkAccess('Meetings', 'list', true))
    $module_menu[]=Array("index.php?module=Meetings&action=index&return_module=Meetings&return_action=DetailView&type_list=Meeting&meeting_type_advanced=Meeting", $mod_strings['LNK_MEETING_LIST'],"Meetings");

?>
