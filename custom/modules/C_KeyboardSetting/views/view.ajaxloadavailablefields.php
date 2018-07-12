<?php

    require_once("custom/modules/C_KeyboardSetting/KeyboardSettingHelper.php");
    
    class C_KeyboardSettingViewAjaxLoadAvailableFields extends SugarView {

        function C_KeyboardSettingViewAjaxLoadAvailableFields() {
            parent::SugarView();
        }

        function display() {
            if(isset($_POST['moduleName']) && !empty($_POST['moduleName'])) {
                // Generate available field array
                $fieldList = @KeyboardSettingHelper::getFieldList($_POST['moduleName']);  
                $availableFieldArr = array();
                foreach($fieldList as $fieldName => $label) {
                    $availableFieldArr[] = array(
                        'field_name' => $fieldName, 
                        'label' => $label,
                        'correction_type' => 'uppercase_all'
                    ); 
                }
                
                echo json_encode($availableFieldArr); 
            }

            parent::display();
        }

    }
?>
