<?php
 // created: 2015-07-16 08:57:21
$layout_defs["Contacts"]["subpanel_setup"]['contacts_j_feedback_1'] = array (
  'order' => 100,
  'module' => 'J_Feedback',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CONTACTS_J_FEEDBACK_1_FROM_J_FEEDBACK_TITLE',
  'get_subpanel_data' => 'contacts_j_feedback_1',
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
