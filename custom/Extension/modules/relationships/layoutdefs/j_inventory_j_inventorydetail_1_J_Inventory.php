<?php
 // created: 2015-07-25 14:52:02
$layout_defs["J_Inventory"]["subpanel_setup"]['j_inventory_j_inventorydetail_1'] = array (
  'order' => 100,
  'module' => 'J_Inventorydetail',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_J_INVENTORY_J_INVENTORYDETAIL_1_FROM_J_INVENTORYDETAIL_TITLE',
  'get_subpanel_data' => 'j_inventory_j_inventorydetail_1',
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
