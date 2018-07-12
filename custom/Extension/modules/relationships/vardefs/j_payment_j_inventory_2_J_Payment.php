<?php
// created: 2015-08-19 17:33:43
$dictionary["J_Payment"]["fields"]["j_payment_j_inventory_2"] = array (
  'name' => 'j_payment_j_inventory_2',
  'type' => 'link',
  'relationship' => 'j_payment_j_inventory_2',
  'source' => 'non-db',
  'module' => 'J_Inventory',
  'bean_name' => 'J_Inventory',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_2_FROM_J_INVENTORY_TITLE',
  'id_name' => 'j_payment_j_inventory_2j_inventory_idb',
);
$dictionary["J_Payment"]["fields"]["j_payment_j_inventory_2_name"] = array (
  'name' => 'j_payment_j_inventory_2_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_2_FROM_J_INVENTORY_TITLE',
  'save' => true,
  'id_name' => 'j_payment_j_inventory_2j_inventory_idb',
  'link' => 'j_payment_j_inventory_2',
  'table' => 'j_inventory',
  'module' => 'J_Inventory',
  'rname' => 'name',
);
$dictionary["J_Payment"]["fields"]["j_payment_j_inventory_2j_inventory_idb"] = array (
  'name' => 'j_payment_j_inventory_2j_inventory_idb',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_2_FROM_J_INVENTORY_TITLE_ID',
  'id_name' => 'j_payment_j_inventory_2j_inventory_idb',
  'link' => 'j_payment_j_inventory_2',
  'table' => 'j_inventory',
  'module' => 'J_Inventory',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
