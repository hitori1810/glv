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
$layout_defs["Contacts"]["subpanel_setup"]['bc_survey_contacts'] = array (
  'order' => 100,
  'module' => 'bc_survey',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_BC_SURVEY_CONTACTS_FROM_BC_SURVEY_TITLE',
  'get_subpanel_data' => 'bc_survey_contacts',
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
$layout_defs["Contacts"]["subpanel_setup"]['bc_survey_submission_contacts'] = array (
  'order' => 100,
  'module' => 'bc_survey_submission',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_BC_SURVEY_SUBMISSION_CONTACTS_FROM_BC_SURVEY_SUBMISSION_TITLE',
  'get_subpanel_data' => 'bc_survey_submission_contacts',
  'top_buttons' => array (),
);


 // created: 2015-08-05 14:40:26
$layout_defs["Contacts"]["subpanel_setup"]['contacts_contacts_1'] = array (
  'order' => 100,
  'module' => 'Contacts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CONTACTS_CONTACTS_1_FROM_CONTACTS_R_TITLE',
  'get_subpanel_data' => 'contacts_contacts_1',
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


 // created: 2014-04-12 00:26:05
$layout_defs["Contacts"]["subpanel_setup"]['contacts_c_invoices_1'] = array (
  'order' => 100,
  'module' => 'C_Invoices',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CONTACTS_C_INVOICES_1_FROM_C_INVOICES_TITLE',
  'get_subpanel_data' => 'contacts_c_invoices_1',
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


 // created: 2014-04-12 00:27:35
$layout_defs["Contacts"]["subpanel_setup"]['contacts_c_payments_1'] = array (
  'order' => 50,
  'module' => 'C_Payments',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CONTACTS_C_PAYMENTS_1_FROM_C_PAYMENTS_TITLE',
  'get_subpanel_data' => 'contacts_c_payments_1',
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


 // created: 2014-04-18 09:33:01
$layout_defs["Contacts"]["subpanel_setup"]['contacts_c_refunds_1'] = array (
  'order' => 100,
  'module' => 'C_Refunds',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CONTACTS_C_REFUNDS_1_FROM_C_REFUNDS_TITLE',
  'get_subpanel_data' => 'contacts_c_refunds_1',
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


 // created: 2015-07-16 08:57:21
$layout_defs["Contacts"]["subpanel_setup"]['contacts_j_feedback_1'] = array (
  'order' => 100,
  'module' => 'J_Feedback',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CONTACTS_J_FEEDBACK_1_FROM_J_FEEDBACK_TITLE',
  'get_subpanel_data' => 'contacts_j_feedback_1',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    //1 => 
//    array (
//      'widget_class' => 'SubPanelTopSelectButton',
//      'mode' => 'MultiSelect',
//    ),
  ),
);


// created: 2015-07-14 16:44:03
$layout_defs["Contacts"]["subpanel_setup"]['contacts_j_payment_1'] = array (
    'order' => 50,
    'module' => 'J_Payment',
    'subpanel_name' => 'default',
    'sort_order' => 'desc',
    'sort_by' => 'payment_date',
    'title_key' => 'LBL_CONTACTS_J_PAYMENT_1_FROM_J_PAYMENT_TITLE',
    'get_subpanel_data' => 'contacts_j_payment_1',
    'top_buttons' => 
    array (
        0 => 
        array (
            'widget_class' => 'SubPanelCreatePayment',
        ),
        1 => 
        array (
            'widget_class' => 'SubPanelTransfer',
        ),
        2 => 
        array (
            'widget_class' => 'SubPanelRefund',
        ),
    ),
);


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

$layout_defs["Contacts"]["subpanel_setup"]["contact_studentsituations"] = array (
    'order' => 52,
    'module' => 'J_StudentSituations',
    'subpanel_name' => 'default',
    'title_key' => 'LBL_SUPPANEL_STUDENT_SITUATION',
    'sort_order' => 'desc',
    'sort_by' => 'end_study',
    'get_subpanel_data' => 'ju_studentsituations',
    'top_buttons' =>
    array (
    ),
);
//display subpanel gradebookdetail by Lam Hai
$layout_defs["Contacts"]["subpanel_setup"]['student_j_gradebookdetail'] = array (
    'order' => 100,
    'module' => 'J_GradebookDetail',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_GRADEBOOK_DETAIL',
    'get_subpanel_data' => 'student_j_gradebookdetail',
    'top_buttons' =>
    array (
    ),
);
//end

$layout_defs["Contacts"]["subpanel_setup"]["contact_vouchers"] = array (
    'order' => 101,
    'module' => 'J_Voucher',
    'subpanel_name' => 'default',
    'title_key' => 'LBL_SUBPANEL_VOUCHER',
    'sort_order' => 'desc',
    'sort_by' => 'end_date',
    'get_subpanel_data' => 'ju_vouchers',
    'top_buttons' =>
    array (
    ),
);

$layout_defs["Contacts"]["subpanel_setup"]["student_loyaltys"] = array (
    'order' => 102,
    'module' => 'J_Loyalty',
    'subpanel_name' => 'default',
    'title_key' => 'LBL_LOYALTY',
    'sort_order' => 'desc',
    'sort_by' => 'input_date',
    'get_subpanel_data' => 'loyalty_link',
    'top_buttons' =>
    array (
        0 =>
        array (
            'widget_class' => 'SubPanelTopButtonQuickCreate',
        ),
    ),
);  

$layout_defs["Contacts"]["subpanel_setup"]['prospect_list_contacts'] = array (   
    'order' => 4,   
    'module' => 'ProspectLists',   
    'subpanel_name' => 'default',   
    'sort_order' => 'asc',   
    'sort_by' => 'id',   
    'title_key' => 'LBL_PROSPECT_LIST',   
    'get_subpanel_data' => 'prospect_lists',   
    'top_buttons' => array (                    
        1 => array (       
            'widget_class' => 'SubPanelTopSelectButton',       
            'mode' => 'MultiSelect',
            'title'=>'LBL_SELECT_TARGET_LIST',
            'access_key'=>'LBL_SELECT_TARGET_LIST',     
        ),   
    ), 
);



 // created: 2014-07-15 14:40:52
$layout_defs["Contacts"]["subpanel_setup"]['c_classes_contacts_1'] = array (
  'order' => 51,
  'module' => 'C_Classes',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_C_CLASSES_CONTACTS_1_FROM_C_CLASSES_TITLE',
  'get_subpanel_data' => 'c_classes_contacts_1',
  'top_buttons' => 
  array (
  ),
);


// created: 2015-08-14 09:21:53
$layout_defs["Contacts"]["subpanel_setup"]['j_class_contacts_1'] = array (
    'order' => 51,
    'module' => 'J_Class',
    'subpanel_name' => 'default',
    'sort_order' => 'asc',
    'sort_by' => 'id',
    'title_key' => 'LBL_J_CLASS_CONTACTS_1_FROM_J_CLASS_TITLE',
    'get_subpanel_data' => 'j_class_contacts_1',
    'top_buttons' => 
    array (
        0 => 
        array (
            'widget_class' => 'SubPanelMovingClass',
        ),
    ),
);


 // created: 2015-09-23 15:23:34
// $layout_defs["Contacts"]["subpanel_setup"]['leads_contacts_1'] = array (
//   'order' => 100,
//   'module' => 'Leads',
//   'subpanel_name' => 'default',
//   'sort_order' => 'asc',
//   'sort_by' => 'id',
//   'title_key' => 'LBL_LEADS_CONTACTS_1_FROM_LEADS_TITLE',
//   'get_subpanel_data' => 'leads_contacts_1',
//   'top_buttons' => 
//   array (
//    0 => 
//    array (
//      'widget_class' => 'SubPanelTopButtonQuickCreate',
//    ),
//    1 => 
//    array (
//      'widget_class' => 'SubPanelTopSelectButton',
//      'mode' => 'MultiSelect',
//    ),
  //),
//);


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


 
 // created: 2016-05-23 03:55:52

							$layout_defs['Contacts']['subpanel_setup']['contact_c_sms'] = array (
							  'order' => 250,
							  'module' => 'C_SMS',
							  'subpanel_name' => 'default',
							  'sort_order' => 'asc',
							  'sort_by' => 'date_entered',
							  'title_key' => 'LBL_C_SMS',
							  'get_subpanel_data' => 'contact_c_sms',
							  'top_buttons' =>
							  array (
								    array('widget_class' => 'SubPanelSMSButton')
							  ),
							);
							

//auto-generated file DO NOT EDIT
/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$layout_defs['Contacts']['subpanel_setup']['bc_survey_submission_contacts']['override_subpanel_name'] = 'Contact_subpanel_bc_survey_submission_contacts';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['cases']['override_subpanel_name'] = 'Contact_subpanel_cases';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['contacts_contacts_1']['override_subpanel_name'] = 'Contact_subpanel_contacts_contacts_1';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['contacts_c_payments_1']['override_subpanel_name'] = 'Contact_subpanel_contacts_c_payments_1';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['contacts_c_refunds_1']['override_subpanel_name'] = 'Contact_subpanel_contacts_c_refunds_1';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['contacts_j_feedback_1']['override_subpanel_name'] = 'Contact_subpanel_contacts_j_feedback_1';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['contacts_j_payment_1']['override_subpanel_name'] = 'Contact_subpanel_contacts_j_payment_1';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['contacts_j_ptresult_1']['override_subpanel_name'] = 'Contact_subpanel_contacts_j_ptresult_1';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['contact_demo']['override_subpanel_name'] = 'Contact_subpanel_contact_demo';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['contact_pt']['override_subpanel_name'] = 'Contact_subpanel_contact_pt';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['contact_studentsituations']['override_subpanel_name'] = 'Contact_subpanel_contact_studentsituations';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['contact_vouchers']['override_subpanel_name'] = 'Contact_subpanel_contact_vouchers';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['j_class_contacts_1']['override_subpanel_name'] = 'Contact_subpanel_j_class_contacts_1';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['j_feedback_contacts_1']['override_subpanel_name'] = 'Contact_subpanel_j_feedback_contacts_1';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['j_ptresult_contacts_1']['override_subpanel_name'] = 'Contact_subpanel_j_ptresult_contacts_1';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['meetings_contacts']['override_subpanel_name'] = 'Contact_subpanel_meetings_contacts';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['meetings_contacts_demo']['override_subpanel_name'] = 'Contact_subpanel_meetings_contacts_demo';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['opportunities']['override_subpanel_name'] = 'Contact_subpanel_opportunities';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['student_j_gradebookdetail']['override_subpanel_name'] = 'Contact_subpanel_student_j_gradebookdetail';


//auto-generated file DO NOT EDIT
$layout_defs['Contacts']['subpanel_setup']['student_loyaltys']['override_subpanel_name'] = 'Contact_subpanel_student_loyaltys';

?>