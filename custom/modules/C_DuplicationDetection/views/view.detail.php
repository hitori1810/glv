<?php

    class C_DuplicationDetectionViewDetail extends ViewDetail {

        function C_DuplicationDetectionViewDetail() {
            parent::ViewDetail();
        }

        function display() {
            require_once("custom/include/utils/FieldHelper.php");
            $moduleName = $this->bean->target_module;
            $targetFields = json_decode(html_entity_decode($this->bean->target_fields));
            
            // Get label for each fields
            $fields = '<ul>';
            foreach($targetFields as $fieldName) {
                $fields .= '<li>'. FieldHelper::getLabel($moduleName, $fieldName) .'</li>';   
            }
            $fields .= '</ul>'; 
            
            $this->bean->target_fields = $fields;

            parent::display();
        }

    }
?>
