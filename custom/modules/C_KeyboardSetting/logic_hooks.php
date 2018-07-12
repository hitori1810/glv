<?php
    $hook_version = 1; 
    $hook_array = Array(); 
    // position, file, function 
    $hook_array['process_record'] = Array(); 
    $hook_array['process_record'][] = Array(1, 'Show friendly module name', 'custom/modules/C_KeyboardSetting/KeyboardSettingLogicHooks.php','KeyboardSettingLogicHooks','showFriendlyModuleName');
?>