<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2014-10-08 08:28:57
/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$layout_defs["Leads"]["subpanel_setup"]['bc_survey_leads'] = array (
  'order' => 100,
  'module' => 'bc_survey',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_BC_SURVEY_LEADS_FROM_BC_SURVEY_TITLE',
  'get_subpanel_data' => 'bc_survey_leads',
  'top_buttons' => array (),
);


 // created: 2014-10-08 08:28:57
/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$layout_defs["Leads"]["subpanel_setup"]['bc_survey_submission_leads'] = array (
  'order' => 100,
  'module' => 'bc_survey_submission',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_BC_SURVEY_SUBMISSION_LEADS_FROM_BC_SURVEY_SUBMISSION_TITLE',
  'get_subpanel_data' => 'bc_survey_submission_leads',
  'top_buttons' => array (),
);


 // created: 2015-10-19 08:53:40
$layout_defs["Leads"]["subpanel_setup"]['j_class_leads_1'] = array (
  'order' => 100,
  'module' => 'J_Class',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_J_CLASS_LEADS_1_FROM_J_CLASS_TITLE',
  'get_subpanel_data' => 'j_class_leads_1',
  'top_buttons' => 
  array (
    //0 => 
//    array (
//      'widget_class' => 'SubPanelTopButtonQuickCreate',
//    ),
//    1 => 
//    array (
//      'widget_class' => 'SubPanelTopSelectButton',
//      'mode' => 'MultiSelect',
//    ),
  ),
);


 // created: 2015-09-23 15:23:34
$layout_defs["Leads"]["subpanel_setup"]['leads_contacts_1'] = array (
  'order' => 100,
  'module' => 'Contacts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_LEADS_CONTACTS_1_FROM_CONTACTS_TITLE',
  'get_subpanel_data' => 'leads_contacts_1',
  'top_buttons' => 
  array (
//    0 => 
//    array (
//      'widget_class' => 'SubPanelTopButtonQuickCreate',
//    ),
//    1 => 
//    array (
//      'widget_class' => 'SubPanelTopSelectButton',
//      'mode' => 'MultiSelect',
//    ),
  ),
);


 // created: 2014-09-28 15:16:06
$layout_defs["Leads"]["subpanel_setup"]['leads_c_payments_1'] = array (
  'order' => 100,
  'module' => 'C_Payments',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_LEADS_C_PAYMENTS_1_FROM_C_PAYMENTS_TITLE',
  'get_subpanel_data' => 'leads_c_payments_1',
  'top_buttons' => 
  array (
  ),
);


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


 // created: 2015-09-29 17:15:18
$layout_defs["Leads"]["subpanel_setup"]['leads_leads_1'] = array (
  'order' => 100,
  'module' => 'Leads',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_LEADS_LEADS_1_FROM_LEADS_R_TITLE',
  'get_subpanel_data' => 'leads_leads_1',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);


    //$layout_defs["Leads"]["subpanel_setup"]['meetings_leads'] = array (
//        'order' => 90,
//        'module' => 'Meetings',
//        'subpanel_name' => 'default',
//        'sort_order' => 'asc',
//        'sort_by' => 'date_start',
//        'title_key' => 'LBL_SESSION_TITLE',
//        'get_subpanel_data' => 'meetings',
//        'top_buttons' => array (
//            0 => 
//            array (
//                'widget_class' => 'SubPanelSelectButtonOnTop',
//                'mode' => 'MultiSelect',
//            ),
//        ),
//    );


 
 // created: 2016-05-23 03:55:52

							$layout_defs['Leads']['subpanel_setup']['lead_c_sms'] = array (
							  'order' => 102,
							  'module' => 'C_SMS',
							  'subpanel_name' => 'default',
							  'sort_order' => 'asc',
							  'sort_by' => 'date_entered',
							  'title_key' => 'LBL_C_SMS',
							  'get_subpanel_data' => 'lead_c_sms',
							  'top_buttons' =>
							  array (
								    array('widget_class' => 'SubPanelSMSButton')
							  ),
							);
							

/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
//auto-generated file DO NOT EDIT
$layout_defs['Leads']['subpanel_setup']['bc_survey_submission_leads']['override_subpanel_name'] = 'Lead_subpanel_bc_survey_submission_leads';


//auto-generated file DO NOT EDIT
$layout_defs['Leads']['subpanel_setup']['j_class_leads_1']['override_subpanel_name'] = 'Lead_subpanel_j_class_leads_1';


//auto-generated file DO NOT EDIT
$layout_defs['Leads']['subpanel_setup']['j_ptresult_leads_1']['override_subpanel_name'] = 'Lead_subpanel_j_ptresult_leads_1';


//auto-generated file DO NOT EDIT
$layout_defs['Leads']['subpanel_setup']['leads_j_ptresult_1']['override_subpanel_name'] = 'Lead_subpanel_leads_j_ptresult_1';


//auto-generated file DO NOT EDIT
$layout_defs['Leads']['subpanel_setup']['lead_demo']['override_subpanel_name'] = 'Lead_subpanel_lead_demo';


//auto-generated file DO NOT EDIT
$layout_defs['Leads']['subpanel_setup']['lead_pt']['override_subpanel_name'] = 'Lead_subpanel_lead_pt';


//auto-generated file DO NOT EDIT
$layout_defs['Leads']['subpanel_setup']['meetings_leads']['override_subpanel_name'] = 'Lead_subpanel_meetings_leads';

?>