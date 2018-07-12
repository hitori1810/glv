<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will
// be automatically rebuilt in the future.
 $hook_version = 1;
$hook_array = Array();
// position, file, function
$hook_array['before_save'] = Array();
$hook_array['before_save'][] = Array(0, 'Add Auto-Increment Code', 'custom/modules/J_Payment/logicPayment.php','logicPayment', 'addCode');
$hook_array['before_save'][] = Array(1, 'workflow', 'include/workflow/WorkFlowHandler.php','WorkFlowHandler', 'WorkFlowHandler');
$hook_array['before_save'][] = Array(2, 'Handle before save', 'custom/modules/J_Payment/logicPayment.php','logicPayment', 'handleBeforeSave');
$hook_array['before_save'][] = Array(3, 'Import Payment', 'custom/modules/J_Payment/logicImport.php','logicImport', 'importPayment');

$hook_array['after_save'] = Array();
$hook_array['after_save'][] = Array(1, 'Handle After save', 'custom/modules/J_Payment/logicPayment.php','logicPayment', 'afterSavePayment');
$hook_array['after_save'][] = Array(2, 'save Status Paid', 'custom/modules/J_Payment/logicPayment.php','logicPayment', 'saveStatusPaid');

$hook_array['process_record'] = Array();
$hook_array['process_record'][] = Array(1, 'Color', 'custom/modules/J_Payment/logicPayment.php','logicPayment', 'listViewColorPayment');
$hook_array['before_delete'] = Array();
$hook_array['before_delete'][] = Array(2, 'Delete Payment', 'custom/modules/J_Payment/logicPayment.php','logicPayment', 'deletedPayment');



?>