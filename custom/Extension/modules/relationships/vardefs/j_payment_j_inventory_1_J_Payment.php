<?php
// created: 2015-07-28 09:30:06
$dictionary["J_Payment"]["fields"]["j_payment_j_inventory_1"] = array (
  'name' => 'j_payment_j_inventory_1',
  'type' => 'link',
  'relationship' => 'j_payment_j_inventory_1',
  'source' => 'non-db',
  'module' => 'J_Inventory',
  'bean_name' => 'J_Inventory',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_1_FROM_J_INVENTORY_TITLE',
  'id_name' => 'j_payment_j_inventory_1j_inventory_idb',
);
$dictionary["J_Payment"]["fields"]["j_payment_j_inventory_1_name"] = array (
  'name' => 'j_payment_j_inventory_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_1_FROM_J_INVENTORY_TITLE',
  'save' => true,
  'id_name' => 'j_payment_j_inventory_1j_inventory_idb',
  'link' => 'j_payment_j_inventory_1',
  'table' => 'j_inventory',
  'module' => 'J_Inventory',
  'rname' => 'name',
);
$dictionary["J_Payment"]["fields"]["j_payment_j_inventory_1j_inventory_idb"] = array (
  'name' => 'j_payment_j_inventory_1j_inventory_idb',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_1_FROM_J_INVENTORY_TITLE_ID',
  'id_name' => 'j_payment_j_inventory_1j_inventory_idb',
  'link' => 'j_payment_j_inventory_1',
  'table' => 'j_inventory',
  'module' => 'J_Inventory',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
