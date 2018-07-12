<?php
// created: 2015-07-21 17:32:55
$dictionary["j_discount_j_discount_1"] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'j_discount_j_discount_1' => 
    array (
      'lhs_module' => 'J_Discount',
      'lhs_table' => 'j_discount',
      'lhs_key' => 'id',
      'rhs_module' => 'J_Discount',
      'rhs_table' => 'j_discount',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'j_discount_j_discount_1_c',
      'join_key_lhs' => 'j_discount_j_discount_1j_discount_ida',
      'join_key_rhs' => 'j_discount_j_discount_1j_discount_idb',
    ),
  ),
  'table' => 'j_discount_j_discount_1_c',
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
      'name' => 'j_discount_j_discount_1j_discount_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'j_discount_j_discount_1j_discount_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'j_discount_j_discount_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'j_discount_j_discount_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'j_discount_j_discount_1j_discount_ida',
        1 => 'j_discount_j_discount_1j_discount_idb',
      ),
    ),
  ),
);