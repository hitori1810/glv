<?php
 // created: 2014-09-16 16:03:36
$layout_defs["C_Memberships"]["subpanel_setup"]['c_memberships_contacts_1'] = array (
  'order' => 100,
  'module' => 'Contacts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_C_MEMBERSHIPS_CONTACTS_1_FROM_CONTACTS_TITLE',
  'get_subpanel_data' => 'c_memberships_contacts_1',
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
