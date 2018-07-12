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

if(ACLController::checkAccess('J_Kindofcourse', 'edit', true))$module_menu[]=Array("index.php?module=J_Kindofcourse&action=EditView&return_module=J_Kindofcourse&return_action=DetailView", $mod_strings['LNK_NEW_RECORD'],"CreateJ_Kindofcourse", 'J_Kindofcourse');
if(ACLController::checkAccess('J_Kindofcourse', 'list', true))$module_menu[]=Array("index.php?module=J_Kindofcourse&action=index&return_module=J_Kindofcourse&return_action=DetailView", $mod_strings['LNK_LIST'],"J_Kindofcourse", 'J_Kindofcourse');
if(ACLController::checkAccess('J_Kindofcourse', 'import', true))$module_menu[]=Array("index.php?module=J_Kindofcourse&action=configcategory&return_module=J_Kindofcourse&return_action=index", $mod_strings['LNK_CONFIG_CATEGORY'],"Config", 'J_Kindofcourse');