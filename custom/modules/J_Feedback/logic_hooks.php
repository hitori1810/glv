<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(4, 'Save Full Type FeedBack', 'custom/modules/J_Feedback/before_save.php','before_save_feedback', 'make_full_type'); 
$hook_array['before_save'][] = Array(1, 'workflow', 'include/workflow/WorkFlowHandler.php','WorkFlowHandler', 'WorkFlowHandler');  
$hook_array['process_record'] = Array(); 
$hook_array['process_record'][] = Array(1, 'Color', 'custom/modules/J_Feedback/before_save.php','before_save_feedback', 'listViewColorBack'); 



?>