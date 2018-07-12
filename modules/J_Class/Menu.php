<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
* By installing or using this file, you are confirming on behalf of the entity
* subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
* the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
* http://www.sugarcrm.com/master-subscription-agreement
*
* If Company is not bound by the MSA, then by installing or using this file
* you are agreeing unconditionally that Company will be bound by the MSA and
* certifying that you have authority to bind Company accordingly.
*
* Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
********************************************************************************/


global $mod_strings, $app_strings, $sugar_config;

$module_menu = Array();
if(ACLController::checkAccess('J_Class','edit',true)){
    $module_menu[]=    Array("index.php?module=J_Class&action=EditView&return_module=J_Class&return_action=DetailView&class_type=Normal%20Class", $mod_strings['LNK_NEW_RECORD']);
    $module_menu[]=    Array("index.php?module=J_Class&action=EditView&return_module=J_Class&return_action=DetailView&class_type=Waiting%20Class", $mod_strings['LNK_NEW_WAITING']);

}
if(ACLController::checkAccess('J_Class', 'list', true))$module_menu[]=Array("index.php?module=J_Class&action=index&return_module=J_Class&return_action=DetailView", $mod_strings['LNK_LIST'],"J_Class", 'J_Class');