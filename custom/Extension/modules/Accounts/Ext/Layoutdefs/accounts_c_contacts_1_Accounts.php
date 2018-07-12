<?php
 // created: 2017-08-09 23:33:27
$layout_defs["Accounts"]["subpanel_setup"]['accounts_c_contacts_1'] = array (
  'order' => 100,
  'module' => 'C_Contacts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_ACCOUNTS_C_CONTACTS_1_FROM_C_CONTACTS_TITLE',
  'get_subpanel_data' => 'accounts_c_contacts_1',
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
