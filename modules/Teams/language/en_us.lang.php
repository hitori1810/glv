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

/*********************************************************************************

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$mod_strings = array (
	'ERR_ADD_RECORD' => 'You must specify a record number to add a user to this team.',
    'ERR_DUP_NAME' => 'Team Name already existed, please choose another one.',
	'ERR_DELETE_RECORD' => 'You must specify a record number to delete this team.',
    'ERR_INVALID_TEAM_REASSIGNMENT' => 'Error.  The selected team <b>({0})</b> is a team you have chosen to delete.  Please select another team.',
	'ERR_CANNOT_REMOVE_PRIVATE_TEAM' => 'Error.  You may not delete a user whose private team has not been deleted.',

	'LBL_DESCRIPTION' => 'Description:',
	'LBL_GLOBAL_TEAM_DESC' => 'Globally Visible',
	'LBL_INVITEE' => 'Team Members',
	'LBL_LIST_DEPARTMENT' => 'Department',
	'LBL_LIST_DESCRIPTION' => 'Description',
	'LBL_LIST_FORM_TITLE' => 'Team List',
	'LBL_LIST_NAME' => 'Name',
    'LBL_FIRST_NAME' => 'First Name:',
    'LBL_LAST_NAME' => 'Last Name:',
	'LBL_LIST_REPORTS_TO' => 'Reports To',
	'LBL_LIST_TITLE' => 'Title',
	'LBL_MODULE_NAME' => 'Teams',
	'LBL_MODULE_NAME_SINGULAR' => 'Team',
	'LBL_MODULE_TITLE' => 'Teams: Home',
	'LBL_NAME' => 'Team Name:',
    'LBL_NAME_2' => 'Team Name(2):',
    'LBL_PRIMARY_TEAM_NAME' => 'Center',
	'LBL_NEW_FORM_TITLE' => 'New Team',
	'LBL_PRIVATE' => 'Private',
	'LBL_PRIVATE_TEAM_FOR' => 'Private team for: ',
	'LBL_SEARCH_FORM_TITLE' => 'Team Search',
	'LBL_TEAM_MEMBERS' => 'Team Members',
	'LBL_TEAM' => 'Center:',
	'LBL_USERS_SUBPANEL_TITLE' => 'User',
	'LBL_USERS'=>'User',

    'LBL_REASSIGN_TEAM_TITLE' => 'There are records assigned to the following team(s): <b>{0}</b><br>Before deleting the team(s), you must first reassign these records to a new team.  Select a team to be used as the replacement.',
    'LBL_REASSIGN_TEAM_BUTTON_KEY' => 'R',
    'LBL_REASSIGN_TEAM_BUTTON_LABEL' => 'Reassign',
    'LBL_REASSIGN_TEAM_BUTTON_TITLE' => 'Reassign',
    'LBL_CONFIRM_REASSIGN_TEAM_LABEL' => 'Proceed to update the affected records to use the new team?',
    'LBL_REASSIGN_TABLE_INFO' => 'Updating table {0}',
    'LBL_REASSIGN_TEAM_COMPLETED' => 'Operation has completed successfully.',

    'LNK_LIST_TEAM' => 'Teams',
	'LNK_LIST_TEAMNOTICE' => 'Team Notices',
    'LNK_NEW_TEAM' => 'Create Team',
    'LBL_PREFIX' => 'Team Code',
    'LBL_EC_EMAIL' => 'Email Group Sale',
    'LBL_EFL_EMAIL' => 'Email Group EFL',
    'LBL_CM_EMAIL' => 'Email CM',

    'NTC_DELETE_CONFIRMATION' => 'Are you sure you want to delete this record?',
	'NTC_REMOVE_TEAM_MEMBER_CONFIRMATION' => 'Are you sure you want to remove this user\\\'s membership?',
	'LBL_EDITLAYOUT' => 'Edit Layout' /*for 508 compliance fix*/,


);


?>
