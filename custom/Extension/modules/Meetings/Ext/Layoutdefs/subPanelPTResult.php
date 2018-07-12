<?php
    $layout_defs["Meetings"]["subpanel_setup"]['sub_pt_result'] =array(
        'order' => 150,
        'module' => 'J_PTResult',
        'subpanel_name' => 'default',
        'get_subpanel_data' => 'function:getSubResult',
        'generate_select' => true,
        'title_key' => 'LBL_PT_RESULT',
        'top_buttons' => '',
        'function_parameters' => array(
            'import_function_file' => 'custom/modules/Meetings/subPanelPTResult.php',
            'meeting_id' => $this->_focus->id,
            'return_as_array' => 'true'
        ), 
    );
    
    $layout_defs["Meetings"]["subpanel_setup"]['sub_demo_result'] =array(
        'order' => 160,
        'module' => 'J_PTResult',
        'subpanel_name' => 'default',
        'get_subpanel_data' => 'function:getSubDemoResult',
        'generate_select' => true,
        'title_key' => 'LBL_DEMO_RESULT',
        'top_buttons' => '',
        'function_parameters' => array(
            'import_function_file' => 'custom/modules/Meetings/subPanelPTResult.php',
            'meeting_id' => $this->_focus->id,
            'return_as_array' => 'true'
        ), 
    );
?>
