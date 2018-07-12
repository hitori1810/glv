<?php
// created: 2016-08-05 15:50:22
$dictionary["calls_j_class_1"] = array (
  'true_relationship_type' => 'one-to-one',
  'from_studio' => true,
  'relationships' => 
  array (
    'calls_j_class_1' => 
    array (
      'lhs_module' => 'Calls',
      'lhs_table' => 'calls',
      'lhs_key' => 'id',
      'rhs_module' => 'J_Class',
      'rhs_table' => 'j_class',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'calls_j_class_1_c',
      'join_key_lhs' => 'calls_j_class_1calls_ida',
      'join_key_rhs' => 'calls_j_class_1j_class_idb',
    ),
  ),
  'table' => 'calls_j_class_1_c',
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
      'name' => 'calls_j_class_1calls_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'calls_j_class_1j_class_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'calls_j_class_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'calls_j_class_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'calls_j_class_1calls_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'calls_j_class_1_idb2',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'calls_j_class_1j_class_idb',
      ),
    ),
  ),
);