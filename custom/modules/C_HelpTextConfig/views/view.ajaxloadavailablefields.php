<?php

    require_once("custom/modules/C_HelpTextConfig/HelpTextConfigHelper.php");
    
    class C_HelpTextConfigViewAjaxLoadAvailableFields extends SugarView {

        function C_HelpTextConfigViewAjaxLoadAvailableFields() {
            parent::SugarView();
        }

        function display() {
            if(isset($_POST['moduleName']) && !empty($_POST['moduleName'])) {
                // Generate available field array
                $fieldList = @HelpTextConfigHelper::getFieldList($_POST['moduleName']);  
                $availableFieldArr = array();
                foreach($fieldList as $fieldName => $label) {
                    $availableFieldArr[] = array(
                        'field_name' => $fieldName, 
                        'label' => $label,
                        'help_text' => ''
                    ); 
                }
                
                echo json_encode($availableFieldArr); 
            }

            parent::display();
        }

    }
?>
