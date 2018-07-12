<?php
 // created: 2015-06-18 09:48:49
$layout_defs["Contacts"]["subpanel_setup"]['contacts_c_payments_2'] = array (
  'order' => 100,
  'module' => 'C_Payments',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CONTACTS_C_PAYMENTS_2_FROM_C_PAYMENTS_TITLE',
  'get_subpanel_data' => 'contacts_c_payments_2',
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
