<?php
 // created: 2015-10-19 08:53:40
$layout_defs["Leads"]["subpanel_setup"]['j_class_leads_1'] = array (
  'order' => 100,
  'module' => 'J_Class',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_J_CLASS_LEADS_1_FROM_J_CLASS_TITLE',
  'get_subpanel_data' => 'j_class_leads_1',
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
