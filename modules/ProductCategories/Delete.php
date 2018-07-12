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



$focus = new ProductCategory();

if(!isset($_REQUEST['record']))
	sugar_die($mod_strings['ERR_DELETE_RECORD']);

$focus->mark_deleted($_REQUEST['record']);

//$focus->clear_branch($_REQUEST['record']);
//
//if($_REQUEST['delete_type']=="graft") $focus->graft($_REQUEST['record'], $_REQUEST['parent_id'], $_REQUEST['parent_node_id']);
//
//if($_REQUEST['delete_type']=="prune"){
//	
//	$focus->prune($_REQUEST['record']);
//
//	$TreeView = new TreeView($focus);
//	$TreeView->regrow_tree("Y");
//}
 
header("Location: index.php?module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']);
?>