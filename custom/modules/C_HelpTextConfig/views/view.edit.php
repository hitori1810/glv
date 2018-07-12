<?php

    require_once("custom/include/utils/FieldHelper.php");
    require_once("custom/modules/C_HelpTextConfig/HelpTextConfigHelper.php");
    
    class C_HelpTextConfigViewEdit extends ViewEdit {

        function C_HelpTextConfigViewEdit() {
            parent::ViewEdit();
        }

        function display() {
            global $app_list_strings, $mod_strings, $dictionary, $beanList;
            
            $appliedFieldArr = array(array('field_name'=>'', 'label'=>''));     // One empty row is needed
            $availableFieldArr = array(array('field_name'=>'', 'label'=>''));     // One empty row is needed
            
            if(!empty($this->bean->target_module)) {
                $appliedModule = $this->bean->target_module;
                
                // Generate applied fields array
                $appliedFields = json_decode(html_entity_decode($this->bean->target_fields));
                $appliedFieldArr = array();
                $appliedFieldNames = array();
                if(count($appliedFields) > 0) {
                    foreach($appliedFields as $fieldName => $config) {
                        $appliedFieldArr[] = array(
                            'field_name' => $fieldName, 
                            'label' => FieldHelper::getLabel($appliedModule, $fieldName),
                            'help_text' => $config->help_text
                        );
                        
                        // Create applied field names array to compare in the next step
                        $appliedFieldNames[] = $config->field_name;
                    }
                }
                
                // Generate available field array
                $fieldList = HelpTextConfigHelper::getFieldList($appliedModule);  
                $availableFieldArr = array();
                foreach($fieldList as $fieldName => $label) {
                    $field = array(
                        'field_name' => $fieldName, 
                        'label' => $label,
                        'help_text' => 'uppercase_all'
                    );
                    
                    if(in_array($fieldName, $appliedFieldNames))
                        $field = null; // Get only fields that are not applied
                    
                    if($field != null)
                        $availableFieldArr[] = $field;
                }
            }

            $this->ss->assign('APPLIED_FIELDS', json_encode($appliedFieldArr));
            $this->ss->assign('AVAILABLE_FIELDS', json_encode($availableFieldArr));

            parent::display();
        }

    }
?>
