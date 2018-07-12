<?php

    require_once("custom/modules/C_FieldHighlighter/FieldHighlighterHelper.php");
    
    class C_FieldHighlighterViewAjaxLoadAvailableFields extends SugarView {

        function C_FieldHighlighterViewAjaxLoadAvailableFields() {
            parent::SugarView();
        }

        function display() {
            if(isset($_POST['moduleName']) && !empty($_POST['moduleName'])) {
                // Generate available field array
                $fieldList = @FieldHighlighterHelper::getFieldList($_POST['moduleName']);  
                $availableFieldArr = array();
                foreach($fieldList as $fieldName => $label) {
                    $availableFieldArr[] = array(
                        'field_name' => $fieldName, 
                        'label' => $label,
                        'applied_style' => 'blue'
                    ); 
                }
                
                echo json_encode($availableFieldArr); 
            }

            parent::display();
        }

    }
?>
