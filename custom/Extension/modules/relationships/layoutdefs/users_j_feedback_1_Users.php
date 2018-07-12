<?php
 // created: 2017-01-05 12:05:27
$layout_defs["Users"]["subpanel_setup"]['users_j_feedback_1'] = array (
  'order' => 100,
  'module' => 'J_Feedback',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_USERS_J_FEEDBACK_1_FROM_J_FEEDBACK_TITLE',
  'get_subpanel_data' => 'users_j_feedback_1',
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
