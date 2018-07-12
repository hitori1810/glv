<?php
 // created: 2017-08-10 20:47:56
$layout_defs["C_Contacts"]["subpanel_setup"]['c_contacts_contracts_1'] = array (
  'order' => 100,
  'module' => 'Contracts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_C_CONTACTS_CONTRACTS_1_FROM_CONTRACTS_TITLE',
  'get_subpanel_data' => 'c_contacts_contracts_1',
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
