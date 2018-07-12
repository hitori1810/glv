<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2016-11-25 10:58:12
$layout_defs["Contracts"]["subpanel_setup"]['contracts_j_class_1'] = array (
  'order' => 29,
  'module' => 'J_Class',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CONTRACTS_J_CLASS_1_FROM_J_CLASS_TITLE',
  'get_subpanel_data' => 'contracts_j_class_1',
  'top_buttons' =>
  array (
    0 =>
    array (
      'widget_class' => 'SubPanelTopCreateButton',
    ),
    1 =>
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);


 // created: 2014-12-02 16:32:02
//$layout_defs["Contracts"]["subpanel_setup"]['contracts_meetings_1'] = array (
//  'order' => 100,
//  'module' => 'Meetings',
//  'subpanel_name' => 'default',
//  'sort_order' => 'asc',
//  'sort_by' => 'id',
//  'title_key' => 'LBL_CONTRACTS_MEETINGS_1_FROM_MEETINGS_TITLE',
//  'get_subpanel_data' => 'contracts_meetings_1',
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


$layout_defs["Contracts"]["subpanel_setup"]["payment_paymentdetails"] = array (
    'order' => 50,
    'module' => 'J_PaymentDetail',
    'subpanel_name' => 'default',
    'title_key' => 'LBL_PAYMENT_DETAIL',
    'sort_order' => 'asc',
    'sort_by' => 'status',
    'get_subpanel_data' => 'paymentdetail_link',
    'top_buttons' =>
    array (
    ),
);


//auto-generated file DO NOT EDIT
$layout_defs['Contracts']['subpanel_setup']['contacts']['override_subpanel_name'] = 'Contract_subpanel_contacts';


//auto-generated file DO NOT EDIT
$layout_defs['Contracts']['subpanel_setup']['contracts_contacts_1']['override_subpanel_name'] = 'Contract_subpanel_contracts_contacts_1';

?>