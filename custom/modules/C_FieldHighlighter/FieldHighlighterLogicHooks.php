<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    
    class FieldHighlighterLogicHooks {
        
        function showFriendlyModuleName(&$bean, $event, $arguments){
            // ProcessRecord
            
            if(!empty($bean->target_module)) {
                global $app_list_strings;
                $bean->target_module = $app_list_strings['moduleList'][$bean->target_module];
            }
        }
        
        function applyLogicHook(&$bean, $event, $arguments) {
            // AfterSave
            
            if(!empty($bean->target_module) && $bean->is_active == 1) {
                $newHook = array();
                $newHook[] = 1000;
                $newHook[] = 'Highlight fields';
                $newHook[] = 'custom/include/LogicHooks/FieldHighlighter.php';
                $newHook[] = 'FieldHighlighter';
                $newHook[] = 'highlightFields';
                
                // Write new config in logic_hook.php inside selected module
                check_logic_hook_file($bean->target_module, 'process_record', $newHook);
            }
        }
    }
?>
