<?php
 // created: 2015-08-25 11:31:05
$layout_defs["J_PTResult"]["subpanel_setup"]['j_ptresult_contacts_1'] = array (
  'order' => 100,
  'module' => 'Contacts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_J_PTRESULT_CONTACTS_1_FROM_CONTACTS_TITLE',
  'get_subpanel_data' => 'j_ptresult_contacts_1',
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
