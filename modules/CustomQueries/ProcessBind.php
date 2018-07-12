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


require_once('modules/DataSets/DataSet_Layout.php');




$focus = new CustomQuery();

if(!empty($_REQUEST['record']) && $_REQUEST['record']!=""){
	$focus->retrieve($_REQUEST['record']);
	$temp_select = $focus->repair_column_binding();
	unset($temp_select['Remove']);
}	



foreach($_SESSION['old_column_array'] as $key => $value){
	
		//eliminate direct matches
		if(!empty($temp_select[$value])){
			unset($temp_select[$value]);
		//end eliminate direct matches
		}
		
	//check to see if this post select is available
	$temp_var = "column_".$key;
	if(!empty($_POST[$temp_var])){
		//action exists on this old column name
		//run action on this var
		if($_POST[$temp_var]=="Remove"){
		//remove layout data for this old column
		
			$focus->remove_layout($value);
		} else {
		//modify this old_column's layout data into the key column data
			$focus->modify_layout($value, $_POST[$temp_var]);

		//remove from the temp_select array
		unset($temp_select[$_POST[$temp_var]]);		
		//end ifelse remove or edit old column
		}
	//if this was an option selected.
	}		

//end foreach
}


//at this point the temp select should only have new columns that are not mapped.  These columns
//need to then be added as layout records for all data sets that use this query
//with custom_layout enable

foreach($temp_select as $key => $value){

	$focus->add_column_to_layouts($value);

}

$return_id = $focus->id;
$edit='';
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "CustomQueries";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];
if(!empty($_REQUEST['edit'])) {
	$return_id='';
	$edit='edit=true';
}


//cleanup the session variable we have been using to pass the old_column_array information
unset($_SESSION['old_column_array']);
$GLOBALS['log']->debug("Process Bind record with id of ".$return_id);

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id&$edit&old_column_array=$old_column_array");
?>
