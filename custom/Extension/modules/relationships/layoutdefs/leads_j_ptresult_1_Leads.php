<?php
 // created: 2015-09-07 09:49:06
$layout_defs["Leads"]["subpanel_setup"]['leads_j_ptresult_1'] = array (
  'order' => 100,
  'module' => 'J_PTResult',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_LEADS_J_PTRESULT_1_FROM_J_PTRESULT_TITLE',
  'get_subpanel_data' => 'leads_j_ptresult_1',
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
