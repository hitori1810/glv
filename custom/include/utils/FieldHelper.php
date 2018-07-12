<?php
    /*
    *   FieldHelper.php
    *   Author: Hieu Nguyen
    *   Purpose: A util class to handle all things that related to fields
    */

    class FieldHelper {

        // Get label of any fields in any modules
        public function getLabel($moduleName, $fieldName) {
            global $dictionary, $current_language, $beanList, $app_strings;
            $modStrings = return_module_language($current_language, $moduleName, true);
            $beanName = $beanList[$moduleName];
            if($moduleName == 'Cases') $beanName = 'Case';  // A bug of Case
            $fieldList = $dictionary[$beanName]['fields'];
            $fieldDefs = $fieldList[$fieldName];
            
            // Use friendly field label if it is defined
            $label = '';
            if (isset($fieldDefs['vname'])) {
                $label = $fieldDefs['vname'];
                
                // If the label is in the mod strings then get it
                if (isset($modStrings[$fieldDefs['vname']]))
                    $label = $modStrings[$fieldDefs['vname']];
                // Otherwise, search it in app strings
                else if(isset($app_strings[$fieldDefs['vname']]))
                    $label = $app_strings[$fieldDefs['vname']];
                    
                $label = str_replace(':', '', $label);  // Remove colon from the label if any
            }
            
            return $label;    
        }        

    }
?>
