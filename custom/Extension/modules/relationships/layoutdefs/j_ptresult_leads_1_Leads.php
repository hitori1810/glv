<?php
 // created: 2015-08-25 11:34:29
$layout_defs["Leads"]["subpanel_setup"]['j_ptresult_leads_1'] = array (
  'order' => 100,
  'module' => 'J_PTResult',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_J_PTRESULT_LEADS_1_FROM_J_PTRESULT_TITLE',
  'get_subpanel_data' => 'j_ptresult_leads_1',
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
