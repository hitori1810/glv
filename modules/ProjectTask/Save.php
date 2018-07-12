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





$project = new ProjectTask();
if(!empty($_POST['record']))
{
	$project->retrieve($_POST['record']);
}
////
//// save the fields to the ProjectTask object
////

if(isset($_REQUEST['email_id'])) $project->email_id = $_REQUEST['email_id'];

require_once('include/formbase.php');
$project = populateFromPost('', $project);
if(!isset($_REQUEST['milestone_flag']))
{
	$project->milestone_flag = '0';
}


$GLOBALS['check_notify'] = false;
if (!empty($_POST['assigned_user_id']) && ($project->assigned_user_id != $_POST['assigned_user_id']) && ($_POST['assigned_user_id'] != $current_user->id)) {
	$GLOBALS['check_notify'] = true;
}

	if(!$project->ACLAccess('Save')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
	}

if( empty($project->project_id) ) $project->project_id = $_POST['relate_id']; //quick for 5.1 till projects are revamped for 5.5 nsingh- 7/3/08
$project->save($GLOBALS['check_notify']);

if(isset($_REQUEST['form']))
{
	// we are doing the save from a popup window
	echo '<script>opener.window.location.reload();self.close();</script>';
	die();
}
else
{
	// need to refresh the page properly

	$return_module = empty($_REQUEST['return_module']) ? 'ProjectTask'
		: $_REQUEST['return_module'];

	$return_action = empty($_REQUEST['return_action']) ? 'index'
		: $_REQUEST['return_action'];

	$return_id = empty($_REQUEST['return_id']) ? $project->id
		: $_REQUEST['return_id'];
		
	//if this navigation is going to list view, do not show the bean id, it will populate the mass update.
	if($return_action == 'index') {
		$return_id ='';
	}		
header("Location: index.php?module=$return_module&action=$return_action&record=$return_id");

}
?>
