<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2014-12-02 16:32:02
//$layout_defs["Meetings"]["subpanel_setup"]['contracts_meetings_1'] = array (
//  'order' => 100,
//  'module' => 'Contracts',
//  'subpanel_name' => 'default',
//  'sort_order' => 'asc',
//  'sort_by' => 'id',
//  'title_key' => 'LBL_CONTRACTS_MEETINGS_1_FROM_CONTRACTS_TITLE',
//  'get_subpanel_data' => 'contracts_meetings_1',
//  'top_buttons' => 
//  array (
////    0 => 
////    array (
////      'widget_class' => 'SubPanelTopButtonQuickCreate',
////    ),
////    1 => 
////    array (
////      'widget_class' => 'SubPanelTopSelectButton',
////      'mode' => 'MultiSelect',
////    ),
//  ),
//);


 // created: 2015-08-05 15:56:02
/*$layout_defs["Meetings"]["subpanel_setup"]['meetings_j_ptresult_1'] = array (
  'order' => 100,
  'module' => 'J_PTResult',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_MEETINGS_J_PTRESULT_1_FROM_J_PTRESULT_TITLE',
  'get_subpanel_data' => 'meetings_j_ptresult_1',
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
); */


 // created: 2014-11-22 02:54:14
//$layout_defs["Meetings"]["subpanel_setup"]['opportunities_meetings_1'] = array (
//  'order' => 100,
//  'module' => 'Opportunities',
//  'subpanel_name' => 'default',
//  'sort_order' => 'asc',
//  'sort_by' => 'id',
//  'title_key' => 'LBL_OPPORTUNITIES_MEETINGS_1_FROM_OPPORTUNITIES_TITLE',
//  'get_subpanel_data' => 'opportunities_meetings_1',
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



//auto-generated file DO NOT EDIT
$layout_defs['Meetings']['subpanel_setup']['contacts']['override_subpanel_name'] = 'Meeting_subpanel_contacts';


//auto-generated file DO NOT EDIT
$layout_defs['Meetings']['subpanel_setup']['leads']['override_subpanel_name'] = 'Meeting_subpanel_leads';


//auto-generated file DO NOT EDIT
//$layout_defs['Meetings']['subpanel_setup']['meetings_j_ptresult_1']['override_subpanel_name'] = 'Meeting_subpanel_meetings_j_ptresult_1';

?>