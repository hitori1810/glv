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

 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings,$app_strings;
if(ACLController::checkAccess('Bugs', 'edit', true))
$module_menu [] =	Array("index.php?module=Bugs&action=EditView&return_module=Bugs&return_action=DetailView", $mod_strings['LNK_NEW_BUG'],"CreateBugs", 'Bugs');
if(ACLController::checkAccess('Bugs', 'list', true))
$module_menu [] =		Array("index.php?module=Bugs&action=index&return_module=Bugs&return_action=DetailView", $mod_strings['LNK_BUG_LIST'],"Bugs", 'Bugs');
if(ACLController::checkAccess('Bugs', 'list', true))$module_menu[] =Array("index.php?module=Reports&action=index&query=true&report_module=Bugs", $mod_strings['LNK_BUG_REPORTS'],"BugReports", 'Bugs');
if(ACLController::checkAccess('Bugs', 'import', true))$module_menu[] =Array("index.php?module=Import&action=Step1&import_module=Bugs&return_module=Bugs&return_action=index", $mod_strings['LNK_IMPORT_BUGS'],"Import", 'Bugs');

?>