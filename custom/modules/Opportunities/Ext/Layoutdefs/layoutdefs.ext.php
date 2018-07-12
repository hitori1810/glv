<?php 
 //WARNING: The contents of this file are auto-generated


    // created: 2014-11-12 02:35:22
    $layout_defs["Opportunities"]["subpanel_setup"]['c_classes_opportunities_1'] = array (
        'order' => 100,
        'module' => 'C_Classes',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_C_CLASSES_OPPORTUNITIES_1_FROM_C_CLASSES_TITLE',
        'get_subpanel_data' => 'c_classes_opportunities_1',
        //Remove Button
        'top_buttons' =>
        array (),
    );


 // created: 2014-04-30 19:59:14
$layout_defs["Opportunities"]["subpanel_setup"]['opportunities_c_refunds_1'] = array (
  'order' => 100,
  'module' => 'C_Refunds',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_OPPORTUNITIES_C_REFUNDS_1_FROM_C_REFUNDS_TITLE',
  'get_subpanel_data' => 'opportunities_c_refunds_1',
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


 // created: 2014-11-22 02:54:14
$layout_defs["Opportunities"]["subpanel_setup"]['opportunities_meetings_1'] = array (
  'order' => 100,
  'module' => 'Meetings',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_OPPORTUNITIES_MEETINGS_1_FROM_MEETINGS_TITLE',
  'get_subpanel_data' => 'opportunities_meetings_1',
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


//auto-generated file DO NOT EDIT
$layout_defs['Opportunities']['subpanel_setup']['contacts']['override_subpanel_name'] = 'Opportunity_subpanel_contacts';


//auto-generated file DO NOT EDIT
$layout_defs['Opportunities']['subpanel_setup']['leads']['override_subpanel_name'] = 'Opportunity_subpanel_leads';

?>