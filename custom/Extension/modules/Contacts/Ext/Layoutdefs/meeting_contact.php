<?php
    $layout_defs["Contacts"]["subpanel_setup"]['meetings_contacts'] = array (
            'order' => 52,
            'module' => 'Meetings',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'date_start',
            'title_key' => 'LBL_SESSION_TITLE',
            'get_subpanel_data' => 'meetings',
            'top_buttons' => array (),
        );

    /*$layout_defs["Contacts"]["subpanel_setup"]['meetings_contacts_demo'] = array (
            'order' => 300,
            'module' => 'Meetings',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'date_start',
            'title_key' => 'LBL_DEMO_TITLE',
            'get_subpanel_data' => 'function:getSubDemo',
            'function_parameters' => array(
                'import_function_file' => 'custom/modules/Contacts/subPanelDemo.php',
                'contact_id' => $this->_focus->id,
                'return_as_array' => 'true'
            ), 
            'top_buttons' => array (
                0 => 
                array (
                    'widget_class' => 'SubPanelSelectButtonOnTop',
                    'mode' => 'MultiSelect',
                ),
            ),
        );*/

//    $layout_defs["Contacts"]["subpanel_setup"]['meetings_contacts_demo'] = array (
//        'order' => 100,
//        'module' => 'Meetings',
//        'subpanel_name' => 'default',
//        'sort_order' => 'asc',
//        'sort_by' => 'date_start',
//        'title_key' => 'LBL_DEMO_TITLE',
//        'get_subpanel_data' => 'meetings',
//        'top_buttons' => array (
//            0 => 
//            array (
//                'widget_class' => 'SubPanelSelectButtonOnTop',
//                'mode' => 'MultiSelect',
//            ),
//        ),
//    );
?>
