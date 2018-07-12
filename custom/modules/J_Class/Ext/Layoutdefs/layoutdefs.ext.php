<?php 
 //WARNING: The contents of this file are auto-generated


// // created: 2016-11-25 10:58:12
//$layout_defs["J_Class"]["subpanel_setup"]['contracts_j_class_1'] = array (
//  'order' => 100,
//  'module' => 'Contracts',
//  'subpanel_name' => 'default',
//  'sort_order' => 'asc',
//  'sort_by' => 'id',
//  'title_key' => 'LBL_CONTRACTS_J_CLASS_1_FROM_CONTRACTS_TITLE',
//  'get_subpanel_data' => 'contracts_j_class_1',
//  'top_buttons' =>
//  array (
//    0 =>
//    array (
//      'widget_class' => 'SubPanelTopButtonQuickCreate',
//    ),
//    1 =>
//    array (
//      'widget_class' => 'SubPanelTopSelectButton',
//      'mode' => 'MultiSelect',
//    ),
//  ),
//);


$layout_defs["J_Class"]["subpanel_setup"]["j_class_meetings"] = array (
    'order' => 30,
    'module' => 'Meetings',
    'subpanel_name' => 'default',
    'title_key' => 'LBL_SUPPANEL_SESSION',
    'sort_order' => 'asc',
    'sort_by' => 'date_start',
    'get_subpanel_data' => 'ju_meetings',
    'top_buttons' =>
    array (    
        1 =>
        array (
            'widget_class' => 'SubPanelClass',
        ),
    ),
);
$layout_defs["J_Class"]["subpanel_setup"]["j_class_meetings_syllabus"] = array (
    'order' => 40,
    'module' => 'Meetings',
    'subpanel_name' => 'J_Class_subpanel_j_class_meetings_syllabus',
    'title_key' => 'LBL_SUPPANEL_SYLLABUS',
    'sort_order' => 'asc',
    'sort_by' => 'date_start',
    'get_subpanel_data' => 'ju_meetings',
    'top_buttons' =>
    array (
        0 =>
        array (
            'widget_class' => 'SubPabelSyllabus',
        ),
    ),
);
$layout_defs["J_Class"]["subpanel_setup"]["j_class_studentsituations"] = array (
    'order' => 20,
    'module' => 'J_StudentSituations',
    'subpanel_name' => 'default',                    
    'title_key' => 'LBL_SUPPANEL_ENROLLMENT_INFO',
    'sort_order' => 'desc',
    'sort_by' => 'student_id, start_study',
    'get_subpanel_data' => 'function:getSubSituation',
    'function_parameters' => array(
        'import_function_file' => 'custom/modules/J_Class/customSubPanel.php',
        'class_id' => $this->_focus->id,
        'return_as_array' => 'true'
    ),
    'top_buttons' =>
    array (
        0 =>
        array (
            'widget_class' => 'SubPanelDateSituation',
        ),

    ),
);



 // created: 2015-08-14 09:21:53

$layout_defs["J_Class"]["subpanel_setup"]['j_class_contacts_1'] = array (
  'order' => 10,
  'module' => 'Contacts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_J_CLASS_CONTACTS_1_FROM_CONTACTS_TITLE',
  'get_subpanel_data' => 'j_class_contacts_1',
  'top_buttons' => 
  array (
    0 => 
    array (                                         
      'widget_class' => 'SubPanelSendSMS',
    ),    
  ),
);


 // created: 2015-08-11 08:50:10
$layout_defs["J_Class"]["subpanel_setup"]['j_class_c_teachers_1'] = array (
  'order' => 31,
  'module' => 'C_Teachers',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_J_CLASS_C_TEACHERS_1_FROM_C_TEACHERS_TITLE',
  'get_subpanel_data' => 'j_class_c_teachers_1',
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


 // created: 2015-07-16 16:15:52
//$layout_defs["J_Class"]["subpanel_setup"]['j_class_j_class_1'] = array (
//  'order' => 100,
//  'module' => 'J_Class',
//  'subpanel_name' => 'default',
//  'sort_order' => 'asc',
//  'sort_by' => 'id',
//  'title_key' => 'LBL_J_CLASS_J_CLASS_1_FROM_J_CLASS_R_TITLE',
//  'get_subpanel_data' => 'j_class_j_class_1',
//  'top_buttons' => 
//  array (
//    0 => 
//    array (
//      'widget_class' => 'SubPanelTopButtonQuickCreate',
//    ),
//    1 => 
//    array (
//      'widget_class' => 'SubPanelTopSelectButton',
//      'mode' => 'MultiSelect',
//    ),
//  ),
//);


 // created: 2015-07-16 09:00:12
$layout_defs["J_Class"]["subpanel_setup"]['j_class_j_feedback_1'] = array (
  'order' => 60,
  'module' => 'J_Feedback',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_J_CLASS_J_FEEDBACK_1_FROM_J_FEEDBACK_TITLE',
  'get_subpanel_data' => 'j_class_j_feedback_1',
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


 // created: 2016-05-12 11:08:29
$layout_defs["J_Class"]["subpanel_setup"]['j_class_j_gradebook_1'] = array (
  'order' => 50,
  'module' => 'J_Gradebook',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'name',
  'title_key' => 'LBL_J_CLASS_J_GRADEBOOK_1_FROM_J_GRADEBOOK_TITLE',
  'get_subpanel_data' => 'j_class_j_gradebook_1',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),   
  ),
);


 // created: 2017-10-12 01:41:43
$layout_defs["J_Class"]["subpanel_setup"]['j_class_j_teachercontract_1'] = array (
  'order' => 32,
  'module' => 'J_Teachercontract',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_J_CLASS_J_TEACHERCONTRACT_1_FROM_J_TEACHERCONTRACT_TITLE',
  'get_subpanel_data' => 'j_class_j_teachercontract_1',
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


 // created: 2015-10-19 08:53:40
//$layout_defs["J_Class"]["subpanel_setup"]['j_class_leads_1'] = array (
//  'order' => 100,
//  'module' => 'Leads',
//  'subpanel_name' => 'default',
//  'sort_order' => 'asc',
//  'sort_by' => 'id',
//  'title_key' => 'LBL_J_CLASS_LEADS_1_FROM_LEADS_TITLE',
//  'get_subpanel_data' => 'j_class_leads_1',
//  'top_buttons' => 
//  array (
//    0 => 
//    array (
//      'widget_class' => 'SubPanelTopButtonQuickCreate',
//    ),
//    1 => 
//    array (
//      'widget_class' => 'SubPanelTopSelectButton',
//      'mode' => 'MultiSelect',
//    ),
//  ),
//);


//auto-generated file DO NOT EDIT
$layout_defs['J_Class']['subpanel_setup']['j_class_contacts_1']['override_subpanel_name'] = 'J_Class_subpanel_j_class_contacts_1';


//auto-generated file DO NOT EDIT
$layout_defs['J_Class']['subpanel_setup']['j_class_c_teachers_1']['override_subpanel_name'] = 'J_Class_subpanel_j_class_c_teachers_1';


//auto-generated file DO NOT EDIT
$layout_defs['J_Class']['subpanel_setup']['j_class_j_feedback_1']['override_subpanel_name'] = 'J_Class_subpanel_j_class_j_feedback_1';


//auto-generated file DO NOT EDIT
$layout_defs['J_Class']['subpanel_setup']['j_class_j_gradebook_1']['override_subpanel_name'] = 'J_Class_subpanel_j_class_j_gradebook_1';


//auto-generated file DO NOT EDIT
$layout_defs['J_Class']['subpanel_setup']['j_class_meetings']['override_subpanel_name'] = 'J_Class_subpanel_j_class_meetings';


//auto-generated file DO NOT EDIT
$layout_defs['J_Class']['subpanel_setup']['j_class_meetings_syllabus']['override_subpanel_name'] = 'J_Class_subpanel_j_class_meetings_syllabus';


//auto-generated file DO NOT EDIT
$layout_defs['J_Class']['subpanel_setup']['j_class_studentsituations']['override_subpanel_name'] = 'J_Class_subpanel_j_class_studentsituations';

?>