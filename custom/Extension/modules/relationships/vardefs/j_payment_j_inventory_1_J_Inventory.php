<?php
// created: 2015-07-28 09:30:06
$dictionary["J_Inventory"]["fields"]["j_payment_j_inventory_1"] = array (
  'name' => 'j_payment_j_inventory_1',
  'type' => 'link',
  'relationship' => 'j_payment_j_inventory_1',
  'source' => 'non-db',
  'module' => 'J_Payment',
  'bean_name' => 'J_Payment',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_1_FROM_J_PAYMENT_TITLE',
  'id_name' => 'j_payment_j_inventory_1j_payment_ida',
);
$dictionary["J_Inventory"]["fields"]["j_payment_j_inventory_1_name"] = array (
  'name' => 'j_payment_j_inventory_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_1_FROM_J_PAYMENT_TITLE',
  'save' => true,
  'id_name' => 'j_payment_j_inventory_1j_payment_ida',
  'link' => 'j_payment_j_inventory_1',
  'table' => 'j_payment',
  'module' => 'J_Payment',
  'rname' => 'name',
);
$dictionary["J_Inventory"]["fields"]["j_payment_j_inventory_1j_payment_ida"] = array (
  'name' => 'j_payment_j_inventory_1j_payment_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_1_FROM_J_PAYMENT_TITLE_ID',
  'id_name' => 'j_payment_j_inventory_1j_payment_ida',
  'link' => 'j_payment_j_inventory_1',
  'table' => 'j_payment',
  'module' => 'J_Payment',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
