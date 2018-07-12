<?php
 // created: 2014-05-19 19:45:57
$layout_defs["Users"]["subpanel_setup"]['c_kpi_group_users_1'] = array (
  'order' => 100,
  'module' => 'C_KPI_Group',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_C_KPI_GROUP_USERS_1_FROM_C_KPI_GROUP_TITLE',
  'get_subpanel_data' => 'c_kpi_group_users_1',
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
