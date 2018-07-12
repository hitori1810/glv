<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will
// be automatically rebuilt in the future.
 $hook_version = 1;
$hook_array = Array();
// position, file, function
$hook_array['before_save'] = Array();
$hook_array['before_save'][] = Array(1, 'Check lisence', 'custom/modules/Contacts/logicStudent.php','logicStudent', 'checkStudentLimit');
$hook_array['before_save'][] = Array(2, 'Import Student', 'custom/modules/Contacts/logicStudent.php','logicStudent', 'importStudent');
$hook_array['before_save'][] = Array(3, 'LOGIC STUDENT', 'custom/modules/Contacts/logicStudent.php','logicStudent', 'handleStudentLogic');
$hook_array['before_save'][] = Array(4, 'Add Code', 'custom/modules/Contacts/logicStudent.php','logicStudent', 'addCode');

$hook_array['after_save'] = Array();
$hook_array['after_save'][] = Array(0, 'Auto Generate Account Portal', 'custom/modules/Contacts/logicStudent.php','logicStudent', 'autoGenerateAccountPortal');
$hook_array['after_save'][] = Array(1, 'workflow', 'include/workflow/WorkFlowHandler.php','WorkFlowHandler', 'WorkFlowHandler');
$hook_array['after_save'][] = Array(2, 'Get Info Facebook', 'custom/modules/Contacts/logicStudent.php','logicStudent', 'getInfoFacebook');
$hook_array['after_save'][] = Array(3, 'Create Lead', 'custom/modules/Contacts/logicStudent.php','logicStudent', 'createLead');

$hook_array['before_relationship_add'] = Array();
$hook_array['before_relationship_add'][] = Array(1, 'Add Corp Student', 'custom/modules/Contacts/logicStudent.php','logicStudent', 'handleAddRelationship');

$hook_array['after_relationship_delete'] = Array();
$hook_array['after_relationship_delete'][] = Array(1, 'Remove Corp Student', 'custom/modules/Contacts/logicStudent.php','logicStudent', 'handleRemoveRelationship');

$hook_array['process_record'] = Array();
$hook_array['process_record'][] = Array(1, 'Color', 'custom/modules/Contacts/logicStudent.php','logicStudent', 'listviewcolor_Stu');
$hook_array['process_record'][] = Array(2, 'Show situation type', 'custom/modules/Contacts/logicStudent.php','logicStudent', 'showSituationType');

$hook_array['after_delete'] = Array();
$hook_array['after_delete'][] = Array(1, 'Auto Delete Account Portal', 'custom/modules/Contacts/logicStudent.php','logicStudent', 'autoDeleteAccountPortal');

//$hook_array['after_ui_frame'] = Array();
//$hook_array['after_ui_frame'][] = Array(1001, 'Include javascript files for Survey', 'modules/bc_survey/addSurveyJsInSupportModules.php','addSurveyJsInSupportModules', 'getSurveyScripts');
//
$hook_array['before_delete'] = Array();
$hook_array['before_delete'][] = Array(1, 'Delete Contact', 'custom/modules/Contacts/logicStudent.php','logicStudent', 'beforeDeleteStudent');


?>