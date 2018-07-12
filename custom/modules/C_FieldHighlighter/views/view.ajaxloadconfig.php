<?php

    class C_FieldHighlighterViewAjaxLoadConfig extends SugarView {

        function C_FieldHighlighterViewAjaxLoadConfig() {
            parent::SugarView();
        }

        function display() { 
            if(isset($_POST['moduleName']) && !empty($_POST['moduleName'])) {
                // Get config that is active
                $data = new C_FieldHighlighter();
                $data->retrieve_by_string_fields(
                    array(
                        'target_module' => $_POST['moduleName'],
                        'is_active' => 1
                    )
                );
                
                if(!empty($data->target_fields)) {
                    // Return config that has target fields only
                    echo html_entity_decode($data->target_fields);    
                }
                 
            }

            parent::display();
        }

    }
?>
