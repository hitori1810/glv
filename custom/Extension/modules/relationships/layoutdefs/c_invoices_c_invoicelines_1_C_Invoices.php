<?php
 // created: 2014-04-12 00:22:44
$layout_defs["C_Invoices"]["subpanel_setup"]['c_invoices_c_invoicelines_1'] = array (
  'order' => 100,
  'module' => 'C_Invoicelines',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_C_INVOICES_C_INVOICELINES_1_FROM_C_INVOICELINES_TITLE',
  'get_subpanel_data' => 'c_invoices_c_invoicelines_1',
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
