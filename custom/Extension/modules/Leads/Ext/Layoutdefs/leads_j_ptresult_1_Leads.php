<?php
// created: 2015-09-07 09:49:06
$layout_defs["Leads"]["subpanel_setup"]['lead_pt'] = array (
    'order' => 200,
    'module' => 'J_PTResult',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'group' => 'PT',
    'title_key' => 'LBL_LEAD_PT',
    'get_subpanel_data' => 'function:getSubPTLead',
    'function_parameters' => array(
        'import_function_file' => 'custom/modules/Meetings/subPanelPTResult.php',
        'lead_id' => $this->_focus->id,
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

$layout_defs["Leads"]["subpanel_setup"]['lead_demo'] = array (
    'order' => 201,
    'module' => 'J_PTResult',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'group' => 'DEMO',
    'title_key' => 'LBL_LEAD_DEMO',
    'get_subpanel_data' => 'function:getSubDemoLead',
    'function_parameters' => array(
        'import_function_file' => 'custom/modules/Meetings/subPanelPTResult.php',
        'lead_id' => $this->_focus->id,
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

$layout_defs["Leads"]["subpanel_setup"]["lead_studentsituations"] = array (
    'order' => 52,
    'module' => 'J_StudentSituations',
    'subpanel_name' => 'default',
    'title_key' => 'Lead Situations',
    'sort_order' => 'desc',
    'sort_by' => 'end_study',
    'get_subpanel_data' => 'ju_studentsituations',
    'top_buttons' =>
    array (
    ),
);


$layout_defs["Leads"]["subpanel_setup"]['prospect_list_leads'] = array (   
    'order' => 101,   
    'module' => 'ProspectLists',   
    'subpanel_name' => 'default',   
    'sort_order' => 'asc',   
    'sort_by' => 'id',   
    'title_key' => 'LBL_PROSPECT_LIST',   
    'get_subpanel_data' => 'prospect_list_leads',   
    'top_buttons' => array (     
        // 0 => array (       
        //     'widget_class' => 'SubPanelTopButtonQuickCreate',
        //     'title'=>'LBL_CREATE_TARGET_LIST',
        //     'access_key'=>'LBL_CREATE_TARGET_LIST',       
        // ),     // Hide by Nguyen Tung 4-6-2018
        1 => array (       
            'widget_class' => 'SubPanelTopSelectButton',       
            'mode' => 'MultiSelect',
            'title'=>'LBL_SELECT_TARGET_LIST',
            'access_key'=>'LBL_SELECT_TARGET_LIST',     
        ),   
    ), 
);

$layout_defs["Leads"]["subpanel_setup"]["lead_payments"] = array (
    'order' => 202,
    'module' => 'J_Payment',
    'subpanel_name' => 'default',
    'title_key' => 'LBL_PAYMENT',
    'sort_order' => 'desc',
    'sort_by' => 'payment_date',
    'get_subpanel_data' => 'payment_link',
    'top_buttons' =>
    array (
        0 =>
        array (
            'widget_class' => 'SubPanelCreatePayment',
        ),

    ),
);
