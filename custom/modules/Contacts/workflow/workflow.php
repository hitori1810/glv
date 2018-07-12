<?php

include_once("include/workflow/alert_utils.php");
include_once("include/workflow/action_utils.php");
include_once("include/workflow/time_utils.php");
include_once("include/workflow/trigger_utils.php");
//BEGIN WFLOW PLUGINS
include_once("include/workflow/custom_utils.php");
//END WFLOW PLUGINS
	class Contacts_workflow {
	function process_wflow_triggers(& $focus){
		include("custom/modules/Contacts/workflow/triggers_array.php");
		include("custom/modules/Contacts/workflow/alerts_array.php");
		include("custom/modules/Contacts/workflow/actions_array.php");
		include("custom/modules/Contacts/workflow/plugins_array.php");
		if(empty($focus->fetched_row['id']) || (!empty($_SESSION["workflow_cron"]) && $_SESSION["workflow_cron"]=="Yes" && !empty($_SESSION["workflow_id_cron"]) && $_SESSION["workflow_id_cron"]=="222f0a9c-647a-7b85-f397-580f16fd374d")){ 
 
 if((isset($focus->student_type) && $focus->student_type ==  'Adult')){ 
 

	 //Frame Secondary 

	 $secondary_array = array(); 
	 //Secondary Triggers 

	global $triggeredWorkflows;
	if (!isset($triggeredWorkflows['5a84c6d6_db97_46fa_a14f_58b3b06d8999'])){
		$triggeredWorkflows['5a84c6d6_db97_46fa_a14f_58b3b06d8999'] = true;
		 $alertshell_array = array(); 

	 $alertshell_array['alert_msg'] = "5f494a9b-4b26-4ef9-48c6-580f15565d96"; 

	 $alertshell_array['source_type'] = "Custom Template"; 

	 $alertshell_array['alert_type'] = "Email"; 

	 process_workflow_alerts($focus, $alert_meta_array['Contacts0_alert0'], $alertshell_array, false); 
 	 unset($alertshell_array); 
		}
 

	 //End Frame Secondary 

	 unset($secondary_array); 
 

 //End if trigger is true 
 } 

		 //End if new, update, or all record
 		} 


	//end function process_wflow_triggers
	}

	//end class
	}

?>