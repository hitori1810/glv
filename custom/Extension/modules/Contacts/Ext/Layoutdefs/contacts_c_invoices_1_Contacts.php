<?php
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
