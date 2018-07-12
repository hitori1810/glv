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


require_once('include/controller/Controller.php');




//if for a list order change in report maker
if(!empty($_REQUEST['data_set_id']) && $_REQUEST['data_set_id']!=""){
	$_POST['data_set_id'] = $_REQUEST['data_set_id'];
}
//if for removing a data set in a report maker
if(!empty($_REQUEST['record']) && $_REQUEST['record']!=""){
	$_POST['record'] = $_REQUEST['record'];
}


//Setup variables so no errors occur
$handle_custom_layout = false;
$custom_layout = false;



$focus = new DataSet();
$controller = new Controller();

if(!empty($_POST['data_set_id'])){
	
	//if we are saving from the adddatasetform
	$focus->retrieve($_POST['data_set_id']);

	//is the direction set, if not, then this is a brand new addition to the report
	if(!empty($_REQUEST['direction'])){
		$magnitude = 1;
		$direction = $_REQUEST['direction'];

		$controller->init($focus, "Save");
		$controller->change_component_order($magnitude, $direction, $focus->report_id);
		
	} else {
	//brand new
		$controller->init($focus, "New");
		$controller->change_component_order("", "", $_REQUEST['report_id']);	
	}		
		
	
} else {

	$focus->retrieve($_POST['record']);
	
	
///////////////////////////Start Handle Disabling and Enabling the Custom Layout Tool
	if(!empty($_REQUEST['record']) && $_REQUEST['record']!=""){
	//exist
		//if we are have switched the queries we are using in this data set
		if(!empty($_REQUEST['query_id']) && $_REQUEST['query_id']!= $focus->query_id){
			$handle_custom_layout=true;
			$custom_layout=true;
			$remove_layout=true;	
		}
			
	} else {
	//new
			$handle_custom_layout=true;
			$custom_layout=true;		
	}

////////////////////////////////End Handling Disabling and Enabling Tool
	
}	

require_once('include/formbase.php');
$focus = populateFromPost('', $focus);

if(empty($_POST['data_set_id'])){

	if (!isset($_POST['exportable'])) $focus->exportable = '0';
	if (!isset($_POST['header'])) $focus->header = '0';
	if (!isset($_POST['use_prev_header'])) $focus->use_prev_header = '0';
	if (!isset($_POST['prespace_y'])) $focus->prespace_y = '0';
}

//If you are removing a data set from a report
	if(!empty($_REQUEST['rem_dataset']) && $_REQUEST['rem_dataset']=="Y"){

		//First Adjust list_order information accordingly
		$controller->init($focus, "Delete");
		$controller->delete_adjust_order($focus->report_id);

		//Now remove the report_id
		$focus->report_id = "";
	
	}

	
$focus->save();
$return_id = $focus->id;

	//Only handle it if there is a change or this is new and we need to enable
	if($handle_custom_layout==true){
		if($custom_layout==true){
			if($remove_layout==true){
			$focus->disable_custom_layout();
			}
			$focus->enable_custom_layout();
		} else {
			$focus->disable_custom_layout();
		}
	//end handle custom layout	
	}
	

	
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "DataSets";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$GLOBALS['log']->debug("Saved record with id of ".$return_id);
//echo "index.php?action=$return_action&module=$return_module&record=$return_id";
//exit;
header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>
