<?php
    $hook_version = 1; 
    $hook_array = Array(); 
    // position, file, function 
    $hook_array['process_record'] = Array(); 
    $hook_array['process_record'][] = Array(1, 'Show friendly module name', 'custom/modules/C_FieldHighlighter/FieldHighlighterLogicHooks.php','FieldHighlighterLogicHooks','showFriendlyModuleName');
    
    $hook_array['after_save'] = array();
    $hook_array['after_save'][] = Array(1, 'Apply field highlighter logic hook', 'custom/modules/C_FieldHighlighter/FieldHighlighterLogicHooks.php','FieldHighlighterLogicHooks','applyLogicHook');
?>