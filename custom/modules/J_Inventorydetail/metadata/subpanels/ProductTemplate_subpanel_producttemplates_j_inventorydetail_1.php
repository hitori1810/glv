<?php
// created: 2015-07-29 16:39:01
$subpanel_layout['list_fields'] = array (
  'j_inventory_j_inventorydetail_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_J_INVENTORY_J_INVENTORYDETAIL_1_FROM_J_INVENTORY_TITLE',
    'id' => 'J_INVENTORY_J_INVENTORYDETAIL_1J_INVENTORY_IDA',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'J_Inventory',
    'target_record_key' => 'j_inventory_j_inventorydetail_1j_inventory_ida',
  ),
  'quantity' => 
  array (
    'type' => 'int',
    'vname' => 'LBL_QUANTITY',
    'width' => '10%',
    'default' => true,
  ),
  'price' => 
  array (
    'type' => 'currency',
    'vname' => 'LBL_PRICE',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'remark' => 
  array (
    'type' => 'text',
    'vname' => 'LBL_REMARK',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'amount' => 
  array (
    'type' => 'currency',
    'vname' => 'LBL_AMOUNT',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'date_modified' => 
  array (
    'vname' => 'LBL_DATE_MODIFIED',
    'width' => '45%',
    'default' => true,
  ),
);