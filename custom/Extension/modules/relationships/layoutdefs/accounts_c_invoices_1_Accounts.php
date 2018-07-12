<?php
 // created: 2014-04-12 00:29:43
$layout_defs["Accounts"]["subpanel_setup"]['accounts_c_invoices_1'] = array (
  'order' => 100,
  'module' => 'C_Invoices',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_ACCOUNTS_C_INVOICES_1_FROM_C_INVOICES_TITLE',
  'get_subpanel_data' => 'accounts_c_invoices_1',
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
