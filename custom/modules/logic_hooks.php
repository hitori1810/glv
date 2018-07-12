<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['after_ui_frame'] = Array(); 
$hook_array['after_ui_frame'][] = Array(67, 'Add Tab to User Editor', 'custom/modules/Users/HomepageManagerLogicHook.php','defaultHomepage', 'addTab'); 
$hook_array['after_ui_frame'][] = Array(11, 'Sugar Asterisk Integration', 'custom/modules/Asterisk/include/LoadConnector.php','LoadConnector', 'echoJavaScript'); 
$hook_array['after_retrieve'] = Array(); 
$hook_array['after_retrieve'][] = Array(68, 'Lock Individual Homepages', 'custom/modules/Users/HomepageManagerLogicHook.php','defaultHomepage', 'resetConfig'); 
$hook_array['before_save'] = Array(); 
$hook_array['before_save'][] = Array(99, 'Before Save Survey Send Logic Hook', 'modules/bc_survey_automizer/before_save_logichook.php','before_save_logichook', 'check_survey_automizer_method'); 
$hook_array['before_delete'] = Array(); 
$hook_array['before_delete'][] = Array(99, 'submission_after_delete', 'custom/include/deleteSubmission.php','deletedSubmission', 'deletedSubmission_method'); 
$hook_array['after_save'] = Array(); 
$hook_array['after_relationship_add'] = Array(); 
$hook_array['after_relationship_delete'] = Array(); 
$hook_array['after_ui_footer'] = Array(); 



?>