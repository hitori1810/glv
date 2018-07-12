<?php
 // created: 2014-05-07 08:53:29
$layout_defs["Contacts"]["subpanel_setup"]['contacts_c_contacts_1'] = array (
  'order' => 100,
  'module' => 'C_Contacts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CONTACTS_C_CONTACTS_1_FROM_C_CONTACTS_TITLE',
  'get_subpanel_data' => 'contacts_c_contacts_1',
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
