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

 * Description:  
 ********************************************************************************/

global $mod_strings;
$module_menu = Array(
	Array("index.php?module=Reports&action=index", $mod_strings['LBL_ALL_REPORTS'],"Reports", 'Reports'),
	Array("index.php?module=CustomQueries&action=EditView&return_module=CustomQueries&return_action=DetailView", $mod_strings['LNK_NEW_CUSTOMQUERY'],"CreateCustomQuery"),
	Array("index.php?module=CustomQueries&action=index&return_module=CustomQueries&return_action=DetailView", $mod_strings['LNK_CUSTOMQUERIES'],"CustomQueries"),
	Array("index.php?module=DataSets&action=EditView&return_module=DataSets&return_action=DetailView", $mod_strings['LNK_NEW_DATASET'],"CreateDataSet"),
	Array("index.php?module=DataSets&action=index&return_module=DataSets&return_action=index", $mod_strings['LNK_LIST_DATASET'],"DataSets"),
	Array("index.php?module=ReportMaker&action=EditView&return_module=ReportMaker&return_action=DetailView", $mod_strings['LNK_NEW_REPORTMAKER'],"CreateReport"),
	Array("index.php?module=ReportMaker&action=index&return_module=ReportMaker&return_action=index", $mod_strings['LNK_LIST_REPORTMAKER'],"ReportMaker"),
	);

?>
