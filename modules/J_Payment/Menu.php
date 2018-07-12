<?php
    //create by Bui Kim Tung
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    global $mod_strings;

    if(ACLController::checkAccess('J_Payment', 'edit', true) && $GLOBALS['current_user']->team_type == 'Junior')
        $module_menu[] = Array("index.php?module=J_Payment&action=EditView&return_module=J_Payment&return_action=DetailView&payment_type=Enrollment", $mod_strings['LBL_CREATE_ENROLLMENT']);
                                                                                                                                                                                    
    if(ACLController::checkAccess('J_Payment', 'edit', true))
        $module_menu[]=Array("index.php?module=J_Payment&action=EditView&return_module=J_Payment&return_action=DetailView&payment_type=Cashholder", $mod_strings['LNK_CREATE_CASHHOLDER']);
    if(ACLController::checkAccess('J_Payment', 'edit', true))
        $module_menu[]=Array("index.php?module=J_Payment&action=EditView&return_module=J_Payment&return_action=DetailView&payment_type=Deposit", $mod_strings['LNK_NEW_RECORD']);
    //if(ACLController::checkAccess('J_Payment', 'edit', true))
//        $module_menu[]=Array("index.php?module=J_Payment&action=EditView&return_module=J_Payment&return_action=DetailView&payment_type=Cashholder", $mod_strings['LNK_NEW_RECORD']);
//        
    if(ACLController::checkAccess('J_Payment', 'list', true))
        $module_menu[]=Array("index.php?module=J_Payment&action=index", $mod_strings['LBL_LIST']);


?>
