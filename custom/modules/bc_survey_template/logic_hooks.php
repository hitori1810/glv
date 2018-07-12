<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
 $hook_version = 1; 
$hook_array = Array(); 
// position, file, function 
$hook_array['after_ui_frame'] = Array(); 
$hook_array['after_ui_frame'][] = Array(1001, 'Include javascript files for Survey', 'modules/bc_survey/addSurveyJsInSupportModules.php','addSurveyJsInSupportModules', 'getSurveyScriptsForSurveyTemplate'); 
$hook_array['process_record'] = Array(); 
$hook_array['process_record'][] = Array(1001, 'Create Survey Button On ListView', 'modules/bc_survey/addSurveyJsInSupportModules.php','addSurveyJsInSupportModules', 'create_survey_button'); 



?>