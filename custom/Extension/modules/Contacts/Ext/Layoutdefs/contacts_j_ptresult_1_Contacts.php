<?php
    // created: 2015-09-07 09:49:06
    $layout_defs["Contacts"]["subpanel_setup"]['contact_pt'] = array (
        'order' => 54,
        'module' => 'J_PTResult',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'group' => 'PT',
        'title_key' => 'LBL_CONTACT_PT',
        'get_subpanel_data' => 'function:getSubPTContact',
        'function_parameters' => array(
            'import_function_file' => 'custom/modules/Meetings/subPanelPTResult.php',
            'contact_id' => $this->_focus->id,
            'return_as_array' => 'true'
        ),
        'top_buttons' =>
        array (
            1 =>
            array (
                'widget_class' => 'SubPanelSelectButtonOnTop',
                'mode' => 'MultiSelect'
            ),
        ),
    );

    $layout_defs["Contacts"]["subpanel_setup"]['contact_demo'] = array (
        'order' => 55,
        'module' => 'J_PTResult',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'group' => 'DEMO',
        'title_key' => 'LBL_CONTACT_DEMO',
        'get_subpanel_data' => 'function:getSubDemoContact',
        'function_parameters' => array(
            'import_function_file' => 'custom/modules/Meetings/subPanelPTResult.php',
            'contact_id' => $this->_focus->id,
            'return_as_array' => 'true'
        ),
        'top_buttons' =>
        array (
            1 =>
            array (
                'widget_class' => 'SubPanelSelectButtonOnTop',
                'mode' => 'MultiSelect'
            ),
        ),
    );