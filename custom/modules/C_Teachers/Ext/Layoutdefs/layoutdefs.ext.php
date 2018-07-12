<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2014-07-15 14:43:15
$layout_defs["C_Teachers"]["subpanel_setup"]['c_classes_c_teachers_1'] = array (
  'order' => 100,
  'module' => 'C_Classes',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_C_CLASSES_C_TEACHERS_1_FROM_C_CLASSES_TITLE',
  'get_subpanel_data' => 'c_classes_c_teachers_1',
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


 // created: 2016-07-27 11:01:40
$layout_defs["C_Teachers"]["subpanel_setup"]['c_teachers_j_gradebook_1'] = array (
  'order' => 100,
  'module' => 'J_Gradebook',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_C_TEACHERS_J_GRADEBOOK_1_FROM_J_GRADEBOOK_TITLE',
  'get_subpanel_data' => 'c_teachers_j_gradebook_1',
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


 // created: 2015-07-24 11:57:42
$layout_defs["C_Teachers"]["subpanel_setup"]['c_teachers_j_teachercontract_1'] = array (
  'order' => 100,
  'module' => 'J_Teachercontract',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_C_TEACHERS_J_TEACHERCONTRACT_1_FROM_J_TEACHERCONTRACT_TITLE',
  'get_subpanel_data' => 'c_teachers_j_teachercontract_1',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
   /* 1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),*/
  ),
);


    $layout_defs["C_Teachers"]["subpanel_setup"]["teachers_meetings"] = array (
        'order' => 100,
        'module' => 'Meetings',
        'subpanel_name' => 'default',
        'title_key' => 'LBL_MEETING',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'get_subpanel_data' => 'meetings',
        'top_buttons' => 
        array (
        ),
    );



 // created: 2015-08-11 08:50:10
$layout_defs["C_Teachers"]["subpanel_setup"]['j_class_c_teachers_1'] = array (
  'order' => 100,
  'module' => 'J_Class',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_J_CLASS_C_TEACHERS_1_FROM_J_CLASS_TITLE',
  'get_subpanel_data' => 'j_class_c_teachers_1',
  'top_buttons' => 
  array (
    /*0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),*/
  ),
);


  
  $layout_defs["C_Teachers"]["subpanel_setup"]['teacher_holidays'] = array (
  'order' => 110,
  'module' => 'Holidays',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_HOLIDAYS',
  'get_subpanel_data' => 'teacher_holidays',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
  ),
);


 
 // created: 2016-05-23 03:55:52

							$layout_defs['C_Teachers']['subpanel_setup']['c_teachers_c_sms'] = array (
							  'order' => 250,
							  'module' => 'C_SMS',
							  'subpanel_name' => 'default',
							  'sort_order' => 'asc',
							  'sort_by' => 'date_entered',
							  'title_key' => 'LBL_C_SMS',
							  'get_subpanel_data' => 'c_teachers_c_sms',
							  'top_buttons' =>
							  array (
								    array('widget_class' => 'SubPanelSMSButton')
							  ),
							);
							

//auto-generated file DO NOT EDIT
$layout_defs['C_Teachers']['subpanel_setup']['c_classes_c_teachers_1']['override_subpanel_name'] = 'C_Teachers_subpanel_c_classes_c_teachers_1';


//auto-generated file DO NOT EDIT
$layout_defs['C_Teachers']['subpanel_setup']['c_teachers_j_teachercontract_1']['override_subpanel_name'] = 'C_Teachers_subpanel_c_teachers_j_teachercontract_1';


//auto-generated file DO NOT EDIT
$layout_defs['C_Teachers']['subpanel_setup']['teachers_meetings']['override_subpanel_name'] = 'C_Teachers_subpanel_teachers_meetings';

?>