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




global $current_user;
global $mod_strings, $app_strings;
$module_menu = array();

// Each index of module_menu must be an array of:
// the link url, display text for the link, and the icon name.

// Create Project
if(ACLController::checkAccess('Project', 'edit', true)) {
    $module_menu[] = array(
        'index.php?module=Project&action=EditView&return_module=Project&return_action=DetailView',
        isset($mod_strings['LNK_NEW_PROJECT']) ? $mod_strings['LNK_NEW_PROJECT'] : '',
        'CreateProject'
    );
}

// Create Project Template
if(ACLController::checkAccess('Project', 'edit', true)) {
    $module_menu[] = array(
        'index.php?module=Project&action=ProjectTemplatesEditView&return_module=Project&return_action=ProjectTemplatesDetailView',
        isset($mod_strings['LNK_NEW_PROJECT_TEMPLATES']) ? $mod_strings['LNK_NEW_PROJECT_TEMPLATES'] : '',
        'CreateProjectTemplate'
    );
}
	
// Project List
if(ACLController::checkAccess('Project', 'list', true)) {
    $module_menu[] = array(
        'index.php?module=Project&action=index',
        isset($mod_strings['LNK_PROJECT_LIST']) ? $mod_strings['LNK_PROJECT_LIST'] : '',
        'Project'
    );
}
	
// Project Templates
if(ACLController::checkAccess('Project', 'list', true)) {
    $module_menu[] = array(
        'index.php?module=Project&action=ProjectTemplatesListView',
        isset($mod_strings['LNK_PROJECT_TEMPLATES_LIST']) ? $mod_strings['LNK_PROJECT_TEMPLATES_LIST'] : '',
        'ProjectTemplate'
    );
}
	
// Project Tasks
if(ACLController::checkAccess('ProjectTask', 'list', true)) {
    $module_menu[] = array(
        'index.php?module=ProjectTask&action=index',
        isset($mod_strings['LNK_PROJECT_TASK_LIST']) ? $mod_strings['LNK_PROJECT_TASK_LIST'] : '',
        'ProjectTask'
    );
}
	
if(ACLController::checkAccess('Project', 'list', true)) {
    $module_menu[] = array(
        "index.php?module=Project&action=Dashboard&return_module=Project&return_action=DetailView",
        isset($mod_strings['LNK_PROJECT_DASHBOARD']) ? $mod_strings['LNK_PROJECT_DASHBOARD'] : '',
        'Project'
    );
}

?>
