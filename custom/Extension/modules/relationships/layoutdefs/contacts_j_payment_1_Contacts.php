<?php
 // created: 2015-07-14 16:44:03
$layout_defs["Contacts"]["subpanel_setup"]['contacts_j_payment_1'] = array (
  'order' => 100,
  'module' => 'J_Payment',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CONTACTS_J_PAYMENT_1_FROM_J_PAYMENT_TITLE',
  'get_subpanel_data' => 'contacts_j_payment_1',
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
