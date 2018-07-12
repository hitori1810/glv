<?php

    require_once("custom/include/utils/FieldHelper.php");
    require_once("custom/modules/C_DuplicationDetection/DuplicationDetectionHelper.php");
    
    class C_DuplicationDetectionViewEdit extends ViewEdit {

        function C_DuplicationDetectionViewEdit() {
            parent::ViewEdit();
        }

        function display() {
            global $mod_strings, $dictionary, $beanList;
            
            // Don't allow to edit the default config
            if($this->bean->target_module == 'C_DuplicationDetection') {
                global $app_strings;
                die($app_strings['LBL_EMAIL_DELETE_ERROR_DESC']);
            }
            
            // Preparing data for the form to be displayed
            $appliedFieldArr = array(array('field_name'=>'', 'label'=>''));     // One empty row is needed
            $availableFieldArr = array(array('field_name'=>'', 'label'=>''));     // One empty row is needed
            
            if(!empty($this->bean->target_module)) {
                $appliedModule = $this->bean->target_module;
                
                // Generate applied fields array
                $appliedFields = json_decode(html_entity_decode($this->bean->target_fields));
                $appliedFieldArr = array();
                for($i = 0; $i < count($appliedFields); $i++) {
                    $fieldName = $appliedFields[$i];
                    $appliedFieldArr[] = array(
                        'field_name' => $fieldName, 
                        'label' => FieldHelper::getLabel($appliedModule, $fieldName)
                    );
                }
                
                // Generate available field array
                $fieldList = DuplicationDetectionHelper::getFieldList($appliedModule);  
                $availableFieldArr = array();
                foreach($fieldList as $fieldName => $label) {
                    if(!in_array($fieldName, $appliedFields)) { // Get only fields that are not applied
                        $availableFieldArr[] = array(
                            'field_name' => $fieldName, 
                            'label' => $label
                        ); 
                    }
                }                          
            }

            $this->ss->assign('APPLIED_FIELDS', json_encode($appliedFieldArr));
            $this->ss->assign('AVAILABLE_FIELDS', json_encode($availableFieldArr));

            parent::display();
        }

    }
?>
