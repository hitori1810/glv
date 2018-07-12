<?php
// created: 2015-07-25 14:52:02
$dictionary["J_Inventorydetail"]["fields"]["j_inventory_j_inventorydetail_1"] = array (
  'name' => 'j_inventory_j_inventorydetail_1',
  'type' => 'link',
  'relationship' => 'j_inventory_j_inventorydetail_1',
  'source' => 'non-db',
  'module' => 'J_Inventory',
  'bean_name' => 'J_Inventory',
  'side' => 'right',
  'vname' => 'LBL_J_INVENTORY_J_INVENTORYDETAIL_1_FROM_J_INVENTORYDETAIL_TITLE',
  'id_name' => 'j_inventory_j_inventorydetail_1j_inventory_ida',
  'link-type' => 'one',
);
$dictionary["J_Inventorydetail"]["fields"]["j_inventory_j_inventorydetail_1_name"] = array (
  'name' => 'j_inventory_j_inventorydetail_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_INVENTORY_J_INVENTORYDETAIL_1_FROM_J_INVENTORY_TITLE',
  'save' => true,
  'id_name' => 'j_inventory_j_inventorydetail_1j_inventory_ida',
  'link' => 'j_inventory_j_inventorydetail_1',
  'table' => 'j_inventory',
  'module' => 'J_Inventory',
  'rname' => 'name',
);
$dictionary["J_Inventorydetail"]["fields"]["j_inventory_j_inventorydetail_1j_inventory_ida"] = array (
  'name' => 'j_inventory_j_inventorydetail_1j_inventory_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_INVENTORY_J_INVENTORYDETAIL_1_FROM_J_INVENTORYDETAIL_TITLE_ID',
  'id_name' => 'j_inventory_j_inventorydetail_1j_inventory_ida',
  'link' => 'j_inventory_j_inventorydetail_1',
  'table' => 'j_inventory',
  'module' => 'J_Inventory',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
