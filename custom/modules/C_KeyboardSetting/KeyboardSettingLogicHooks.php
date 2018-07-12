<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    
    class KeyboardSettingLogicHooks {
        
        function showFriendlyModuleName(&$bean, $event, $arguments){
            // ProcessRecord
            
            if(!empty($bean->target_module)) {
                global $app_list_strings;
                $bean->target_module = $app_list_strings['moduleList'][$bean->target_module];
            }
        }
    }
?>
