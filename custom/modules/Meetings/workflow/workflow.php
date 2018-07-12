<?php

include_once("include/workflow/alert_utils.php");
include_once("include/workflow/action_utils.php");
include_once("include/workflow/time_utils.php");
include_once("include/workflow/trigger_utils.php");
//BEGIN WFLOW PLUGINS
include_once("include/workflow/custom_utils.php");
//END WFLOW PLUGINS
	class Meetings_workflow {
	function process_wflow_triggers(& $focus){
		include("custom/modules/Meetings/workflow/triggers_array.php");
		include("custom/modules/Meetings/workflow/alerts_array.php");
		include("custom/modules/Meetings/workflow/actions_array.php");
		include("custom/modules/Meetings/workflow/plugins_array.php");
		
 if((isset($focus->meeting_type) && $focus->meeting_type ==  'Meeting')){ 
 

	 //Frame Secondary 

	 $secondary_array = array(); 
	 //Secondary Triggers 

	global $triggeredWorkflows;
	if (!isset($triggeredWorkflows['8cb28cc2_a8a8_792c_1d97_586f114df640'])){
		$triggeredWorkflows['8cb28cc2_a8a8_792c_1d97_586f114df640'] = true;
		 $alertshell_array = array(); 

	 $alertshell_array['alert_msg'] = "2fdabf90-1462-c2c4-ce9e-57c402765c05"; 

	 $alertshell_array['source_type'] = "Custom Template"; 

	 $alertshell_array['alert_type'] = "Email"; 

	 process_workflow_alerts($focus, $alert_meta_array['Meetings0_alert0'], $alertshell_array, false); 
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