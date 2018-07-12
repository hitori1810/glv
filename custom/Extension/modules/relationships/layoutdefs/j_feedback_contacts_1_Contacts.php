<?php
 // created: 2015-07-08 14:27:00
$layout_defs["Contacts"]["subpanel_setup"]['j_feedback_contacts_1'] = array (
  'order' => 100,
  'module' => 'J_Feedback',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_J_FEEDBACK_CONTACTS_1_FROM_J_FEEDBACK_TITLE',
  'get_subpanel_data' => 'j_feedback_contacts_1',
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
