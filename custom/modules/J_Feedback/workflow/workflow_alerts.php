<?php

include_once("include/workflow/alert_utils.php");
    class J_Feedback_alerts {
    function process_wflow_J_Feedback2_alert0(&$focus){
            include("custom/modules/J_Feedback/workflow/alerts_array.php");

	 $alertshell_array = array(); 

	 $alertshell_array['alert_msg'] = "445698ab-e591-52ec-b677-58083345c0ac"; 

	 $alertshell_array['source_type'] = "Custom Template"; 

	 $alertshell_array['alert_type'] = "Email"; 

	 process_workflow_alerts($focus, $alert_meta_array['J_Feedback2_alert0'], $alertshell_array, false); 
 	 unset($alertshell_array); 
	 }



    //end class
    }

?>