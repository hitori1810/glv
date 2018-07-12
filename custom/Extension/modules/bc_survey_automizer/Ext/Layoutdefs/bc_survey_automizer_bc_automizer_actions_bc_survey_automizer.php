<?php
 // created: 2016-07-07 16:43:11
$layout_defs["bc_survey_automizer"]["subpanel_setup"]['bc_survey_automizer_bc_automizer_actions'] = array (
  'order' => 100,
  'module' => 'bc_automizer_actions',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_BC_SURVEY_AUTOMIZER_BC_AUTOMIZER_ACTIONS_FROM_BC_AUTOMIZER_ACTIONS_TITLE',
  'get_subpanel_data' => 'bc_survey_automizer_bc_automizer_actions',
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
