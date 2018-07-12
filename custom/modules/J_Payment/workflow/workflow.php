<?php

include_once("include/workflow/alert_utils.php");
include_once("include/workflow/action_utils.php");
include_once("include/workflow/time_utils.php");
include_once("include/workflow/trigger_utils.php");
//BEGIN WFLOW PLUGINS
include_once("include/workflow/custom_utils.php");
//END WFLOW PLUGINS
	class J_Payment_workflow {
	function process_wflow_triggers(& $focus){
		include("custom/modules/J_Payment/workflow/triggers_array.php");
		include("custom/modules/J_Payment/workflow/alerts_array.php");
		include("custom/modules/J_Payment/workflow/actions_array.php");
		include("custom/modules/J_Payment/workflow/plugins_array.php");
		
 if((isset($focus->payment_type) && $focus->payment_type ==  'Refund')){ 
 

	 //Frame Secondary 

	 $secondary_array = array(); 
	 //Secondary Triggers 
	 //Secondary Trigger number #1
	 if((isset($focus->payment_type) && $focus->payment_type ==  'Transfer In')	 ){ 
	 

	 //Secondary Trigger number #2
	 if((isset($focus->payment_type) && $focus->payment_type ==  'Transfer Out')	 ){ 
	 


	global $triggeredWorkflows;
	if (!isset($triggeredWorkflows['464188b1_4cc1_7f8a_a0f2_586f110e2ed1'])){
		$triggeredWorkflows['464188b1_4cc1_7f8a_a0f2_586f110e2ed1'] = true;
		 $alertshell_array = array(); 

	 $alertshell_array['alert_msg'] = "3e5e0fb6-5e7f-15a3-6d50-57c504c8e296"; 

	 $alertshell_array['source_type'] = "Custom Template"; 

	 $alertshell_array['alert_type'] = "Email"; 

	 process_workflow_alerts($focus, $alert_meta_array['J_Payment0_alert0'], $alertshell_array, false); 
 	 unset($alertshell_array); 
		}
 

	 //End Frame Secondary 

	 // End Secondary Trigger number #1
 	 } 

	 // End Secondary Trigger number #2
 	 } 

	 unset($secondary_array); 
 

 //End if trigger is true 
 } 


 if((isset($focus->payment_type) && $focus->payment_type ==  'Moving In')){ 
 

	 //Frame Secondary 

	 $secondary_array = array(); 
	 //Secondary Triggers 
	 //Secondary Trigger number #1
	 if((isset($focus->payment_type) && $focus->payment_type ==  'Moving Out')	 ){ 
	 


	global $triggeredWorkflows;
	if (!isset($triggeredWorkflows['6a4e8286_e7c2_0704_3aa9_586f11751fa3'])){
		$triggeredWorkflows['6a4e8286_e7c2_0704_3aa9_586f11751fa3'] = true;
		 $alertshell_array = array(); 

	 $alertshell_array['alert_msg'] = "598ff19f-75de-246b-f6bc-57c50a169973"; 

	 $alertshell_array['source_type'] = "Custom Template"; 

	 $alertshell_array['alert_type'] = "Email"; 

	 process_workflow_alerts($focus, $alert_meta_array['J_Payment1_alert0'], $alertshell_array, false); 
 	 unset($alertshell_array); 
		}
 

	 //End Frame Secondary 

	 // End Secondary Trigger number #1
 	 } 

	 unset($secondary_array); 
 

 //End if trigger is true 
 } 


	//end function process_wflow_triggers
	}

	//end class
	}

?>