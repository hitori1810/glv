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






require_once('include/formbase.php');

global $current_user;

$sugarbean = new Project();
$sugarbean = populateFromPost('', $sugarbean);

$projectTasks = array();
if (isset($_REQUEST['duplicateSave']) && $_REQUEST['duplicateSave'] === "true"){
    $base_project_id = $_REQUEST['relate_id'];
}
else{
    $base_project_id = $sugarbean->id;
}
if(isset($_REQUEST['save_type']) || isset($_REQUEST['duplicateSave']) && $_REQUEST['duplicateSave'] === "true") {
    $query = "SELECT id FROM project_task WHERE project_id = '" . $base_project_id . "' AND deleted = 0";
    $result = $sugarbean->db->query($query,true,"Error retrieving project tasks");
    $row = $sugarbean->db->fetchByAssoc($result);

    while ($row != null){
        $projectTaskBean = new ProjectTask();
        $projectTaskBean->id = $row['id'];
        $projectTaskBean->retrieve();
        $projectTaskBean->date_entered = '';
        $projectTaskBean->date_modified = '';
        array_push($projectTasks, $projectTaskBean);
        $row = $sugarbean->db->fetchByAssoc($result);
    }
}
if (isset($_REQUEST['save_type'])){
    $sugarbean->id = '';
    $sugarbean->assigned_user_id = $current_user->id;

    if ($_REQUEST['save_type'] == 'TemplateToProject'){
        $sugarbean->name = $_REQUEST['project_name'];
        $sugarbean->is_template = 0;
    }
    else if ($_REQUEST['save_type'] == 'ProjectToTemplate'){
        $sugarbean->name = $_REQUEST['template_name'];
        $sugarbean->is_template = true;
    }
}
else{
    if (isset($_REQUEST['is_template']) && $_REQUEST['is_template'] == '1'){
        $sugarbean->is_template = true;
    }
    else{
        $sugarbean->is_template = 0;
    }
}

if(isset($_REQUEST['email_id'])) $sugarbean->email_id = $_REQUEST['email_id'];

if(!$sugarbean->ACLAccess('Save')){
        ACLController::displayNoAccess(true);
        sugar_cleanup(true);
}

if (isset($GLOBALS['check_notify'])) {
    $check_notify = $GLOBALS['check_notify'];
}
else {
    $check_notify = FALSE;
}
$sugarbean->save($check_notify);
$return_id = $sugarbean->id;

if(isset($_REQUEST['save_type']) || isset($_REQUEST['duplicateSave']) && $_REQUEST['duplicateSave'] === "true") {
    for ($i = 0; $i < count($projectTasks); $i++){
        if (isset($_REQUEST['save_type']) || (isset($_REQUEST['duplicateSave']) && $_REQUEST['duplicateSave'] === "true")){
            $projectTasks[$i]->id = '';
            $projectTasks[$i]->project_id = $sugarbean->id;
        }
        if ($sugarbean->is_template){
            $projectTasks[$i]->assigned_user_id = '';
        }
        $projectTasks[$i]->team_id = $sugarbean->team_id;
        if(empty( $projectTasks[$i]->duration_unit)) $projectTasks[$i]->duration_unit = " "; //Since duration_unit cannot be null.
        $projectTasks[$i]->save(false);
    }
}

if ($sugarbean->is_template){
    header("Location: index.php?action=ProjectTemplatesDetailView&module=Project&record=$return_id&return_module=Project&return_action=ProjectTemplatesEditView");
}
else{
    handleRedirect($return_id,'Project');
}
?>