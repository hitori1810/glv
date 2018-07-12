<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


global $mod_strings, $app_strings, $sugar_config;
$module_menu = Array();
if(ACLController::checkAccess('Opportunities','edit',true)){
    $module_menu[]=    Array("index.php?module=Opportunities&action=EditView&return_module=Opportunities&return_action=DetailView&enroll=Student", $mod_strings['LNK_NEW_OPPORTUNITY'],"CreateOpportunities");
}
if(ACLController::checkAccess('Opportunities','list',true)){
    $module_menu[]=    Array("index.php?module=Opportunities&action=index&return_module=Opportunities&return_action=DetailView", $mod_strings['LNK_OPPORTUNITY_LIST'],"Opportunities");
}
if(empty($sugar_config['disc_client'])){
    if(ACLController::checkAccess('Opportunities','view',true)){
        $module_menu[]=    Array("index.php?module=Reports&action=index&query=true&report_module=Opportunities", $mod_strings['LNK_OPPORTUNITY_REPORTS'],"OpportunityReports", 'Opportunities');
    }
}
  
?>