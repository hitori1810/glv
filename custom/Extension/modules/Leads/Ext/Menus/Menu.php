<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 


    global $mod_strings, $app_strings, $sugar_config;
    $module_menu = Array();
    if(ACLController::checkAccess('Leads', 'edit', true)){
        $module_menu[]=Array("index.php?module=Leads&action=EditView&return_module=Leads&return_action=DetailView", $mod_strings['LNK_NEW_LEAD'],"CreateLeads", 'Leads');
    }
    if(ACLController::checkAccess('Leads', 'list', true))$module_menu[]=Array("index.php?module=Leads&action=index&return_module=Leads&return_action=DetailView", $mod_strings['LNK_LEAD_LIST'],"Leads", 'Leads');
    if(ACLController::checkAccess('Leads', 'import', true))$module_menu[]=Array("index.php?module=Import&action=Step1&import_module=Leads&return_module=Leads&return_action=index", $mod_strings['LNK_IMPORT_LEADS'],"Import", 'Leads');

  /*  if(ACLController::checkAccess('Leads', 'edit', true)){
        $module_menu[]=Array("index.php?module=Meetings&action=EditView&return_module=Meetings&return_action=DetailView&type=PT", $mod_strings['LNK_NEW_PT'],"CreateMeetings");
        $module_menu[]=Array("index.php?module=Meetings&action=EditView&return_module=Meetings&return_action=DetailView&type=Demo", $mod_strings['LNK_NEW_DEMO'],"CreateMeetings");       

    } */

?>
