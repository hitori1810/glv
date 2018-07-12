<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will
// be automatically rebuilt in the future.
 $hook_version = 1;
$hook_array = Array();
// position, file, function
$hook_array['after_ui_frame'] = Array();
$hook_array['after_ui_frame'][] = Array(1001, 'Include javascript files for Survey', 'modules/bc_survey/addSurveyJsInSupportModules.php','addSurveyJsInSupportModules', 'getSurveyScripts');
$hook_array['before_save'] = Array();
$hook_array['before_save'][] = Array(1, 'Add Code', 'custom/modules/Accounts/logicAccount.php','logicAccount', 'addCode');

$hook_array['process_record'] = Array();
$hook_array['process_record'][] = Array(1, 'get Desciption content', 'custom/include/LogicHooks/RowsHighlighter.php','RowsHighlighter', 'getlastContentForDescription');

?>