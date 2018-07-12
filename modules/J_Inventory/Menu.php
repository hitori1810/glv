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
if(ACLController::checkAccess('J_Inventory','edit',true)){
    $module_menu[]=    Array("index.php?module=J_Inventory&action=EditView&return_module=J_Inventory&return_action=DetailView&type=Import", $mod_strings['LBL_IMPORT'],"CreateJ_Inventory");
    $module_menu[]=    Array("index.php?module=J_Inventory&action=EditView&return_module=J_Inventory&return_action=DetailView&type=Tranfer", $mod_strings['LBL_TRANFER'],"CreateJ_Inventory");
    $module_menu[]=    Array("index.php?module=J_Inventory&action=EditView&return_module=J_Inventory&return_action=DetailView&type=Sale", $mod_strings['LBL_SALE'],"CreateJ_Inventory");
}
if(ACLController::checkAccess('J_Inventory', 'list', true))$module_menu[]=Array("index.php?module=J_Inventory&action=index&return_module=J_Inventory&return_action=DetailView", $mod_strings['LNK_LIST'],"J_Inventory", 'J_Inventory');