<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    
    class DuplicationDetectionLogicHooks {
        
        function showFriendlyModuleName(&$bean, $event, $arguments){
            // ProcessRecord
            
            if(!empty($bean->target_module)) {
                global $app_list_strings;
                $bean->target_module = $app_list_strings['moduleList'][$bean->target_module];
            }
        }
        
        function showFriendlyFieldName(&$bean, $event, $arguments){
            // ProcessRecord
            
            if(!empty($bean->target_fields)) {
                require_once("custom/include/utils/FieldHelper.php");
                $moduleName = $bean->target_module;
                $targetFields = json_decode(html_entity_decode($bean->target_fields));
                
                // Get label for each fields
                $fields = array();
                foreach($targetFields as $fieldName) {
                    $fields[] = FieldHelper::getLabel($moduleName, $fieldName);   
                } 
                
                $bean->target_fields = join(', ', $fields);   
            }
        }
        
        function preventEditOrDeleteDefaultConfig(&$bean, $event, $arguments) {
            // ProcessRecord
            $importantModules = array(
                'C_DetailViewEditableConfig', 
                'C_DuplicationDetection', 
                'C_FieldHighlighter', 
                'C_HelpTextConfig', 
                'C_KeyboardSetting'
            );
            
            if(in_array($bean->target_module, $importantModules)) {
                // Don't allow to edit or delete default config record
                $bean->name .= '<img src="themes/default/images/blank.gif" onload="var row = $(this).closest(\'tr\'); row.find(\'td:nth(0) .checkbox\').remove(); row.find(\'td:nth(2) a\').remove();">';
            }
        }
    }
?>
