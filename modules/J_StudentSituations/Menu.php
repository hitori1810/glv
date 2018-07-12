<?php
    //create by Bui Kim Tung
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    global $mod_strings;

    if(ACLController::checkAccess('J_StudentSituations', 'edit', true) && ($GLOBALS['current_user']->team_type == 'Junior'))
        $module_menu[] = Array("index.php?module=J_StudentSituations&action=EditView&return_module=J_StudentSituations&return_action=DetailView&type=Moving%20Out", $mod_strings['LNK_MOVING_CLASS']);
        
    if(ACLController::checkAccess('J_StudentSituations', 'list', true))
        $module_menu[]=Array("index.php?module=J_StudentSituations&action=index", $mod_strings['LNK_LIST']);


?>
