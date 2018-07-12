<?php
// created: 2015-08-19 17:33:43
$dictionary["j_payment_j_inventory_2"] = array (
  'true_relationship_type' => 'one-to-one',
  'from_studio' => true,
  'relationships' => 
  array (
    'j_payment_j_inventory_2' => 
    array (
      'lhs_module' => 'J_Payment',
      'lhs_table' => 'j_payment',
      'lhs_key' => 'id',
      'rhs_module' => 'J_Inventory',
      'rhs_table' => 'j_inventory',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'j_payment_j_inventory_2_c',
      'join_key_lhs' => 'j_payment_j_inventory_2j_payment_ida',
      'join_key_rhs' => 'j_payment_j_inventory_2j_inventory_idb',
    ),
  ),
  'table' => 'j_payment_j_inventory_2_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'j_payment_j_inventory_2j_payment_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'j_payment_j_inventory_2j_inventory_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'j_payment_j_inventory_2spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'j_payment_j_inventory_2_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'j_payment_j_inventory_2j_payment_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'j_payment_j_inventory_2_idb2',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'j_payment_j_inventory_2j_inventory_idb',
      ),
    ),
  ),
);