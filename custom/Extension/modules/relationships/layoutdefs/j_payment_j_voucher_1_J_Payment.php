<?php
 // created: 2017-01-16 17:18:07
$layout_defs["J_Payment"]["subpanel_setup"]['j_payment_j_voucher_1'] = array (
  'order' => 100,
  'module' => 'J_Voucher',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_J_PAYMENT_J_VOUCHER_1_FROM_J_VOUCHER_TITLE',
  'get_subpanel_data' => 'j_payment_j_voucher_1',
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
