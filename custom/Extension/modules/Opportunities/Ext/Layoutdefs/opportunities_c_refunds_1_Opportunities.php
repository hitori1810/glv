<?php
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
