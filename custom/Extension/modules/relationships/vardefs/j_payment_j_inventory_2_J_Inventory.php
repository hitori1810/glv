<?php
// created: 2015-08-19 17:33:43
$dictionary["J_Inventory"]["fields"]["j_payment_j_inventory_2"] = array (
  'name' => 'j_payment_j_inventory_2',
  'type' => 'link',
  'relationship' => 'j_payment_j_inventory_2',
  'source' => 'non-db',
  'module' => 'J_Payment',
  'bean_name' => 'J_Payment',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_2_FROM_J_PAYMENT_TITLE',
  'id_name' => 'j_payment_j_inventory_2j_payment_ida',
);
$dictionary["J_Inventory"]["fields"]["j_payment_j_inventory_2_name"] = array (
  'name' => 'j_payment_j_inventory_2_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_2_FROM_J_PAYMENT_TITLE',
  'save' => true,
  'id_name' => 'j_payment_j_inventory_2j_payment_ida',
  'link' => 'j_payment_j_inventory_2',
  'table' => 'j_payment',
  'module' => 'J_Payment',
  'rname' => 'name',
);
$dictionary["J_Inventory"]["fields"]["j_payment_j_inventory_2j_payment_ida"] = array (
  'name' => 'j_payment_j_inventory_2j_payment_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_2_FROM_J_PAYMENT_TITLE_ID',
  'id_name' => 'j_payment_j_inventory_2j_payment_ida',
  'link' => 'j_payment_j_inventory_2',
  'table' => 'j_payment',
  'module' => 'J_Payment',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
