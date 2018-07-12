<?php

include_once("include/workflow/alert_utils.php");
include_once("include/workflow/action_utils.php");
include_once("include/workflow/time_utils.php");
include_once("include/workflow/trigger_utils.php");
//BEGIN WFLOW PLUGINS
include_once("include/workflow/custom_utils.php");
//END WFLOW PLUGINS
	class J_Feedback_workflow {
	function process_wflow_triggers(& $focus){
		include("custom/modules/J_Feedback/workflow/triggers_array.php");
		include("custom/modules/J_Feedback/workflow/alerts_array.php");
		include("custom/modules/J_Feedback/workflow/actions_array.php");
		include("custom/modules/J_Feedback/workflow/plugins_array.php");
		if(empty($focus->fetched_row['id']) || (!empty($_SESSION["workflow_cron"]) && $_SESSION["workflow_cron"]=="Yes" && !empty($_SESSION["workflow_id_cron"]) && $_SESSION["workflow_id_cron"]=="aa4c6421-e60f-369c-a992-57be7d069bce")){ 
 
 if((isset($focus->type_feedback_list) && $focus->type_feedback_list ==  'Customer')){ 
 

	 //Frame Secondary 

	 $secondary_array = array(); 
	 //Secondary Triggers 

	global $triggeredWorkflows;
	if (!isset($triggeredWorkflows['75fb61ef_5644_3d84_a678_586f114449c6'])){
		$triggeredWorkflows['75fb61ef_5644_3d84_a678_586f114449c6'] = true;
		 $alertshell_array = array(); 

	 $alertshell_array['alert_msg'] = "cef83702-e3f4-356b-a362-57be7d987f09"; 

	 $alertshell_array['source_type'] = "Custom Template"; 

	 $alertshell_array['alert_type'] = "Email"; 

	 process_workflow_alerts($focus, $alert_meta_array['J_Feedback0_alert0'], $alertshell_array, false); 
 	 unset($alertshell_array); 
		}
 

	 //End Frame Secondary 

	 unset($secondary_array); 
 

 //End if trigger is true 
 } 

		 //End if new, update, or all record
 		} 

if(isset($focus->fetched_row['id']) && $focus->fetched_row['id']!=""){ 
 
 if((isset($focus->type_feedback_list) && $focus->type_feedback_list ==  'Customer')){ 
 

	 //Frame Secondary 

	 $secondary_array = array(); 
	 //Secondary Triggers 

	global $triggeredWorkflows;
	if (!isset($triggeredWorkflows['82993c89_b102_7669_e741_586f112ef7a3'])){
		$triggeredWorkflows['82993c89_b102_7669_e741_586f112ef7a3'] = true;
		 $alertshell_array = array(); 

	 $alertshell_array['alert_msg'] = "a98d90a0-ac8a-a954-bf97-57c6aab9970f"; 

	 $alertshell_array['source_type'] = "Custom Template"; 

	 $alertshell_array['alert_type'] = "Email"; 

	 process_workflow_alerts($focus, $alert_meta_array['J_Feedback1_alert0'], $alertshell_array, false); 
 	 unset($alertshell_array); 
		}
 

	 //End Frame Secondary 

	 unset($secondary_array); 
 

 //End if trigger is true 
 } 

		 //End if new, update, or all record
 		} 


 if((isset($focus->type_feedback_list) && $focus->type_feedback_list !=  'Customer')){ 
 

	 //Frame Secondary 

	 $secondary_array = array(); 
	 //Secondary Triggers 

	global $triggeredWorkflows;
	if (!isset($triggeredWorkflows['e7884edc_bff7_d343_dbb5_586f11bf4879'])){
		$triggeredWorkflows['e7884edc_bff7_d343_dbb5_586f11bf4879'] = true;
		 $alertshell_array = array(); 

	 $alertshell_array['alert_msg'] = "445698ab-e591-52ec-b677-58083345c0ac"; 

	 $alertshell_array['source_type'] = "Custom Template"; 

	 $alertshell_array['alert_type'] = "Email"; 

	 process_workflow_alerts($focus, $alert_meta_array['J_Feedback2_alert0'], $alertshell_array, false); 
 	 unset($alertshell_array); 
		}
 

	 //End Frame Secondary 

	 unset($secondary_array); 
 

 //End if trigger is true 
 } 


	//end function process_wflow_triggers
	}

	//end class
	}

?>