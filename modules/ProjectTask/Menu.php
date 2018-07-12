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




$module_menu = array();
global $mod_strings;

// Each index of module_menu must be an array of:
// the link url, display text for the link, and the icon name.

if(ACLController::checkAccess('Project', 'edit', true))$module_menu[] = array("index.php?module=Project&action=EditView&return_module=Project&return_action=DetailView",
	$mod_strings['LNK_NEW_PROJECT'], 'CreateProject');
if(ACLController::checkAccess('Project', 'list', true))$module_menu[] = array('index.php?module=Project&action=index',
	$mod_strings['LNK_PROJECT_LIST'], 'Project');
    /*
if(ACLController::checkAccess('ProjectTask', 'edit', true))$module_menu[] = array("index.php?module=ProjectTask&action=EditView&return_module=ProjectTask&return_action=DetailView",
	$mod_strings['LNK_NEW_PROJECT_TASK'], 'CreateProjectTask');
    */
if(ACLController::checkAccess('ProjectTask', 'list', true))$module_menu[] = array('index.php?module=ProjectTask&action=index',
	$mod_strings['LNK_PROJECT_TASK_LIST'], 'ProjectTask');


?>
