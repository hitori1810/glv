<?php
 // created: 2014-04-12 00:31:10
$layout_defs["Accounts"]["subpanel_setup"]['accounts_c_payments_1'] = array (
  'order' => 100,
  'module' => 'C_Payments',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_ACCOUNTS_C_PAYMENTS_1_FROM_C_PAYMENTS_TITLE',
  'get_subpanel_data' => 'accounts_c_payments_1',
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
