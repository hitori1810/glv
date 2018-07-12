<?php

    class C_HelpTextConfigViewAjaxLoadConfig extends SugarView {

        function C_HelpTextConfigViewAjaxLoadConfig() {
            parent::SugarView();
        }

        function display() { 
            if(isset($_POST['moduleName']) && !empty($_POST['moduleName'])) {
                // Get config that is active
                $moduleName = $_POST['moduleName'];
                
                $data = new C_HelpTextConfig();
                $data->retrieve_by_string_fields(
                    array(
                        'target_module' => $moduleName,
                        'is_active' => 1
                    )
                );
                
                if(!empty($data->target_fields)) {
                    require_once("custom/include/utils/FieldHelper.php");
                    
                    // Return config that has target fields only
                    $config = json_decode(html_entity_decode($data->target_fields), true);
                    foreach($config as $fieldName => $data) {
                        $config[$fieldName]['label'] = FieldHelper::getLabel($moduleName, $fieldName);    
                    }
                    
                    echo json_encode($config);    
                }
                 
            }

            parent::display();
        }

    }
?>
