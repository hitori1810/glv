<?php

    class C_DuplicationDetectionViewAjaxLoadConfig extends SugarView {

        function C_DuplicationDetectionViewAjaxLoadConfig() {
            parent::SugarView();
        }

        function display() { 
            if(isset($_POST['moduleName']) && !empty($_POST['moduleName'])) {
                // Get config that is active
                $data = new C_DuplicationDetection();
                $data->retrieve_by_string_fields(
                    array(
                        'target_module' => $_POST['moduleName'],
                        'is_active' => 1
                    )
                );
                
                $targetFields = array();
                if(!empty($data->target_fields)) {
                    // Return config that has target fields only
                    $targetFields = array(
                        'targetFields' => json_decode(html_entity_decode($data->target_fields)),           
                        'preventiveType' => $data->preventive_type,           
                    );    
                }
                
                echo json_encode($targetFields); 
            }

            parent::display();
        }

    }
?>
