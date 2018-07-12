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


_pp($_REQUEST);
$focus = new ProductCategory();


	$focus->retrieve($_REQUEST['record']);

	foreach($focus->column_fields as $field)
	{
		if(isset($_REQUEST[$field]))
		{
			$focus->$field = $_REQUEST[$field];
			
		}
	}
	
	foreach($focus->additional_column_fields as $field)
	{
		if(isset($_REQUEST[$field]))
		{
			$value = $_REQUEST[$field];
			$focus->$field = $value;
			
		}
	}

$focus->save();
$return_id = $focus->id;

$edit='';
$return_module = (isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") ? $_REQUEST['return_module'] : "ProductCategories";
$return_action = (isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") ? $_REQUEST['return_action'] : "DetailView";

if (isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") {
	$return_id = $_REQUEST['return_id'];
}
if (!empty($_REQUEST['edit'])) {
	$return_id='';
	$edit='&edit=true';
}
if (!empty($_REQUEST['isDuplicate']) && ($_REQUEST['isDuplicate'] == 'true')) {
	$return_id='';
}
$GLOBALS['log']->debug("Saved record with id of ".$return_id);

header("Location: index.php?action=$return_action&module=$return_module&record={$return_id}{$edit}");
?>