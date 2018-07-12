<?php
    $hook_version = 1; 
    $hook_array = Array(); 
    // position, file, function 
    $hook_array['process_record'] = Array(); 
    $hook_array['process_record'][] = Array(1, 'Prevent editing and deleting default config', 'custom/modules/C_DuplicationDetection/DuplicationDetectionLogicHooks.php','DuplicationDetectionLogicHooks','preventEditOrDeleteDefaultConfig');
    $hook_array['process_record'][] = Array(2, 'Show friendly field name', 'custom/modules/C_DuplicationDetection/DuplicationDetectionLogicHooks.php','DuplicationDetectionLogicHooks','showFriendlyFieldName');
    $hook_array['process_record'][] = Array(3, 'Show friendly module name', 'custom/modules/C_DuplicationDetection/DuplicationDetectionLogicHooks.php','DuplicationDetectionLogicHooks','showFriendlyModuleName');
?>