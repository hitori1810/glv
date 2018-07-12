<?php
// created: 2015-07-25 12:44:10
$dictionary["j_inventory_j_intenvorydetail_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'j_inventory_j_intenvorydetail_1' => 
    array (
      'lhs_module' => 'J_Inventory',
      'lhs_table' => 'j_inventory',
      'lhs_key' => 'id',
      'rhs_module' => 'J_Intenvorydetail',
      'rhs_table' => 'j_intenvorydetail',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'j_inventory_j_intenvorydetail_1_c',
      'join_key_lhs' => 'j_inventory_j_intenvorydetail_1j_inventory_ida',
      'join_key_rhs' => 'j_inventory_j_intenvorydetail_1j_intenvorydetail_idb',
    ),
  ),
  'table' => 'j_inventory_j_intenvorydetail_1_c',
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
      'name' => 'j_inventory_j_intenvorydetail_1j_inventory_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'j_inventory_j_intenvorydetail_1j_intenvorydetail_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'j_inventory_j_intenvorydetail_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'j_inventory_j_intenvorydetail_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'j_inventory_j_intenvorydetail_1j_inventory_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'j_inventory_j_intenvorydetail_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'j_inventory_j_intenvorydetail_1j_intenvorydetail_idb',
      ),
    ),
  ),
);