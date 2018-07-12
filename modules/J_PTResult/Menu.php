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
if(ACLController::checkAccess('J_PTResult', 'list', true)){
    
$module_menu[]=Array("index.php?module=J_PTResult&action=index&return_module=J_PTResult&return_action=DetailView&type_list=PT", $mod_strings['LNK_PT_LIST'],"J_PTResult");

$module_menu[]=Array("index.php?module=J_PTResult&action=index&return_module=J_PTResult&return_action=DetailView&type_list=Demo", $mod_strings['LNK_DEMO_LIST'],"J_PTResult");

}