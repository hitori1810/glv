<?php

    class C_KeyboardSettingViewDetail extends ViewDetail {

        function C_KeyboardSettingViewDetail() {
            parent::ViewDetail();
        }

        function display() {
            // Replace json value with a friendly table
            $this->ss->assign('TARGET_FIELD_TABLE', $this->getTargetFieldTable());

            parent::display();
        }
        
        private function getTargetFieldTable() {
            require_once("custom/include/utils/FieldHelper.php");
            global $mod_strings, $app_list_strings;
            $moduleName = $this->bean->target_module;
            $targetFields = json_decode(html_entity_decode($this->bean->target_fields));
            
            $table = '';
            if(!empty($targetFields)) {
                $table = '<link rel="stylesheet" type="text/css" href="custom/modules/C_KeyboardSetting/css/EditView.css"/>';    
                $table .= '<table id="tblTargetFields">';    
                $table .= '<thead>';    
                $table .= '<tr>';    
                $table .= '<th>'. $mod_strings['LBL_APPLIED_FIELD'] .'</th>';    
                $table .= '<th>'. $mod_strings['LBL_APPLIED_FIELD_NAME'] .'</th>';    
                $table .= '<th>'. $mod_strings['LBL_APPLIED_CORRECTION_TYPE'] .'</th>';    
                $table .= '</tr>';    
                $table .= '</thead>';    
                foreach($targetFields as $fieldName => $config) {
                    $table .= '<tr>';  
                    $table .= '<td>'. FieldHelper::getLabel($moduleName, $fieldName) .'</td>';        
                    $table .= '<td>'. $fieldName .'</td>';          
                    $table .= '<td>'. translate('keyboardsetting_correction_type_options', '', $config->correction_type) .'</td>';          
                    $table .= '</tr>';          
                }    
                $table .= '</table>';    
            }
            
            return $table;
        }

    }
?>
