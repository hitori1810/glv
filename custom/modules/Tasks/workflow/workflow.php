<?php

include_once("include/workflow/alert_utils.php");
include_once("include/workflow/action_utils.php");
include_once("include/workflow/time_utils.php");
include_once("include/workflow/trigger_utils.php");
//BEGIN WFLOW PLUGINS
include_once("include/workflow/custom_utils.php");
//END WFLOW PLUGINS
	class Tasks_workflow {
	function process_wflow_triggers(& $focus){
		include("custom/modules/Tasks/workflow/triggers_array.php");
		include("custom/modules/Tasks/workflow/alerts_array.php");
		include("custom/modules/Tasks/workflow/actions_array.php");
		include("custom/modules/Tasks/workflow/plugins_array.php");
		
 if(true){ 
 

	 //Frame Secondary 

	 $secondary_array = array(); 
	 //Secondary Triggers 

	global $triggeredWorkflows;
	if (!isset($triggeredWorkflows['33bb7c47_5f2e_e62c_ba27_586f119b5a11'])){
		$triggeredWorkflows['33bb7c47_5f2e_e62c_ba27_586f119b5a11'] = true;
		 $alertshell_array = array(); 

	 $alertshell_array['alert_msg'] = "d612d3dc-1ef5-886e-a127-57c4ea718e90"; 

	 $alertshell_array['source_type'] = "Custom Template"; 

	 $alertshell_array['alert_type'] = "Email"; 

	 process_workflow_alerts($focus, $alert_meta_array['Tasks0_alert0'], $alertshell_array, false); 
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