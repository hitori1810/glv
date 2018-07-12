<?php
//create by leduytan 22/7/2014 - Insert menu into Class
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    global $mod_strings, $app_strings, $sugar_config;
    
    //if(ACLController::checkAccess('C_Attendance', 'edit', true))$module_menu[]=Array("index.php?module=C_Attendance&action=EditView&return_module=C_Attendance&return_action=index", $mod_strings['LNK_NEW_RECORD']);
//
//    if(ACLController::checkAccess('C_Attendance', 'list', true))$module_menu[]=Array("index.php?module=C_Attendance&action=index&return_module=C_Attendance&return_action=DetailView", $mod_strings['LNK_LIST']);
//    if(empty($sugar_config['disc_client'])){
//        if(ACLController::checkAccess('C_Attendance', 'list', true))$module_menu[]=Array("index.php?module=Reports&action=index&query=true&report_module=C_Attendance", $mod_strings['LNK_ATTENDANCE_REPORTS']);
//    }
    
    if(ACLController::checkAccess('C_Attendance', 'import', true))$module_menu[] = Array("index.php?module=C_Attendance&action=index", $mod_strings['LNK_ATTENDANCE_CHECK']);
    if(ACLController::checkAccess('C_Attendance', 'import', true))$module_menu[] = Array("index.php?module=C_Attendance&action=attendanceMonitor&sugar_body_only=true", $mod_strings['LNK_ATTENDANCE_CHECK_MONITOR']);
    
?>
