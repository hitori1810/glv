<?php
 // created: 2015-08-06 14:27:38
$layout_defs["C_Contacts"]["subpanel_setup"]['c_contacts_contacts_1'] = array (
  'order' => 100,
  'module' => 'Contacts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_C_CONTACTS_CONTACTS_1_FROM_CONTACTS_TITLE',
  'get_subpanel_data' => 'c_contacts_contacts_1',
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
