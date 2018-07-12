<?php

    require_once("custom/modules/C_DuplicationDetection/DuplicationDetectionHelper.php");
    
    class C_DuplicationDetectionViewAjaxLoadAvailableFields extends SugarView {

        function C_DuplicationDetectionViewAjaxLoadAvailableFields() {
            parent::SugarView();
        }

        function display() {
            if(isset($_POST['moduleName']) && !empty($_POST['moduleName'])) {
                // Generate available field array
                $fieldList = @DuplicationDetectionHelper::getFieldList($_POST['moduleName']);  
                $availableFieldArr = array();
                foreach($fieldList as $fieldName => $label) {
                    $availableFieldArr[] = array(
                        'field_name' => $fieldName, 
                        'label' => $label
                    ); 
                }
                
                echo json_encode($availableFieldArr); 
            }

            parent::display();
        }

    }
?>
