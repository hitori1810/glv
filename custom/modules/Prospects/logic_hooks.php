<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will
// be automatically rebuilt in the future.
 $hook_version = 1;
$hook_array = Array();
// position, file, function
$hook_array['before_save'] = Array();
$hook_array['before_save'][] = Array(1, 'Handle Before Save', 'custom/modules/Prospects/logicProspects.php','logicProspects', 'beforeSaveProspects');
$hook_array['process_record'] = Array();
$hook_array['process_record'][] = Array(1, 'Color Status', 'custom/modules/Prospects/logicProspects.php','logicProspects', 'listviewcolor');
$hook_array['after_ui_frame'] = Array();
$hook_array['after_ui_frame'][] = Array(1001, 'Include javascript files for Survey', 'modules/bc_survey/addSurveyJsInSupportModules.php','addSurveyJsInSupportModules', 'getSurveyScripts');



?>